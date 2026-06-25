<?php
// includes/ajax-handlers.php

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
                    $diff = $_POST['difficulty'] ?? 'Medium';
                    $points = ($diff === 'Easy') ? 1 : (($diff === 'Hard') ? 3 : 2);

                    $stmt = $pdo->prepare("INSERT INTO household_tasks (task, difficulty, points, est_time, notes, sort_order) VALUES (?,?,?,?,?, 9999) RETURNING *");
                    $stmt->execute([trim($_POST['task']), $diff, $points, trim($_POST['est_time']), trim($_POST['notes'])]);
                    echo json_encode(['ok' => true, 'row' => $stmt->fetch()]);
                    break;

                case 'update':
                    $diff = $_POST['difficulty'] ?? 'Medium';
                    $points = ($diff === 'Easy') ? 1 : (($diff === 'Hard') ? 3 : 2);

                    $stmt = $pdo->prepare("UPDATE household_tasks SET task=?, difficulty=?, points=?, est_time=?, notes=? WHERE id=? RETURNING *");
                    $stmt->execute([trim($_POST['task']), $diff, $points, trim($_POST['est_time']), trim($_POST['notes']), (int)$_POST['id']]);
                    echo json_encode(['ok' => true, 'row' => $stmt->fetch()]);
                    break;

                case 'toggle':
                    $stmt = $pdo->prepare("UPDATE household_tasks SET done = NOT done WHERE id=? RETURNING done");
                    $stmt->execute([(int)$_POST['id']]);
                    $result = $stmt->fetch();
                    echo json_encode(['ok' => true, 'done' => $result['done']]);
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

                case 'update_order':
                    $ids = explode(',', $_POST['ids'] ?? '');
                    $stmt = $pdo->prepare("UPDATE household_tasks SET sort_order = ? WHERE id = ?");
                    foreach ($ids as $index => $id) {
                        if (!empty($id)) {
                            $stmt->execute([(int)$index, (int)$id]);
                        }
                    }
                    echo json_encode(['ok' => true]);
                    break;

                case 'save_settings':
                    $points = isset($_POST['points']) ? (int)$_POST['points'] : 12;
                    if ($points < 1) $points = 12;

                    $stmt = $pdo->prepare("
                        INSERT INTO app_settings (setting_key, setting_value) 
                        VALUES ('cabinMaxPoints', :points) 
                        ON CONFLICT (setting_key) 
                        DO UPDATE SET setting_value = EXCLUDED.setting_value
                    ");
                    $ok = $stmt->execute(['points' => $points]);
                    echo json_encode(['ok' => $ok]);
                    break;

                default:
                    echo json_encode(['ok' => false, 'error' => 'Unknown action']);
            }
        } catch (PDOException $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}