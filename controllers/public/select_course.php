<?php

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

use Models\Public\Select_course;

$selectCourse = new Select_course($pdo);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['course_id'] ?? '';
    if (empty($courseId)) {
        echo json_encode(['success' => false, 'message' => 'Course ID is required.']);
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
        echo json_encode(['success' => true, 'message' => 'Course selected successfully.']);
        header("Location: ../../views/public/index.php?course_id=" . urlencode($courseId) . "&course_code=" . urlencode($course['course_code']) . "&course_title=" . urlencode($course['course_title']));
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found.']);
    }
}
