<?php
// db.php

function connectDatabase() {
    $host = getenv('DB_HOST') ?: 'postgres';
    $name = getenv('DB_NAME') ?: 'postgres';
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    try {
        $dsn = "pgsql:host={$host};dbname={$name}";
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // If it fails, this will help you diagnose credential issues immediately
        die("Database connection failed: " . $e->getMessage());
    }
}