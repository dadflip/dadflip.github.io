<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');


// Dossier de destination pour les vidéos traitées
$destinationFolder = 'media/' . $_SESSION['user_id'];

// Créer le dossier s'il n'existe pas
if (!file_exists($destinationFolder)) {
    mkdir($destinationFolder, 0777, true);
}

// Gérer le fichier vidéo
if (isset($_FILES['videoCapture']) && $_FILES['videoCapture']['error'] === UPLOAD_ERR_OK) {
    $videoFile = $_FILES['videoCapture'];

    // Générer un nom unique pour le fichier vidéo
    $videoFileName = uniqid('vid_') . '.' . pathinfo($videoFile['name'], PATHINFO_EXTENSION);

    // Chemin complet du fichier dans le dossier de destination
    $videoFilePath = $destinationFolder . '/' . $videoFileName;

    // Déplacer le fichier vers le dossier de destination
    move_uploaded_file($videoFile['tmp_name'], $videoFilePath);

    // Construire l'URL complète du fichier traité
    $processedVideoUrl = $videoFilePath; // Vous pouvez ajuster cela en fonction de votre structure d'URL

    // Retourner l'URL du fichier traité en format JSON
    echo json_encode(['url' => $processedVideoUrl]);
} else {
    // Si une erreur s'est produite lors du téléchargement
    echo json_encode(['error' => 'Erreur lors du téléchargement de la vidéo.']);
}
?>
