<?php

declare(strict_types=1);

namespace Models\Public;

use PDO;
use PDOException;

class Select_course{
    public string $courseCode;

    public string $session;
    public PDO $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function SelectAllCourses()
    {
       try{
            $stmt = $this->conn->prepare("SELECT course_id,course_code,course_title FROM courses");
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courses;

       }catch(PDOException $e){
            error_log("Error selecting courses: " . $e->getMessage());
            return false;
       }
    }  
    
    public function recordClass(){
        try{
            $stmt = $this->conn->prepare("INSERT INTO classes (course_id,session) VALUES (:courseId, :session)");
            $stmt->bindParam(':courseId', $this->courseCode);
            $stmt->bindParam(':session', $this->session);
            if($stmt->execute()) {
                $insertId = $this->conn->lastInsertId();
                return $insertId;
            } else {
                error_log("Error executing recordClass: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        }catch(PDOException $e){
            error_log("Error recording class: " . $e->getMessage());
            return false;
        }
    }
}
