<?php
// removeTask.php

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskId'])) {
    $taskId = $_POST['taskId'];

    // Supprimez la tâche de la base de données
    try {
        $stmt = $conn->prepare("DELETE FROM todo_tasks WHERE id = ?");
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        echo json_encode(array('success' => true));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Erreur de base de données: ' . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Requête incorrecte'));
}
?>
