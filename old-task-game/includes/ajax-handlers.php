<?php
// ajax-handlers.php

function handleAjaxActions($pdo) {
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_ajax) {
        header('Content-Type: application/json');
        if (!$pdo) { 
            echo json_encode(['ok' => false, 'error' => 'No DB connection']); 
            exit; 
        }

        $action = $_POST['action'] ?? '';
        try {
            switch ($action) {
                case 'add':
                    $stmt = $pdo->prepare("INSERT INTO household_tasks (task, difficulty, est_time, notes) VALUES (?,?,?,?) RETURNING *");
                    $stmt->execute([trim($_POST['task']), $_POST['difficulty'], trim($_POST['est_time']), trim($_POST['notes'])]);
                    echo json_encode(['ok' => true, 'row' => $stmt->fetch()]);
                    break;

                case 'update':
                    $stmt = $pdo->prepare("UPDATE household_tasks SET task=?, difficulty=?, est_time=?, notes=? WHERE id=? RETURNING *");
                    $stmt->execute([trim($_POST['task']), $_POST['difficulty'], trim($_POST['est_time']), trim($_POST['notes']), (int)$_POST['id']]);
                    echo json_encode(['ok' => true, 'row' => $stmt->fetch()]);
                    break;

                case 'toggle':
                    $stmt = $pdo->prepare("UPDATE household_tasks SET done = NOT done WHERE id=? RETURNING done");
                    $stmt->execute([(int)$_POST['id']]);
                    echo json_encode(['ok' => true, 'done' => $stmt->fetch()['done']]);
                    break;

                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM household_tasks WHERE id=?");
                    $stmt->execute([(int)$_POST['id']]);
                    echo json_encode(['ok' => true]);
                    break;

                case 'reset':
                    $pdo->exec("UPDATE household_tasks SET done = FALSE");
                    echo json_encode(['ok' => true]);
                    break;

                default:
                    echo json_encode(['ok' => false, 'error' => 'Unknown action']);
            }
        } catch (PDOException $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit; // Important: terminates execution so remaining HTML is not sent with AJAX response
    }
}