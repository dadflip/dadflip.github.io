<?php
// getTasks.php

session_start(); // Démarrer la session
include 'db.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("SELECT id, task_name, created_at, completed FROM todo_tasks WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        http_response_code(500);
        die(json_encode(array('error' => 'Erreur de base de données: ' . $e->getMessage())));
    }
} else {
    http_response_code(401);
    die(json_encode(array('error' => 'Utilisateur non connecté')));
}

// Construire le HTML et les données JSON
$data = array();
$html = '';

foreach ($tasks as $task) {
    $html .= '<li class="task-item" id="' . $task['id'] . '">';
    $html .= '<div class="task-details">';
    $html .= '<span class="task-name">' . $task['task_name'] . '</span><br>';
    $html .= '<span class="task-date">' . $task['created_at'] . '</span><br>';
    $html .= '<input type="checkbox" ' . ($task['completed'] ? 'checked' : '') . ' onchange="completeTask(' . $task['id'] . ')">';
    $html .= '</div>';
    $html .= '<button onclick="removeTask(' . $task['id'] . ')">Supprimer</button>';
    $html .= '</li>';

    // Ajouter les données JSON associées à chaque tâche
    $data[] = array(
        'id' => $task['id'],
        'completed' => $task['completed'],
    );
}

echo $html."---";
// Renvoyer le HTML et les données JSON
json_encode(array('html' => $html, 'data' => $data));
?>
