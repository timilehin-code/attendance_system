<?php

declare(strict_types=1);

namespace Models\Public;

use PDO;
use PDOException;

class Attendance
{
    public string $matricNumber;
    public string $courseId;
    public string $studentId;
    public PDO $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function checkMatricNumberExists(): bool
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) 
                FROM students 
                WHERE matric_number = :matricNumber
            ");
            $stmt->bindParam(':matricNumber', $this->matricNumber);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            error_log("Error checking matric number: " . $e->getMessage());
            return false;
        }
    }

    public function getStudentIdByMatricNumber()
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT student_id 
                FROM students 
                WHERE matric_number = :matricNumber
            ");
            $stmt->bindParam(':matricNumber', $this->matricNumber);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['student_id'] ?? null;
        } catch (PDOException $e) {
            error_log("Error fetching student ID: " . $e->getMessage());
            return null;
        }
    }
   private function hasAlreadyMarkedToday(): bool
{
    try {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) FROM attendance 
            WHERE student_id = :studentId 
              AND course_id = :courseId 
              AND DATE(attendance_time) = CURDATE()
        ");
        $stmt->bindParam(':studentId', $this->studentId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $this->courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking today's attendance: " . $e->getMessage());
        return false;
    }
}

public function recordAttendance(): bool
{
    try {
        // Check if already marked today
        if ($this->hasAlreadyMarkedToday()) {
            // Throw exception so controller can catch and show nice message
            throw new PDOException("You have already marked attendance for this course today.");
        }

        $stmt = $this->conn->prepare("
            INSERT INTO attendance (student_id, course_id, attendance_time) 
            VALUES (:studentId, :courseId, NOW())
        ");

        $stmt->bindParam(':studentId', $this->studentId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $this->courseId, PDO::PARAM_INT);

        $success = $stmt->execute();

        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            $errorMsg = $errorInfo[2] ?? 'Unknown database error';
            throw new PDOException("Failed to record attendance: " . $errorMsg);
        }

        return true;

    } catch (PDOException $e) {
        error_log("Attendance Error: " . $e->getMessage());
        throw $e;   // Let controller handle the message
    }
}
}
