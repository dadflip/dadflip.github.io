<?php
include 'db.php';

// Configurations de la base de données
$host = $db_host; // Nom d'hôte de votre base de données
$dbname = $db_name; // Nom de votre base de données
$username = $db_user; // Nom d'utilisateur de votre base de données
$password = $db_password; // Le mot de passe de votre base de données

try {
    // Vérifier si le jeton de vérification est présent dans l'URL
    if (isset($_GET['token'])) {
        $verificationToken = $_GET['token'];

        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si le jeton de vérification existe dans la base de données
        $stmt_check_token = $pdo->prepare("SELECT * FROM utilisateurs WHERE verification_token = :token");
        $stmt_check_token->bindParam(':token', $verificationToken);
        $stmt_check_token->execute();
        $user = $stmt_check_token->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Mettre à jour la base de données pour marquer l'utilisateur comme vérifié
            $stmt_update = $pdo->prepare("UPDATE utilisateurs SET is_verified = 1, verification_token = NULL WHERE id = :id");
            $stmt_update->bindParam(':id', $user['id']);
            $stmt_update->execute();

            echo "Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.";
        } else {
            echo "Le jeton de vérification est invalide.";
        }
    } else {
        echo "Le jeton de vérification est manquant.";
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
<!-- Le reste de votre code HTML reste inchangé -->
