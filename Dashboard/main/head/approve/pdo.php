<?php
    // Database connection variables
    $dbuser = "root";
    $dbpass = "";
    $host = "localhost";
    $db = "hrm_db";

    // Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
        // Create PDO connection and assign it to $pdo
        $pdo = new PDO($dsn, $dbuser, $dbpass);

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "✅ Connected successfully.";
    } catch (PDOException $e) {
        die("❌ Connection failed: " . $e->getMessage());
    }
?>
