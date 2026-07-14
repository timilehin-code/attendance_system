<?php
use Models\Admin\Student_reg;

require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName    = trim($_POST['first_name'] ?? '');
    $lastName     = trim($_POST['last_name'] ?? '');
    $matricNumber = strtoupper(trim($_POST['matric_number'] ?? ''));
    $department   = $_POST['department'] ?? '';

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($matricNumber) || empty($department)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
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
            echo json_encode([
                'success' => false, 
                'message' => 'Matric number already exists.'
            ]);
            exit;
        }

        // Register the student
        if ($studentReg->registerStudent()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Student registered successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to register student.'
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