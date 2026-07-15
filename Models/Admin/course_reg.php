<?php

declare(strict_types=1);


namespace Models\Admin;

use PDO;
use PDOException;

class Course_reg
{
    public string $courseCode;
    public string $courseTitle;

    public PDO $conn;

    public function __construct($conn)
    {
        $this->conn =  $conn;
    }

    public function checkCourseExist(): bool
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*)
                 FROM courses
                 WHERE course_code = :courseCode
                    OR course_title = :courseTitle"
            );

            $stmt->bindParam(':courseCode', $this->courseCode);
            $stmt->bindParam(':courseTitle', $this->courseTitle);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return (int) $count > 0;
        } catch (PDOException $e) {
            error_log("Error checking course existence: " . $e->getMessage());
            return false;
        }
    }

    public function registerCourse(): bool
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO courses (course_code, course_title)
                 VALUES (:courseCode, :courseTitle)"
            );

            $stmt->bindParam(':courseCode', $this->courseCode);
            $stmt->bindParam(':courseTitle', $this->courseTitle);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error registering course: " . $e->getMessage());
            return false;
        }
    }
}
