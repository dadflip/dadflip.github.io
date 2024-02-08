<?php
// completeTask.php

session_start(); // Démarrer la session
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskId']) && isset($_POST['completed'])) {
    $taskId = $_POST['taskId'];
    $completed = $_POST['completed'] ? 1 : 0;

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Utilisateur non connecté'));
        exit();
    }

    $userId = $_SESSION['user_id'];

    // Mettre à jour le statut de la tâche dans la base de données
    try {
        $stmt = $conn->prepare("UPDATE todo_tasks SET completed = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param('iii', $completed, $taskId, $userId);
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
