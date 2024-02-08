<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le champ 'email' existe dans $_POST
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Votre logique pour vérifier l'existence de l'utilisateur et effectuer l'authentification
    // Utilisez la variable $email dans votre logique.

    $stmtCheckUser = $authConn->prepare("SELECT id, username, password FROM utilisateurs WHERE email = ?");
    $stmtCheckUser->bind_param("s", $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    // Si l'utilisateur existe
    if ($resultCheckUser->num_rows > 0) {
        // Afficher une invite pour l'authentification (à implémenter)
        
        // Vous pouvez également rediriger directement ici plutôt que vers une autre page
        // et inclure le formulaire d'authentification dans cette page.
        include 'authentification.php';
        exit();
    } else {
        // Rediriger vers le formulaire d'inscription si l'email n'est pas dans la base d'authentification
        header('Location: inscription.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .login-container {
            text-align: center;
        }

        .login-option {
            cursor: pointer;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 10px;
            width: 200px; /* Taille des conteneurs rectangulaires */
            height: 150px;
            display: inline-block;
        }

        .container {
            display: none;
            text-align: center;
        }

        .form-container {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-top: 20px;
            left: 50%;
            width: 300px; /* Taille du conteneur du formulaire */
        }

        .close-button {
            cursor: pointer;
            margin-top: 10px;
            background-color: #ddd;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1 style="font-size: 2em;">SE CONNECTER</h1>
    </header>

    <!-- Conteneurs rectangulaires cliquables -->
    <div class="login-container">
        <div class="login-option" onclick="showLoginForm()">
            <i class="fas fa-user"></i>
            <p>Se connecter avec son compte Dadflip</p>
        </div>

        <br>

        <a href="inscription.php">
            <div class="login-option">
                <i class="fas fa-user-plus"></i>
                <p>S'inscrire</p>
            </div>
        </a>

    </div>

    <div class="container" id="formContainer">
        <div class="form-container">
            <h2 class="form-heading">Connexion</h2>

            <?php if (isset($loginError)) : ?>
                <p class="error-message"><?php echo $loginError; ?></p>
            <?php endif; ?>

            <a href="login_google.php" class="oauth-button">
                <i class="fab fa-google oauth-icon" aria-hidden="true"></i> Se connecter avec Google
            </a>
            <a href="login_microsoft.php" class="oauth-button">
                <i class="fab fa-windows oauth-icon" aria-hidden="true"></i> Se connecter avec Microsoft
            </a>

            <form action="#" method="post">
                <label for="email">Email</label>
                <input type="text" placeholder="Entrez votre email" name="email" required>

                <button type="submit">Se connecter</button>
            </form>

            <div class="close-button" onclick="hideLoginForm()">Fermer</div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Dadflip Solutions</p>
    </footer>

    <script>
        function showLoginForm() {
            var formContainer = document.getElementById('formContainer');
            formContainer.style.display = 'block';
        }

        function hideLoginForm() {
            var formContainer = document.getElementById('formContainer');
            formContainer.style.display = 'none';
        }
    </script>
</body>

</html>
