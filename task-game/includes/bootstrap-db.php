<?php
// includes/bootstrap-db.php

function bootstrapDatabase($pdo) {
    if (!$pdo) {
        return "Could not connect to the database. Check your environment variables.";
    }

    try {
        // Safe check execution - will not overwrite or alter your active data records
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
        return null; 
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}