<?php

declare(strict_types=1);

namespace Models\Public;
use PDO;
use PDOException;
class Attendance {
    public string $matricNumber;
    public string $courseId;
    public string $studentId;
    public PDO $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function checkMatricNumberExists(): bool {
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

    public function getStudentIdByMatricNumber(){
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

    public function recordAttendance(): bool {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO attendance (student_id, course_id, attendance_time) 
                VALUES (:studentId, :courseId, NOW())
            ");
            $stmt->bindParam(':studentId', $this->studentId);
            $stmt->bindParam(':courseId', $this->courseId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error recording attendance: " . $e->getMessage());
            return false;
        }
    }
}