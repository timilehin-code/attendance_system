<?php

use Models\Admin\Course_reg;
require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseCode  = strtoupper(trim($_POST['course_code'] ?? ''));
    $courseTitle = trim($_POST['course_title'] ?? '');

    // Basic validation
    if (empty($courseCode) || empty($courseTitle)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $courseReg = new Course_reg($pdo);

        $courseReg->courseCode  = $courseCode;
        $courseReg->courseTitle = $courseTitle;

        // Check if course already exists
        if ($courseReg->checkCourseExist()) {
            echo json_encode([
                'success' => false, 
                'message' => 'Course code or title already exists.'
            ]);
            exit;
        }

        // Register the course
        if ($courseReg->registerCourse()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Course registered successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to register course.'
            ]);
        }
    } catch (Exception $e) {
        error_log("Registration Error: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Server error occurred.'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}