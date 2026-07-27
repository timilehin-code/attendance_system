<?php
use Models\Public\search;
session_start();
require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";
$searchModel = new search($pdo);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricNumber = trim($_POST['matric_no'] ?? '');
    if (empty($matricNumber)) {
        $_SESSION['error'] = 'Matric number is required.';
        header('Location: ../../views/public/search.php');
        exit;
    }

    $result = $searchModel->searchStudentByMatricNumber(strtoupper($matricNumber));
    if ($result) {
        $_SESSION['student_data'] = $result;
        header('Location: ../../views/public/attendance_record.php');
        exit;
    } else {
        $_SESSION['error'] = 'No student found with the provided matric number.';
        header('Location: ../../views/public/search.php');
        exit;
    }
}