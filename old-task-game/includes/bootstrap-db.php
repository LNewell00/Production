<?php
// bootstrap-db.php

function bootstrapDatabase($pdo) {
    if (!$pdo) {
        return "Could not connect to the database. Check your environment variables.";
    }

    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS household_tasks (
                id         SERIAL PRIMARY KEY,
                task       TEXT    NOT NULL,
                difficulty VARCHAR(10) NOT NULL DEFAULT 'Easy',
                est_time   VARCHAR(30),
                notes      TEXT,
                done       BOOLEAN NOT NULL DEFAULT FALSE,
                created_at TIMESTAMPTZ DEFAULT NOW()
            );
        ");

        $count = $pdo->query("SELECT COUNT(*) FROM household_tasks")->fetchColumn();
        if ($count == 0) {
            $seeds = [
                ['Clean the Kitchen',           'Medium', '30–45 min',   'Wipe counters, clean stovetop, sink & appliances'],
                ['Laundry – Clothes',            'Medium', '60–90 min',   'Wash, dry, and fold/hang a load of clothes'],
                ['Laundry – Towels/Linens',      'Easy',   '45–60 min',   'Wash, dry, and fold towels or bed linens'],
                ['Meal Prep',                    'Hard',   '60–120 min',  'Prep ingredients or cook meals for the week'],
                ['Vacuum (Main Areas)',          'Medium', '20–30 min',   'Vacuum living room, hallway, and bedrooms'],
                ['Dust the House',               'Easy',   '20–30 min',   'Dust surfaces, shelves, fans, and baseboards'],
                ['Clean Bathrooms',              'Medium', '30–45 min',   'Scrub toilet, sink, tub/shower, and wipe mirrors'],
                ['Mop Floors',                   'Medium', '20–30 min',   'Mop kitchen, bathrooms, or hard floors'],
                ['Tidy Living Room',             'Easy',   '15–20 min',   'Declutter, fluff pillows, straighten decor'],
                ['Take Out Trash & Recycling',   'Easy',   '10–15 min',   'Empty all bins and take out to curb or dumpster'],
                ['Clean Out Fridge',             'Medium', '20–30 min',   'Toss old food, wipe shelves, organize contents'],
                ['Organize a Drawer/Closet',     'Hard',   '30–60 min',   'Declutter and reorganize one area of the home'],
                ['Wipe Down Windows & Mirrors',  'Easy',   '15–20 min',   'Clean glass surfaces throughout the home'],
                ['Water Plants / Outdoor Tidy',  'Easy',   '15–25 min',   'Water houseplants or tidy the porch/yard'],
                ['Change Bed Sheets',            'Easy',   '15–20 min',   'Strip and remake beds with fresh linens'],
                ['Grocery Run / Order',          'Hard',   '45–90 min',   'Shop for groceries in-store or place an order'],
            ];
            $stmt = $pdo->prepare("INSERT INTO household_tasks (task, difficulty, est_time, notes) VALUES (?,?,?,?)");
            foreach ($seeds as $s) $stmt->execute($s);
        }
        return null; // No errors!
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}