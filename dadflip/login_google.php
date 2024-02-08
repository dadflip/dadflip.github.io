<?php
// Inclure le fichier de configuration et les clés secrètes
include 'config.php'; // Assurez-vous de créer ce fichier et d'y définir vos clés secrètes

// Initialiser une session
session_start();

// Charger la bibliothèque Google API
require_once 'vendor/autoload.php'; // Assurez-vous d'installer la bibliothèque via Composer

// Créer un client OAuth
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

// Vérifier si l'utilisateur est déjà authentifié
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    // L'utilisateur est déjà connecté, rediriger vers la page principale
    header("Location: /dadflip/flipApps/flip/app/user/main.html");
    exit();
}

// Vérifier si le code d'authentification est présent dans l'URL
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Vérifier si l'authentification a réussi
    if (!$client->getAccessToken()) {
        // Échec de l'authentification
        echo 'Échec de l\'authentification avec Google.';
    } else {
        // Succès de l'authentification, récupérer les informations de l'utilisateur
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Vous pouvez maintenant utiliser $userInfo pour accéder aux informations de l'utilisateur

        // Exemple : Stocker les informations de l'utilisateur dans la session
        $_SESSION['user_id'] = $userInfo->id;

        // Rediriger vers la page principale
        header("Location: /dadflip/flipApps/flip/app/user/main.html");
        exit();
    }
} else {
    // L'utilisateur n'est pas encore authentifié, générer une URL d'authentification Google
    $authUrl = $client->createAuthUrl();
    header("Location: $authUrl");
    exit();
}
?>
