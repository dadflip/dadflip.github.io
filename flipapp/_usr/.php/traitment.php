<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');

session_start();

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'];

// Récupérer les données de la requête Ajax
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;
$title = isset($_POST['title']) ? $_POST['title'] : null;
$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : null;
$category = isset($_POST['category']) ? $_POST['category'] : null;
$currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;
$currentTime = isset($_POST['currentTime']) ? $_POST['currentTime'] : null;
$browserInfo = isset($_POST['browserInfo']) ? $_POST['browserInfo'] : null;

// Extraire les champs de browser_info
$browserName = $browserInfo['browser_name'];
$browserVersion = $browserInfo['browser_version'];
$userAgent = $browserInfo['user_agent'];
// Ajoutez d'autres champs en fonction de votre structure

//$stmt = $data->prepare("SELECT COUNT(*) FROM data WHERE (title = ? OR keywords = ?) AND user_id = ?");
//$stmt->bind_param("ssi", $title, $keywords, $userId);

// Vérifier si le titre ou les mots clés existent déjà
$stmt = $data->prepare("SELECT keywords, title, category, u_current_date FROM data WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($existingKeywords, $existingTitle, $existingCategory, $existingCurrentDate);
$stmt->fetch();
$stmt->close();

if ($existingKeywords == $keywords && $existingTitle == $title && $existingCategory == $category) {
    // Si le titre ou les mots clés existent déjà, vous pouvez choisir de mettre à jour les informations ici
    $stmtUpdate = $data->prepare("UPDATE data SET u_current_date = ? WHERE user_id = ?");
    $stmtUpdate->bind_param("si", $currentDate, $userId);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Répondre à la requête Ajax
    echo json_encode(['success' => true, 'message' => 'Les informations existent déjà et ont été mises à jour.']);
} else {
    // Si le titre ou les mots clés n'existent pas, insérer les nouvelles informations
    $stmtInsert = $data->prepare("INSERT INTO data (user_id, title, keywords, category, u_current_date, u_current_time, browser_name, browser_version, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("issssssss", $userId, $title, $keywords, $category, $currentDate, $currentTime, $browserName, $browserVersion, $userAgent);
    $stmtInsert->execute();

    // Vérifier le succès de l'opération
    if ($stmtInsert->affected_rows > 0) {
        // Répondre à la requête Ajax
        echo json_encode(['success' => true, 'message' => 'Les informations ont été insérées avec succès.']);
    } else {
        // En cas d'échec
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion en base de données']);
    }

    // Fermer la connexion et la requête
    $stmtInsert->close();
}

$data->close();
?>
