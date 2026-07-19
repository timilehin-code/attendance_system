<?php


use Models\Public\Attendance;
session_start();
$classid = $_SESSION['class_id'] ?? null;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

$attendance = new Attendance($pdo);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $matricNumber = trim($_POST['matric_number'] ?? '');
    $courseId     = trim($_POST['course_id'] ?? '');

    if (empty($matricNumber) || empty($courseId)) {
        echo json_encode(['status' => 'error', 'message' => 'Matric number and course are required.']);
        exit;
    }

    try {
        $attendance->matricNumber = strtoupper($matricNumber);

        if (!$attendance->checkMatricNumberExists()) {
            echo json_encode(['status' => 'error', 'message' => 'Matriculation number does not exist.']);
            exit;
        }

        $studentId = $attendance->getStudentIdByMatricNumber();

        if (!$studentId) {
            echo json_encode(['status' => 'error', 'message' => 'Student ID not found.']);
            exit;
        }

        $attendance->studentId = $studentId;
        $attendance->courseId  = $courseId;
        $attendance->classId   = $classid;

        // Try to record attendance
        $result = $attendance->recordAttendance();

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to record attendance - Check server logs'
            ]);
        }
    } catch (Exception $e) {
    $errorMsg = $e->getMessage();
    
    // Show user-friendly messages
    if (strpos($errorMsg, 'already marked attendance') !== false) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'You have already marked attendance for this course today.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => $errorMsg
        ]);
    }
}
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
