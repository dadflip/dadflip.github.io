<?php
// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\dadflip\assets\php');

// Inclure le fichier '_database_include.php'
include('_database_include.php');
include('_session_data_include.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'email provenant de la page précédente
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Vérifier si l'utilisateur existe dans la base d'authentification
    $stmtCheckUser = $authConn->prepare("SELECT id, username, password FROM utilisateurs WHERE email = ?");
    $stmtCheckUser->bind_param("s", $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    // Si l'utilisateur existe
    if ($resultCheckUser->num_rows > 0) {
        // Afficher le formulaire pour le mot de passe
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Authentification</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
            <link rel="stylesheet" href="styles.css">

            <style>
                /* Style du cadre flottant */
                .floating-container {
                    border-radius: 20px;
                    display: none;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 1000; /* Assure que le cadre reste au premier plan */
                    padding: 20px;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                    max-height: 80%;
                    overflow-y: auto;
                }

                /* Styliser la barre de défilement */
                .floating-container::-webkit-scrollbar {
                    width: 10px; /* Largeur de la barre de défilement */
                    border-radius: 10px;
                    border: 1px dotted #000;
                }

                .floating-container::-webkit-scrollbar-thumb {
                    background-color: #4CAF50; /* Couleur de la poignée de la barre de défilement */
                    border-radius: 5px; /* Bordure de la poignée de la barre de défilement */
                }

                .floating-container::-webkit-scrollbar-track {
                    background-color: #f0f0f0; /* Couleur de fond de la barre de défilement */
                }


                /* Style du bouton de fermeture */
                .close-button {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    cursor: pointer;
                    color: red;
                    font-weight: 300;
                }
            </style>
        </head>

        <body>
            <header>
                <h1 style="font-size: 2em;">AUTHENTIFICATION</h1>
            </header>

            <div class="container">
                <div class="form-container">
                    <h2 class="form-heading">Authentification</h2>

                    <form action="processOauth.php" method="post">
                        <label for="password">Mot de passe</label>
                        <input type="password" placeholder="Entrez votre mot de passe" name="password" required>

                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                        <button type="button" onclick="toggleConditions()">Lire</button><br>
                        <!-- Ajouter une case à cocher pour les conditions -->
                        <label>
                            <input type="checkbox" name="accept_conditions" required> J'accepte les 
                            <span style="cursor: pointer; color: #ff0000; font-weight: 200;" onclick="toggleConditions()">conditions d'utilisation</span>
                            
                        </label>

                        <button type="submit">S'authentifier</button>
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
            </div>

            <footer>
                <p>&copy; 2024 Dadflip Solutions</p>
            </footer>
        </body>

        </html>

        <?php
    } else {
        // Rediriger vers le formulaire d'inscription si l'email n'est pas dans la base d'authentification
        header('Location: inscription.php');
        exit();
    }
}
?>
