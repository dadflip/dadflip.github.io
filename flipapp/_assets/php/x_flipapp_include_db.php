<?php
// Fichier config.php

$db_host = "localhost"; // Hôte de la base de données
$db_name = "flipapp"; // Nom de la base de données
$db_user = "flip"; // Nom d'utilisateur de la base de données
$db_password = "Dk2021lh!M1083"; // Mot de passe de la base de données

// Créer une connexion à la base de données
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Connexion à la base de données d'authentification
$authDbHost = 'localhost';
$authDbName = 'flip-apps';
$authDbUser = 'flip-apps';
$authDbPassword = 'dcalc701Dk2021lh!M';


$authConn = new mysqli($authDbHost, $authDbUser, $authDbPassword, $authDbName);

// Connexion à la base de données
$servername = "localhost";
$username = "dataAccess";
$password = "cGOz2u)qyadx7gYF";
$dbname = "algo";

$data = new mysqli($servername, $username, $password, $dbname);


// Vérifier la connexion
if ($data->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérifier la connexion à la base de données d'authentification
if ($authConn->connect_error) {
    die("Échec de la connexion à la base de données d'authentification : " . $authConn->connect_error);
}

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Assurer que $conn est accessible dans d'autres fichiers
global $conn;
global $authConn;
global $data;
?>
