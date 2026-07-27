<?php
use Models\Admin\Student_reg;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName    = trim($_POST['first_name'] ?? '');
    $lastName     = trim($_POST['last_name'] ?? '');
    $matricNumber = strtoupper(trim($_POST['matric_number'] ?? ''));
    $department   = $_POST['department'] ?? '';

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($matricNumber) || empty($department)) {
       $_SESSION['error'] = 'All fields are required.';
       header('Location: ../../views/admin/student_reg.php');
        exit;
    }

    try {
        $studentReg = new Student_reg($pdo);

        $studentReg->firstName    = $firstName;
        $studentReg->lastName     = $lastName;
        $studentReg->matricNumber = $matricNumber;
        $studentReg->department   = (int)$department;

        // Check if matric number already exists
        if ($studentReg->checkMatricNumberExists()) {
           $_SESSION['error'] = 'Matriculation number already exists.';
           header('Location: ../../views/admin/student_reg.php');
            exit;
        }

        // Register the student
        if ($studentReg->registerStudent()) {
           $_SESSION['success'] = 'Student registered successfully.';
           header('Location: ../../views/admin/student_reg.php');
        } else {
           $_SESSION['error'] = 'Failed to register student.';
           header('Location: ../../views/admin/student_reg.php');
        }
    } catch (Exception $e) {
       $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
       header('Location: ../../views/admin/student_reg.php');
    }
} else {
   $_SESSION['error'] = 'Invalid request method.';
   header('Location: ../../views/admin/student_reg.php');
}