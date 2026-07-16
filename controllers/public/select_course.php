<?php

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

use Models\Public\Select_course;

$selectCourse = new Select_course($pdo);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['course_id'] ?? '';
    $session = $_POST['session'] ?? '';
    if (empty($courseId) || empty($session)) {
        echo json_encode(['success' => false, 'message' => 'Course ID and session are required.']);
        exit;
    }

    $courses = $selectCourse->SelectAllCourses();
    $courseExists = false;
    foreach ($courses as $course) {
        if ($course['course_id'] == $courseId) {
            $courseExists = true;
            break;
        }
    }

    if ($courseExists) {
        $selectCourse->courseCode = $courseId;
        $selectCourse->session = $session;
        $selectCourse->recordClass();

        echo json_encode(['success' => true, 'message' => 'Course selected successfully.']);
        session_start();
        $_SESSION['course_id'] = $courseId;
        $_SESSION['course_code'] = $course['course_code'];
        $_SESSION['session'] = $session;
        header("Location: ../../views/public/index.php");
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found.']);
    }
}
