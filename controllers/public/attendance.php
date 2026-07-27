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

    $_SESSION['error'] = 'Matriculation number and course ID are required.';
    header('Location: ../../views/public/index.php');
    exit;
    }

    try {
        $attendance->matricNumber = strtoupper($matricNumber);

        if (!$attendance->checkMatricNumberExists()) {
           $_SESSION['error'] = 'Matriculation number does not exist.';
           header('Location: ../../views/public/index.php');
            exit;
        }

        $studentId = $attendance->getStudentIdByMatricNumber();

        if (!$studentId) {
            $_SESSION['error'] = 'Student ID not found for the provided matriculation number.';
            header('Location: ../../views/public/index.php');
            exit;
        }

        $attendance->studentId = $studentId;
        $attendance->courseId  = $courseId;
        $attendance->classId   = $classid;

        // Try to record attendance
        $result = $attendance->recordAttendance();

        if ($result) {
           $_SESSION['success'] = 'Attendance recorded successfully.';
           header('Location: ../../views/public/index.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to record attendance. Please try again.';
            header('Location: ../../views/public/index.php');
            exit;
        }
    } catch (Exception $e) {
    $errorMsg = $e->getMessage();
    
    // Show user-friendly messages
    if (strpos($errorMsg, 'already marked attendance') !== false) {
       $_SESSION['error'] = 'You have already marked your attendance for this class.';
       header('Location: ../../views/public/index.php');
       exit;
    } else {
         $_SESSION['error'] = 'An unexpected error occurred. Please try again later.';
         header('Location: ../../views/public/index.php');
         exit;
    }
}
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: ../../views/public/index.php');
    exit;
}
