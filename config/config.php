<?php

// Google Apps Script Configuration
define(
    'GOOGLE_APPS_SCRIPT_URL',
    'https://script.google.com/macros/s/AKfycbzxfvJApTCY7pnIpKfDxigjgolbVZxKB-VxQIbK8gqgoqOzhkzbOrRVPJzNK8zVbSvJNA/exec'
);

// Shared Secret
define(
    'SHARED_SECRET',
    'EnrollmentProject@2026#SecureKey'
);

// Notification Email
define(
    'NOTIFICATION_EMAIL',
    'info@theiotacademy.co'
);

// Auto-detect Localhost vs Live Server
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = in_array($httpHost, ['localhost', '127.0.0.1'])
           || (strpos($httpHost, 'localhost:') === 0)
           || (php_sapi_name() === 'cli');

if ($isLocal) {
    // LOCALHOST (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u733794648_main');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // LIVE SERVER (Hostinger / cPanel)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u733794648_main');
    define('DB_USER', 'u733794648_main');
    define('DB_PASS', '7Ec@C*TGxpx9');
}

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

} catch (PDOException $e) {
    error_log('Database connection error in backend/config/config.php: ' . $e->getMessage());
    $connected = false;
    foreach (['theiotacademy', 'enrollment_db'] as $fallbackDb) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . $fallbackDb . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            $connected = true;
            break;
        } catch (PDOException $ex) {
            continue;
        }
    }

    if (!$connected) {
        die('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}
