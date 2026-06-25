<?php
// includes/bootstrap-db.php

function bootstrapDatabase($pdo) {
    if (!$pdo) {
        return "Could not connect to the database. Check your environment variables.";
    }

    try {
        // Safe check execution for core tasks table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS household_tasks (
                id         SERIAL PRIMARY KEY,
                task       TEXT    NOT NULL,
                difficulty VARCHAR(10) NOT NULL DEFAULT 'Easy',
                points     INT NOT NULL DEFAULT 1,
                est_time   VARCHAR(30),
                notes      TEXT,
                done       BOOLEAN NOT NULL DEFAULT FALSE,
                sort_order INT NOT NULL DEFAULT 0,
                created_at TIMESTAMPTZ DEFAULT NOW()
            );
        ");

        // Universal app configurations table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS app_settings (
                setting_key   VARCHAR(50) PRIMARY KEY,
                setting_value VARCHAR(255) NOT NULL
            );
        ");

        // Seed default max points if missing
        $stmt = $pdo->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES ('cabinMaxPoints', '12') ON CONFLICT DO NOTHING;");
        $stmt->execute();

        return null; 
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}