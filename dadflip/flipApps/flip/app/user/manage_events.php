<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Vérifier si l'action est "addEvent"
if (isset($_POST['action']) && $_POST['action'] === 'addEvent') {
    // Récupérer le titre de l'événement
    $eventTitle = $_POST['eventTitle'];

    // Enregistrez l'événement dans la base de données (à compléter selon votre structure de base de données)
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO events (user_id, title) VALUES (:user_id, :title)");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':title', $eventTitle);
    $stmt->execute();

    // Rediriger vers le tableau de bord après l'ajout de l'événement
    header("Location: dashboard.php");
    exit();
}
?>
