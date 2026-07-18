<?php
 declare(strict_types=1);

 namespace Models\Public;
    use PDO;
    use PDOException;
 class search
 {
     private PDO $conn;

     public function __construct(PDO $conn)
     {
         $this->conn = $conn;
     }

     public function searchStudentByMatricNumber(string $matricNumber): ?array
     {
         $stmt = $this->conn->prepare("SELECT student_id, student_fname, student_lname FROM students WHERE matric_number = :matricNumber");
         $stmt->bindParam(':matricNumber', $matricNumber);
         $stmt->execute();
         return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
     }
     
 }