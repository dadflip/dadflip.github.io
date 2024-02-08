<?php
// Fichier db.php

$db_host = "localhost"; // Hôte de la base de données
$db_name = "flip-apps"; // Nom de la base de données
$db_user = "flip-apps"; // Nom d'utilisateur de la base de données
$db_password = "dcalc701Dk2021lh!M"; // Mot de passe de la base de données

// Créer une connexion à la base de données
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Assurer que $conn est accessible dans d'autres fichiers
global $conn;
?>
