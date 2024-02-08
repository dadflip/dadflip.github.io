<?php
// Inclure le fichier de configuration de la base de données
require_once 'db.php';

// Récupérer les données postées
$moduleId = $_POST['moduleId'];
$width = intval($_POST['width']);
$height = intval($_POST['height']);
$centerX = floatval($_POST['centerX']);
$centerY = floatval($_POST['centerY']);


// Créer une connexion à la base de données
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Mettre à jour les dimensions et le centre dans la base de données
$stmt = $conn->prepare("UPDATE modules_utilisateur SET width = ?, height = ?, centerX = ?, centerY = ? WHERE id = ?");
$stmt->bind_param('iiddi', $width, $height, $centerX, $centerY, $moduleId);

if ($stmt->execute()) {
    echo 'Dimensions et centre enregistrés avec succès. ' . $width . "--" . $height . "--" . $centerX . "--" . $centerY;
} else {
    echo 'Erreur lors de l\'enregistrement des dimensions et du centre.';
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
