<?php
    include 'db.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['db_host'] = $db_host;
    $_SESSION['db_name'] = $db_name;
    $_SESSION['$db_user'] = $db_user;
    $_SESSION['$db_password'] = $db_password;

    if (isset($_SESSION['user_id'])) {
        header("Location: /dadflip/flipApps/flip/app/user/main.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];

                    header("Location: /dadflip/flipApps/flip/app/user/main.php");
                    exit();
                } else {
                    $loginError = "Échec de la connexion. Mot de passe incorrect.";
                }
            } else {
                $loginError = "Échec de la connexion. Nom d'utilisateur incorrect.";
            }
        } catch (PDOException $e) {
            $loginError = "Erreur de base de données: " . $e->getMessage();
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
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f0f0f0;
            color: #333;
            background-image: url('/dadflip/flipApps/flip/app/user/modules/bg/2.png');
            background-repeat: no-repeat; /* Éviter la répétition */
            background-size: cover; /* Ajustement selon les besoins */

        }

        header {
            background-color: #013603;
            color: white;
            text-align: center;
            padding: 20px;
            font-family: 'Helvetica', sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 75vh;
        }

        .form-container {
            width: 80%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .input-container {
            margin: 8px 0;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 5px;
        }

        button,
        .oauth-button {
            background-color: #013603;
            color: white;
            padding: 10px 15px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            display: inline-block;
            box-sizing: border-box;
            border-radius: 5px;
        }

        .oauth-button {
            background-color: #fff;
            color: #333;
            border: 1px solid #ccc;
            height: 40px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .oauth-icon {
            margin-right: 8px;
        }

        .oauth-button:hover {
            background-color: #e0e0e0;
        }

        .form-heading {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #ff0000;
            margin: 10px 0;
        }

        @media only screen and (min-width: 600px) {
            .form-container {
                width: 60%;
            }
        }

        @media only screen and (min-width: 900px) {
            .form-container {
                width: 40%;
            }
        }

        .register-link {
            margin-top: 20px;
            color: #333;
        }

        .register-link a {
            color: #013603;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            background-color: #013603;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Styles pour les liens */
        a {
            color: #013603;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #006400;
        }

    </style>
</head>

<body>
    <header>
        <h1 style="font-size: 2em;">SE CONNECTER</h1>
    </header>

    <div class="container">
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
                <label for="username">Nom d'utilisateur</label>
                <input type="text" placeholder="Entrez votre nom d'utilisateur" name="username" required>

                <label for="password">Mot de passe</label>
                <input type="password" placeholder="Entrez votre mot de passe" name="password" required>

                <button type="submit">Se connecter</button>

                <label for="rememberMe">Se souvenir de moi
                    <input type="checkbox" name="rememberMe">
                </label>
                <a href="forgot_password.html">Mot de passe oublié?</a>

            </form>

            <p class="register-link">Pas encore inscrit? <a href="register.html">Inscrivez-vous ici</a></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Dadflip Solutions</p>
    </footer>
</body>

</html>
