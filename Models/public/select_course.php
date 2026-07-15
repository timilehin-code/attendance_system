<?php

declare(strict_types=1);

namespace Models\Public;

use PDO;
use PDOException;

class Select_course{
    public string $courseCode;
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
}
