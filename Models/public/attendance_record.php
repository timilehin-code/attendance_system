<?php
declare(strict_types=1);
namespace Models\Public;
use PDO;
use PDOException;

class attendance_record
{
    public string $studentId;
    public string $session;
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Status thresholds applied to a course's attendance rate.
     * Adjust these cutoffs if your school defines them differently.
     */
    private const STATUS_THRESHOLDS = [
        90 => 'Excellent',
        80 => 'On Track',
        60 => 'Warning',
        0  => 'Critical',
    ];

    /**
     * Returns per-course attendance rows shaped for the dashboard table:
     * [
     *   [
     *     'course_code'        => 'CSC302',
     *     'course_title'       => 'Object oriented programing',
     *     'total_classes_held' => 4,
     *     'total_attended'     => 2,
     *     'attendance_rate'    => 50.0,
     *     'status'             => 'Critical',
     *     'attendance_dates'   => ['2026-07-19', ...],
     *   ],
     *   ...
     * ]
     *
     * Now that `attendance.class_id` links directly to `classes.class_id`,
     * this joins cleanly through classes (which carries the session) rather
     * than guessing by date. Only rows with status = 'present' count toward
     * "classes attended" — an explicit 'absent' row is not attendance.
     */
    public function getAttendanceRecordsByStudentId(): array
    {
        try {
            // 1. Total classes held per course for this session
            $totalsStmt = $this->conn->prepare("
                SELECT cl.course_id, COUNT(cl.class_id) AS total_classes_held
                FROM classes cl
                WHERE cl.session = :session
                GROUP BY cl.course_id
            ");
            $totalsStmt->bindParam(':session', $this->session);
            $totalsStmt->execute();
            $totalsRows = $totalsStmt->fetchAll(PDO::FETCH_ASSOC);

            $totalsByCourse = [];
            foreach ($totalsRows as $row) {
                $totalsByCourse[$row['course_id']] = (int) $row['total_classes_held'];
            }

            // 2. This student's PRESENT attendance records for this session,
            //    joined through classes (for session) to courses (for title/code)
            $attendanceStmt = $this->conn->prepare("
                SELECT
                    c.course_id,
                    c.course_code,
                    c.course_title,
                    cl.session,
                    a.attendance_time
                FROM attendance a
                JOIN classes cl ON a.class_id = cl.class_id
                JOIN courses c ON a.course_id = c.course_id
                WHERE a.student_id = :studentId
                  AND cl.session = :session
                  AND a.status = 'present'
                ORDER BY c.course_title ASC, a.attendance_time DESC
            ");
            $attendanceStmt->bindParam(':studentId', $this->studentId);
            $attendanceStmt->bindParam(':session', $this->session);
            $attendanceStmt->execute();
            $attendanceRows = $attendanceStmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Group attendance rows by course
            $grouped = [];
            foreach ($attendanceRows as $row) {
                $courseId = $row['course_id'];

                if (!isset($grouped[$courseId])) {
                    $grouped[$courseId] = [
                        'course_id'          => $courseId,
                        'course_code'        => $row['course_code'],
                        'course_title'       => $row['course_title'],
                        'total_classes_held' => $totalsByCourse[$courseId] ?? 0,
                        'total_attended'     => 0,
                        'attendance_dates'   => [],
                    ];
                }

                $grouped[$courseId]['total_attended']++;
                $grouped[$courseId]['attendance_dates'][] = $row['attendance_time'];
            }

            // 4. Compute rate + status for each course
            foreach ($grouped as $courseId => &$course) {
                $total = $course['total_classes_held'];
                $attended = $course['total_attended'];
                $rate = $total > 0 ? ($attended / $total) * 100 : 0.0;

                $course['attendance_rate'] = round($rate, 1);
                $course['status'] = $this->resolveStatus($rate);
            }
            unset($course);

            return array_values($grouped);
        } catch (PDOException $e) {
            error_log("Error fetching attendance records: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Overall attendance rate across all courses (total attended / total classes held),
     * e.g. the "92.4%" summary figure at the top of the dashboard.
     */
    public function getOverallAttendanceRate(): float
    {
        $courses = $this->getAttendanceRecordsByStudentId();

        $totalHeld = 0;
        $totalAttended = 0;
        foreach ($courses as $course) {
            $totalHeld += $course['total_classes_held'];
            $totalAttended += $course['total_attended'];
        }

        if ($totalHeld === 0) {
            return 0.0;
        }

        return round(($totalAttended / $totalHeld) * 100, 1);
    }

    /**
     * Maps a numeric attendance rate to a status label using STATUS_THRESHOLDS.
     */
    private function resolveStatus(float $rate): string
    {
        foreach (self::STATUS_THRESHOLDS as $minRate => $label) {
            if ($rate >= $minRate) {
                return $label;
            }
        }
        return 'Critical';
    }
}