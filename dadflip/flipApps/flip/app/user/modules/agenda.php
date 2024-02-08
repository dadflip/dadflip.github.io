<?php
// agenda.php

// Inclure le fichier de connexion à la base de données
include 'db.php';

// Vérifier la session utilisateur
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Utilisateur non connecté'));
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer les événements associés à l'utilisateur depuis la base de données
try {
    $stmt = $pdo->prepare("SELECT id, title, start, end FROM events WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Erreur de base de données: ' . $e->getMessage()));
}
?>
