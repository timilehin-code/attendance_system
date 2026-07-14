<?php

declare(strict_types=1);


namespace Models\Admin;

use PDO;
use PDOException;

class Student_reg
{
    public string $firstName;
    public string $lastName;
    public string $matricNumber;
    public int $department;

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
    public function registerStudent(): bool
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO students (student_fname, student_Lname, matric_number, department_id) VALUES (:firstName, :lastName, :matricNumber, :department)");
            $stmt->bindParam(':firstName', $this->firstName);
            $stmt->bindParam(':lastName', $this->lastName);
            $stmt->bindParam(':matricNumber', $this->matricNumber);
            $stmt->bindParam(':department', $this->department);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error registering student: " . $e->getMessage());
            return false;
        }
    }
}
