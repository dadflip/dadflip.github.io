<?php
// addTask.php

include 'db.php';
session_start(); // Démarrer la session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskName'])) {
    $taskName = $_POST['taskName'];

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Utilisateur non connecté'));
        exit();
    }

    $userId = $_SESSION['user_id'];

    // Ajoutez la tâche à la base de données
    try {
        $stmt = $conn->prepare("INSERT INTO todo_tasks (user_id, task_name) VALUES (?, ?)");
        $stmt->bind_param('is', $userId, $taskName);
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
