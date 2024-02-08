<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
//include('x_flipapp_include_vars.php');

session_start();

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'];


// Récupérer le terme de recherche depuis la barre de recherche
$searchTerm = isset($_GET['q']) ? strtolower($_GET['q']) : '';

// Initialiser un tableau pour les résultats de la recherche
$searchResults = [];

if (!empty($searchTerm)) {
    // Utiliser des alias pour simplifier l'accès aux colonnes dans le résultat
    $stmt = $conn->prepare("SELECT texts.*, media.media_type, media.media_url, users.username
                            FROM texts
                            LEFT JOIN users ON texts.user_id = users.user_id
                            LEFT JOIN media ON texts.text_id = media.text_id
                            WHERE LOWER(texts.title) LIKE ? 
                                OR LOWER(texts.category) LIKE ? 
                                OR LOWER(texts.content) LIKE ? 
                                OR texts.keywords LIKE ?");

    // Ajouter le caractère joker '%' au début et à la fin du terme de recherche
    $likeTerm = "%$searchTerm%";
    $stmt->bind_param("ssss", $likeTerm, $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();

    $result = $stmt->get_result();

    // Boucler à travers les résultats
    while ($row = $result->fetch_assoc()) {
        // Créer un tableau associatif pour chaque résultat
        $texte = [
            'title' => $row['title'],
            'category' => $row['category'],
            'content' => $row['content'],
            'keywords' => explode(',', $row['keywords']),
            'username' => $row['username'],
            'text_id' => $row['text_id'],
            'likes' => $row['likes'],
            'dislikes' => $row['dislikes'],
            'media_url' => $row['media_url'],
            'media_type' => $row['media_type']
        ];

        // Ajouter le résultat au tableau des résultats de recherche
        $searchResults[] = $texte;
    }
}

// Renvoyer les résultats au format JSON
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
