<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\dadflip\assets\php');

// Inclure le fichier '_database_include.php'
include('_database_include.php');
include('_session_data_include.php');

// Récupérer les mots clés et titres de l'utilisateur (données)
$stmtUserData = $data->prepare("SELECT keywords, title FROM data WHERE user_id = ?");
$stmtUserData->bind_param("i", $userId);
$stmtUserData->execute();
$stmtUserData->bind_result($userKeywords, $userTitle);

$userData = [];
while ($stmtUserData->fetch()) {
    $userData[] = [
        'keywords' => explode(',', $userKeywords),
        'title' => $userTitle,
    ];
}
$stmtUserData->close();

//------------------------------------------------------------------------------------------------------------------------------

// Algorithme de recommandation
$recommendedTextsMap = array();

// Utiliser des alias pour simplifier l'accès aux colonnes dans le résultat
foreach ($userData as $userItem) {
    $userKeywords = $userItem['keywords'];
    $userTitle = $userItem['title'];

    // Rechercher dans la table 'texts' pour des correspondances avec les mots clés et le titre
    $stmtTexts = $conn->prepare("SELECT texts.*, media.media_type, media.media_url, users.username
        FROM texts
        LEFT JOIN users ON texts.user_id = users.user_id
        LEFT JOIN media ON texts.text_id = media.text_id
        WHERE LOWER(texts.title) LIKE ? 
        OR LOWER(texts.category) LIKE ? 
        OR LOWER(texts.content) LIKE ? 
        OR texts.keywords LIKE ?");

    $likeTerm = "%" . implode("%", array_map('strtolower', $userKeywords)) . "%";
    $stmtTexts->bind_param("ssss", $likeTerm, $likeTerm, $likeTerm, $likeTerm);
    $stmtTexts->execute();
    $result = $stmtTexts->get_result();

    while ($row = $result->fetch_assoc()) {
        // Utiliser le titre comme clé pour vérifier les doublons
        $textKey = $row['title'];

        if (!isset($recommendedTextsMap[$textKey])) {
            $recommendedTextsMap[$textKey] = $row;
        }
    }

    $stmtTexts->close();
}

// Convertir le tableau associatif en tableau numérique
$recommendedTexts = array_values($recommendedTextsMap);

// Mélanger le tableau de manière aléatoire
shuffle($recommendedTexts);

$conn->close();

// Renvoyer les résultats recommandés au format JSON
header('Content-Type: application/json');
echo json_encode($recommendedTexts);

//INFOS DE DEBOGAGE: (ATTENTION fait échouer la requête ajax !)
//echo "Après la récupération des données utilisateur.";
//echo json_encode($userData);
?>
