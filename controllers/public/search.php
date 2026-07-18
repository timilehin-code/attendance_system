<?php
use Models\Public\search;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";
$searchModel = new search($pdo);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricNumber = trim($_POST['matric_no'] ?? '');
    if (empty($matricNumber)) {
        echo json_encode(['status' => 'error', 'message' => 'Matric number is required.']);
        exit;
    }

    $result = $searchModel->searchStudentByMatricNumber(strtoupper($matricNumber));
    if ($result) {
        session_start();
        $_SESSION['student_data'] = $result;
        header('Location: ../../views/public/attendance_record.php');
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No student found with the provided matric number.']);
    }
}