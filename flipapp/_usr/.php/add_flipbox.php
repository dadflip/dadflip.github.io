<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');

session_start();

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'];


// Initialiser les variables
$title = $content = $category = $keywords = '';
$imageFilePath = null;
$videoFilePath = null;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $keywords = $_POST['keywords'];

    // Créer un dossier pour l'utilisateur s'il n'existe pas
    $userFolder = 'media' . $userId;
    if (!file_exists($userFolder)) {
        mkdir($userFolder, 0777, true);
    }

    // Gérer le téléchargement de l'image
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $userId . '_img' . uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFilePath = $userFolder . '/' . $imageFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imageFilePath);
    }else if ($_FILES['video']['error'] === UPLOAD_ERR_OK) { // Gérer le téléchargement de la vidéo
        $videoFileName = $userId . '_vid' . uniqid() . '.' . pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $videoFilePath = $userFolder . '/' . $videoFileName;
        move_uploaded_file($_FILES['video']['tmp_name'], $videoFilePath);
    }else{
        // Récupérer les URLs des médias depuis les paramètres de l'URL
        $imageFilePath = isset($_GET['image_url']) ? $_GET['image_url'] : '';
        $videoFilePath = isset($_GET['video_url']) ? $_GET['video_url'] : '';
    }

    // Ajouter le sujet à la base de données dans la table 'texts'
    $stmtTexts = $conn->prepare("INSERT INTO texts (user_id, title, content, category, keywords, image_url, video_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtTexts->bind_param("issssss", $userId, $title, $content, $category, $keywords, $imageFilePath, $videoFilePath);
    $stmtTexts->execute();

    // Récupérer l'ID du texte nouvellement inséré
    $textId = $stmtTexts->insert_id;

    // Fermer la première déclaration
    $stmtTexts->close();

    // Ajouter des informations dans la table 'media'
    if ($textId > 0) {
        $mediaType = ''; // Remplacez cela par le type de média approprié ('image' ou 'video')
        $mediaUrl = ''; // Remplacez cela par l'URL du média approprié

        if (!empty($imageFilePath)) {
            $mediaType = 'image';
            $mediaUrl = '.php/'.$imageFilePath;
        } elseif (!empty($videoFilePath)) {
            $mediaType = 'video';
            $mediaUrl = '.php/'.$videoFilePath;
        }
        
        $createdAt = date('Y-m-d H:i:s');

        $stmtMedia = $conn->prepare("INSERT INTO media (text_id, media_url, media_type, created_at) VALUES (?, ?, ?, ?)");
        $stmtMedia->bind_param("isss", $textId, $mediaUrl, $mediaType, $createdAt);
        $stmtMedia->execute();

        // Fermer la deuxième déclaration
        $stmtMedia->close();
    }

    // Rediriger vers l'interface principale après l'ajout
    header("Location: http://localhost/flipapp/_usr/app.php");
    exit();
}
?>
