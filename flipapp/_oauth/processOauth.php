<?php
if(!isset($_SESSION['$user_id'])){
    header('Location: http://localhost/flipapp/_oauth/login.php');
}

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
//include('x_flipapp_include_vars.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Vérifier si l'utilisateur existe déjà dans la nouvelle base de données
    $stmtCheckExistingUser = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
    $stmtCheckExistingUser->bind_param("s", $email);
    $stmtCheckExistingUser->execute();
    $resultCheckExistingUser = $stmtCheckExistingUser->get_result();

    // Si l'utilisateur existe déjà, vérifier le mot de passe
    if ($resultCheckExistingUser->num_rows > 0) {
        $userData = $resultCheckExistingUser->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password, $userData['password'])) {
            // Mot de passe correct, définir les variables de session et rediriger
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];
            header('Location: http://localhost/flipapp/_usr/');
            exit();
        } else {
            // Mot de passe incorrect, rediriger vers la page d'authentification
            header('Location: login.php');
            exit();
        }
    }

    // L'utilisateur n'existe pas, continuer avec l'insertion
    $stmtCheckUser = $authConn->prepare("SELECT id, username, password FROM utilisateurs WHERE email = ?");
    $stmtCheckUser->bind_param("s", $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    // Si l'utilisateur est authentifié
    if ($resultCheckUser->num_rows > 0) {
        $userData = $resultCheckUser->fetch_assoc();
        $userId = $userData['id'];
        $hashedPassword = $userData['password'];

        // Insérer l'utilisateur dans la nouvelle base de données
        $stmtInsertUser = $conn->prepare("INSERT INTO users (user_id, username, email, password) VALUES (?, ?, ?, ?)");
        $stmtInsertUser->bind_param("isss", $userId, $userData['username'], $email, $hashedPassword);
        $stmtInsertUser->execute();

        // Fermer les connexions
        $stmtInsertUser->close();
        $conn->close();

        // Définir les variables de session
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $userData['username'];

        header('Location: app.php');
        exit();
    } else {
        // Utilisateur non trouvé, rediriger vers la page d'authentification
        header('Location: login.php');
        exit();
    }
}
?>
