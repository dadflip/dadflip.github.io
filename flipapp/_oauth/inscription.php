<?php

// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');

session_start();


function generateUserId() {
    // Générer un ID à 10 chiffres
    return mt_rand(10000000, 99999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;

    // Vérifier si l'utilisateur existe déjà dans la base d'authentification
    $stmtCheckUser = $authConn->prepare("SELECT id, username, password FROM utilisateurs WHERE email = ?");
    $stmtCheckUser->bind_param("s", $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    // Si l'utilisateur existe, rediriger vers la page d'authentification
    if ($resultCheckUser->num_rows > 0) {
        header('Location: authentification.php');
        exit();
    } else {
        // Générer un ID pour l'utilisateur
        $userId = generateUserId();

        // Inscrire le nouvel utilisateur dans la base de données actuelle
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Échec de la connexion à la base de données : " . $conn->connect_error);
        }

        // Hasher le mot de passe avant de l'enregistrer
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Récupérer l'état de la case à cocher (conditions acceptées)
        $acceptedConditions = isset($_POST['accept_conditions']) ? 1 : 0;

        // Insérer le nouvel utilisateur dans la base de données actuelle avec l'ID généré et l'état de la case à cocher
        $stmtInsertUser = $conn->prepare("INSERT INTO users (user_id, username, email, password, accepted_conditions) VALUES (?, ?, ?, ?, ?)");
        $stmtInsertUser->bind_param("isssi", $userId, $username, $email, $hashedPassword, $acceptedConditions);
        $stmtInsertUser->execute();

        // Fermer les connexions
        $stmtInsertUser->close();
        $conn->close();

        // Démarrer la session et définir les variables de session
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $userId;

        // Rediriger vers la page principale après l'inscription
        header('Location: http://localhost/flipapp/_usr/');
        exit();
    }
}
?>


<!-- ... (votre code HTML) ... -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/flipapp/_assets/css/styles.css">
    <link rel="stylesheet" href="http://localhost/flipapp/_assets/css/inscription.css">
</head>

<body>
    <header>
        <h1 style="font-size: 2em;">S'INSCRIRE</h1>
    </header>

    <div class="container">
        <div class="form-container">
            <h2 class="form-heading">Inscription</h2>

            <form action="#" method="post">
                <label for="email">Email</label>
                <input type="text" placeholder="Entrez votre email" name="email" required>

                <label for="username">Nom d'utilisateur</label>
                <input type="text" placeholder="Entrez votre nom d'utilisateur" name="username" required>

                <label for="password">Mot de passe</label>
                <input type="password" placeholder="Entrez votre mot de passe" name="password" required>

                <button type="button" onclick="toggleConditions()">Lire</button><br>
                <!-- Ajouter une case à cocher pour les conditions -->
                <label>
                    <input type="checkbox" name="accept_conditions" required> J'accepte les 
                    <span style="cursor: pointer; color: #ff0000; font-weight: 200;" onclick="toggleConditions()">conditions d'utilisation</span>
                </label>

                <button type="submit">S'inscrire</button>
            </form>
        </div>

        <!-- Nouvelle section pour les conditions d'utilisation -->
        <div class="floating-container" id="conditionsContainer">
            <span class="close-button" onclick="toggleConditions()">&times;</span>
            <container>
                <h2>Conditions d'utilisation</h2>

                <p>Voici les conditions d'utilisation de notre service :</p>

                <h1>Conditions d'utilisation et Politique de confidentialité</h1>

                <p>
                    Bienvenue sur Flip App (ci-après dénommé "nous", "notre", "nos", "Flip"). Veuillez lire attentivement
                    ces conditions d'utilisation et notre politique de confidentialité avant d'utiliser nos services.
                </p>

                <h2>1. Collecte d'informations</h2>

                <p>Nous recueillons certaines informations personnelles lorsque vous vous inscrivez sur notre site ou que vous utilisez
                    nos services. Ces informations peuvent inclure, sans s'y limiter :</p>

                <ul>
                    <li>Votre nom</li>
                    <li>Votre prénom</li>
                    <li>Votre adresse e-mail</li>
                    <li>Votre localisation</li>
                    <li>Votre activité sur le site</li>
                    <!-- Ajoutez d'autres catégories d'informations collectées -->
                </ul>

                <h2>2. Utilisation des informations</h2>

                <p>Nous utilisons les informations que nous collectons pour :</p>

                <ul>
                    <li>Fournir, exploiter et maintenir nos services</li>
                    <li>Améliorer, personnaliser et élargir nos services</li>
                    <li>Comprendre et analyser comment vous utilisez nos services</li>
                    <li>Communiquer avec vous, soit directement, soit par l'intermédiaire de l'un de nos partenaires</li>
                </ul>

                <!-- Ajoutez d'autres sections en fonction de l'utilisation de vos données -->

                <h2>3. Protection des informations</h2>

                <p>Nous mettons en œuvre des mesures de sécurité raisonnables pour protéger la sécurité de vos informations
                    personnelles. Cependant, aucune méthode de transmission sur Internet ou méthode de stockage électronique n'est
                    totalement sûre et fiable, et nous ne pouvons garantir sa sécurité absolue.</p>

                <!-- Ajoutez d'autres sections concernant la sécurité et la protection des données -->

                <h2>4. Vos droits</h2>

                <p>Vous avez le droit :</p>

                <ul>
                    <li>D'accéder à vos informations personnelles</li>
                    <li>De corriger vos informations personnelles</li>
                    <li>De supprimer vos informations personnelles</li>
                    <li>De vous opposer au traitement de vos informations personnelles</li>
                </ul>

                <!-- Ajoutez d'autres droits en fonction de la législation applicable -->

                <h2>5. Cookies</h2>

                <p>Notre site utilise des cookies. En continuant à utiliser notre site, vous consentez à notre utilisation de
                    cookies conformément à notre politique de confidentialité.</p>

                <!-- Ajoutez des détails sur l'utilisation des cookies -->

                <p>En cliquant sur "J'accepte les conditions", vous acceptez ces conditions d'utilisation.</p>
                <button onclick="toggleConditions()">Compris !</button>
            </container>
        </div>
    </div>

    <script>
        // Fonction pour afficher ou masquer les conditions d'utilisation
        function toggleConditions() {
            var conditionsContainer = document.getElementById('conditionsContainer');
            conditionsContainer.style.display = conditionsContainer.style.display === 'none' ? 'block' : 'none';
        }
    </script>

    <footer>
        <p>&copy; 2024 Dadflip Solutions</p>
    </footer>
</body>

</html>