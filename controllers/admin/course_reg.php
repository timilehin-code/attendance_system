<?php

use Models\Admin\Course_reg;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseCode  = strtoupper(trim($_POST['course_code'] ?? ''));
    $courseTitle = trim($_POST['course_title'] ?? '');

    // Basic validation
    if (empty($courseCode) || empty($courseTitle)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: ../../views/admin/course_reg.php');
        exit;
    }

    try {
        $courseReg = new Course_reg($pdo);

        $courseReg->courseCode  = $courseCode;
        $courseReg->courseTitle = $courseTitle;

        // Check if course already exists
        if ($courseReg->checkCourseExist()) {
            $_SESSION['error'] = 'Course code or title already exists.';
            header('Location: ../../views/admin/course_reg.php');
            exit;
        }

        // Register the course
        if ($courseReg->registerCourse()) {
            $_SESSION['success'] = 'Course registered successfully.';
            header('Location: ../../views/admin/course_reg.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to register course.';
            header('Location: ../../views/admin/course_reg.php');
            exit;
        }
    } catch (Exception $e) {
        error_log("Registration Error: " . $e->getMessage());
        $_SESSION['error'] = 'Server error occurred.';
        header('Location: ../../views/admin/course_reg.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: ../../views/admin/course_reg.php');
    exit;
}
