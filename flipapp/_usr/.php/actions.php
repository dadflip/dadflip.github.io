<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');

session_start();

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $data = isset($_POST['data']) ? $_POST['data'] : null;

    switch ($action) {
        case 'envoyer_commentaire':
            // Logique pour envoyer un commentaire
            envoyerCommentaire($data, $conn, $userId);
            break;

        case 'liker':
            // Logique pour liker
            liker($data, $conn, $userId);
            break;

        case 'disliker':
            // Logique pour disliker
            disliker($data, $conn, $userId);
            break;

        case 'mettre_en_favori':
            // Logique pour mettre en favori
            mettreEnFavori($data, $conn);
            break;

        case 'faire_un_don':
            // Logique pour faire un don
            faireUnDon($data, $conn);
            break;

        default:
            // Action non reconnue
            echo json_encode(['error' => 'Action non reconnue']);
    }
}

function envoyerCommentaire($data, $conn, $userId)
{
    if ($_POST['action'] == 'envoyer_commentaire') {
        $textId = $_POST['data']['textId'];
        $commentaire = $_POST['data']['commentaire'];

        var_dump($textId, $commentaire); // Vérifiez ces valeurs dans la sortie
        // Insérer le commentaire dans la base de données
        // ..

        $stmtInsertComment = $conn->prepare("INSERT INTO comments (user_id, text_id, content, timestamp) VALUES (?, ?, ?, NOW())");
        $stmtInsertComment->bind_param("iis", $_SESSION['user_id'], $textId, $commentaire);
        if ($stmtInsertComment->execute()) {
            // Répondre avec un succès ou des données mises à jour si nécessaire
            $stmtInsertComment->close();
            echo json_encode(['success' => true]);
            exit();
        } else {
            // En cas d'erreur, renvoyer un message d'erreur
            $stmtInsertComment->close();
            echo json_encode(['error' => $stmtInsertComment->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}


function ajouterLike($user_id, $text_id, $action)
{
    global $conn;

    // Vérifiez d'abord si l'utilisateur n'a pas déjà effectué cette action sur ce texte
    $stmtCheckAction = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND text_id = ?");
    $stmtCheckAction->bind_param("ii", $user_id, $text_id);
    $stmtCheckAction->execute();
    $resultCheckAction = $stmtCheckAction->get_result();

    if ($resultCheckAction->num_rows == 0) {
        // L'utilisateur n'a pas encore effectué cette action sur ce texte, procédez à l'insertion
        $stmtInsertAction = $conn->prepare("INSERT INTO likes (user_id, text_id, action) VALUES (?, ?, ?)");
        $stmtInsertAction->bind_param("iis", $user_id, $text_id, $action);
        $stmtInsertAction->execute();
    }
}

function liker($data, $conn, $userId)
{
    if (isset($data['textId'])) {
        $textId = $data['textId'];

        // Mettre à jour le compteur de likes dans la base de données
        $stmt = $conn->prepare("UPDATE texts SET likes = likes + 1 WHERE text_id = ?");
        $stmt->bind_param("i", $textId);
        $stmt->execute();
        $stmt->close();

        // Ajoutez le like à la table
        ajouterLike($userId, $textId, "like");

        // Vous pouvez renvoyer une réponse JSON si nécessaire
        echo json_encode(['success' => 'Like ajouté avec succès']);
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}


function disliker($data, $conn, $userId)
{
    if (isset($data['textId'])) {
        $textId = $data['textId'];

        // Mettre à jour le compteur de dislikes dans la base de données
        $stmt = $conn->prepare("UPDATE texts SET dislikes = dislikes + 1 WHERE text_id = ?");
        $stmt->bind_param("i", $textId);
        $stmt->execute();
        $stmt->close();

        ajouterLike($userId, $textId, "dislike");

        // Vous pouvez renvoyer une réponse JSON si nécessaire
        echo json_encode(['success' => 'Dislike ajouté avec succès']);
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}


function mettreEnFavori($data, $conn)
{
    // Logique pour mettre en favori
}

function faireUnDon($data, $conn)
{
    // Logique pour faire un don
}
