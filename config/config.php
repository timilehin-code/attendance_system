<?php
// config/config.php

use Models\Config\Config;
require_once __DIR__ . '/../vendor/autoload.php';

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception("The .env file does not exist at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        if (preg_match('/^\s*([\w.-]+)\s*=\s*(.*)?\s*$/', $line, $matches)) {
            $key = $matches[1];
            $value = trim($matches[2] ?? '', '"\'');
            $_ENV[$key] = $value;
        }
    }
}

try {
    loadEnv(__DIR__ . '/../.env');
} catch (Exception $e) {
    error_log("Error loading .env: " . $e->getMessage());
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$dbName = $_ENV['DB_NAME'] ?? 'attendance_mangement';

try {
    $config = new Config($host, $user, $password, $dbName);
    $pdo = $config->getConn();   // Get the connection

    return $pdo; 

} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed.");
}