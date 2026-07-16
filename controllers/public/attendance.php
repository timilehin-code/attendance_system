<?php
use Models\Public\Attendance;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

$attendance = new Attendance($pdo);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $matricNumber = trim($_POST['matric_number'] ?? '');
    $courseId     = trim($_POST['course_id'] ?? '');

    if (empty($matricNumber) || empty($courseId)) {
        echo json_encode(['status' => 'error', 'message' => 'Matric number and course are required.']);
        exit;
    }

    try {
        // Set the matric number
        $attendance->matricNumber = strtoupper($matricNumber);

        if ($attendance->checkMatricNumberExists()) {
            $studentId = $attendance->getStudentIdByMatricNumber();
            
            if ($studentId) {
                $attendance->studentId = $studentId;
                $attendance->courseId  = $courseId;

                if ($attendance->recordAttendance()) {
                    echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to record attendance.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Student ID not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Matriculation number does not exist.']);
        }
    } catch (Exception $e) {
        error_log("Attendance Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Server error occurred.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}