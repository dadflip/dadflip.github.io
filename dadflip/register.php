<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
// Configurations de la base de données
$host = $db_host; // Nom d'hôte de votre base de données
$dbname = $db_name; // Nom de votre base de données
$username = $db_user; // Nom d'utilisateur de votre base de données
$password = $db_password; // Le mot de passe de votre base de données

// Constantes pour les messages d'erreur
define('ERROR_PASSWORD', "Le mot de passe doit contenir au moins 8 caractères, incluant des chiffres, des lettres et des caractères spéciaux.");
define('ERROR_PASSWORD_MATCH', "Les mots de passe ne correspondent pas.");
define('ERROR_EMAIL_EXIST', "Cette adresse e-mail est déjà utilisée.");
define('ERROR_INVALID_EMAIL', "L'adresse e-mail n'est pas valide.");
define('ERROR_USERNAME_EXIST', "Ce nom d'utilisateur est déjà pris.");
define('ERROR_SEND_EMAIL', "Erreur lors de l'envoi de l'e-mail de vérification.");
define('SUCCESS_SEND_EMAIL', "E-mail de vérification envoyé avec succès.");

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si les variables POST sont définies
    if (isset($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['confirm-password'])) {
        // Récupération des données du formulaire
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        // Validation du mot de passe
        if (!isValidPassword($password)) {
            echo ERROR_PASSWORD;
            exit();
        }

        // Vérifier si les mots de passe correspondent
        if ($password !== $confirmPassword) {
            echo ERROR_PASSWORD_MATCH;
            exit();
        }

        // Vérifier si l'adresse e-mail existe déjà
        $stmt_email = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
        $stmt_email->bindParam(':email', $email);
        $stmt_email->execute();
        if ($stmt_email->fetchColumn() > 0) {
            echo ERROR_EMAIL_EXIST;
            exit();
        }

        // Validation de l'adresse e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo ERROR_INVALID_EMAIL;
            exit();
        }

        // Vérifier si le nom d'utilisateur existe déjà
        $stmt_username = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE username = :username");
        $stmt_username->bindParam(':username', $username);
        $stmt_username->execute();
        if ($stmt_username->fetchColumn() > 0) {
            echo ERROR_USERNAME_EXIST;
            exit();
        }

        // Générer un identifiant unique random à 8 chiffres
        do {
            $user_id = mt_rand(10000000, 99999999);
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id = :id");
            $stmt_check->bindParam(':id', $user_id);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
        } while ($count > 0);

        // Générer un jeton de vérification unique
        $verificationToken = bin2hex(random_bytes(32));

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Requête d'insertion dans la table 'utilisateurs'
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (id, firstname, lastname, username, email, password, verification_token, is_verified) VALUES (:id, :firstname, :lastname, :username, :email, :password, :verification_token, 0)");
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verification_token', $verificationToken);
        $stmt->execute();

        // Envoyer l'e-mail de vérification
        $to = $email;
        $headers = 'From: webmaster@localhost';
        $subject = "Vérification de votre compte";
        $message = "Bienvenue sur notre site! Cliquez sur le lien suivant pour vérifier votre compte:\n\n";
        $message .= "http://localhost/dadflip/verify.php?token=$verificationToken";

        // Utilisez la fonction mail() ou un service tiers pour envoyer l'e-mail.
        // Assurez-vous que votre serveur permet l'envoi d'e-mails.
        //TEST : A CHANGER LORS DU DEPLOIEMENT:
        if (mail($to, $subject, $message, $headers)) {
            echo SUCCESS_SEND_EMAIL;
        } else {
            echo ERROR_SEND_EMAIL;
        }

        // Redirection vers la page de connexion après l'inscription réussie
        header("Location: login.php?registration=success");
        exit();
    } else {
        // Gérer le cas où certaines variables ne sont pas définies
        echo "Toutes les variables POST ne sont pas définies.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

// Fonction de validation du mot de passe
function isValidPassword($password) {
    return strlen($password) >= 8 && preg_match("~[0-9]+~", $password) && preg_match("~[a-zA-Z]+~", $password) && preg_match("~[!@#$%^&*(),.?\":{}|<>]+~", $password);
}

?>
