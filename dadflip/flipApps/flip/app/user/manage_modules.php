<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Vérifier l'action à effectuer (ajouter ou retirer)
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Connexion à la base de données
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Traitement de l'ajout d'un module
        if ($action === 'add' && isset($_POST['addModule'])) {
            $moduleName = $_POST['addModule'];

            // Vérifier si le module n'existe pas déjà pour l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM modules_utilisateur WHERE user_id = :user_id AND module_name = :module_name");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':module_name', $moduleName);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                // Ajouter le module pour l'utilisateur avec une taille par défaut (à ajuster selon vos besoins width, height)
                $stmt = $pdo->prepare("INSERT INTO modules_utilisateur (user_id, module_name, width, height) VALUES (:user_id, :module_name, 400, 400)");
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':module_name', $moduleName);
                $stmt->execute();

                // Insérer le contenu initial du module (exemple avec 'agenda')
                if ($moduleName === 'agenda') {
                    // Insérez ici le code pour ajouter le contenu initial de l'agenda dans la base de données
                    // Vous pouvez avoir une table séparée pour stocker les événements de l'agenda
                }
            }
        }

        // Traitement du retrait d'un module
        elseif ($action === 'remove' && isset($_POST['removeModule'])) {
            $moduleId = $_POST['removeModule'];

            // Retirer le module pour l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM modules_utilisateur WHERE id = :module_id AND user_id = :user_id");
            $stmt->bindParam(':module_id', $moduleId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
        exit();
    }
}

// Rediriger vers le tableau de bord après les modifications
header("Location: dashboard.php");
exit();
?>
