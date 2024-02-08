<?php
// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\dadflip\assets\php');

// Inclure le fichier '_database_include.php'
include('_database_include.php');
include('_session_data_include.php');

// Vérifier si la variable de session 'email' existe
if (!isset($userEmail)) {
    // Rediriger vers la page de login si l'utilisateur n'est pas authentifié
    header('Location: http://localhost/flipapp/login.php');
    exit();
}

// Le reste du code de votre page principale (app.php) vient ici...

// Exemple : Afficher le nom d'utilisateur
//echo 'Bienvenue, ' . $_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Ajoutez cette balise meta pour définir la langue du document -->
        <meta http-equiv="Content-Language" content="en">
        <title>Page d'Accueil</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="/dadflip/assets/css/flipapp.css">

    </head>

    <body>
        <header>
            <div class="user-info-bar">
                <?php
                // Vérifiez si l'utilisateur est authentifié
                if (isset($_SESSION['user_id'])) {
                    // Récupérez le nom d'utilisateur à partir de la session
                    $userName = $_SESSION['username'];
                    $userId = $_SESSION['user_id'];

                    // Affichez le nom d'utilisateur en majuscules et en gras
                    echo '<span class="user-name">' . strtoupper($userName) . '</span>';
                }
                ?>
                <!-- Bouton pour afficher le formulaire (Nouveau sujet)-->
                <button onclick="toggleForm()">➲FLIPBOX<br></button>

                <!-- Vous pouvez également afficher d'autres informations de l'utilisateur ici, par exemple, une image d'avatar -->
                <img class="user-avatar" src="/dadflip/assets/img/logo/1-removebg-preview.png" alt="User Avatar">
            </div>
            <div class="loading-icon">
                <img class="loading-logo" src="/dadflip/flipApps/flip/app/user/modules/flip-img/3-removebg-preview.png" alt="Custom Loading Icon">
            </div>
        </header>

        <!-- Bouton pour ouvrir/fermer le menu -->
        <button class="menu-btn" onclick="toggleMenu()"><center>&#9673;</center></button>

        <!-- Menu de navigation vertical -->
        <div class="vertical-menu" id="menu">
            <ul>
                <li><a href="/dadflip/flipApps/flip/app/user/main.php">APPLICATIONS</a></li>
                <li><a href="#">PROFIL</a></li>
                <li><a href="#">PARAMETRES</a></li>
                <li><a href="#">AIDE</a></li>
            </ul>
        </div>

        <!-- Bouton pour revenir à la page précédente -->
        <button class="back-button" onclick="goBack()">Back</button>

        <!-- Formulaire pour créer un nouveau sujet (initiallement masqué) -->
        <div id="newTopicForm" style="display: none;">
            <form action=".php/add_flipbox.php" method="post" enctype="multipart/form-data">
                <label for="title">Titre :</label>
                <input type="text" name="title" required><br>

                <label for="content">Contenu :</label>
                <textarea name="content" required></textarea><br>

                <label for="category">Catégorie :</label>
                <select name="category" required>
                    <option value="Particulier">Particulier</option>
                    <option value="Entreprise">Entreprise</option>
                    <!-- Ajoutez d'autres options de catégories ici -->
                </select><br>

                <label for="keywords">Mots-clés (séparés par des virgules) :</label>
                <input type="text" name="keywords"><br>

                <!-- Ajout de la possibilité d'uploader des images -->
                <label for="image">Uploader une image :</label>
                <input type="file" name="image" accept="image/*"><br>

                <!-- Ajout de la possibilité d'uploader des vidéos -->
                <label for="video">Uploader une vidéo :</label>
                <input type="file" name="video" accept="video/*"><br>

                <!-- Ajout de la possibilité de capturer une photo via la caméra -->
                <!--label for="cameraCapture">Capturer une photo :</!label>
                <input type="button" value="Capturer une photo" id="capturePhoto"><br>

                Ajout de la possibilité d'enregistrer une vidéo via la caméra
                <label for="videoCapture">Enregistrer une vidéo :</label>
                <input type="button" value="Enregistrer une vidéo" id="captureVideo"><br-->

                <input type="submit" value="FLIP ➯">
            </form>

        </div>
        
        <div class="content">
            <!-- Barre de recherche -->
            <div class="search-bar">
                <input id="barre-recherche" type="text" placeholder="SEARCH BOX : What is your request?" onkeydown="handleKeyPress(event)">
                <div class="search-icon" onclick="effectuerRecherche()">&#10147;</div>
            </div>

            <!-- Ajout d'une marge pour éviter le chevauchement avec la barre de recherche -->
            <div style="margin-top: 80px; margin-left: 8%; margin-right: 8%;">
                <!-- Zone pour afficher les résultats -->
                <div class="flip-box-container"><div id="resultats" class="resultats"></div></div>
            </div>
        </div>

        <footer>
            <div class="ante-footer"></div>
            <p> &copy; Dadflip Solutions 2024</p>
        </footer>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="/dadflip/assets/js/flipapp.js"></script>
        <script>
            //Envoyer des données au serveur
            $(document).ready(function() {

                // Attachez l'événement clic aux flipboxes générées dynamiquement
                $('#resultats').on('click', '.flip-box.clickable', function() {
                    // Récupérer l'ID de l'utilisateur depuis la variable de session PHP
                    var userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
                    
                    // Vérifier si l'ID de l'utilisateur est disponible
                    if (userId !== null) {

                        // Récupérer les autres informations nécessaires
                        var title = $(this).attr('data-title');
                        var keywords = $(this).attr('data-keywords');
                        var category = $(this).attr('data-category');
                        var currentDate = getCurrentDate();
                        var currentTime = getCurrentTime();
                        var browserInfo = getBrowserInfo();

                        console.log(browserInfo);
                        console.log(keywords);
                        var flipboxData = {
                            keywords: keywords,
                            category: category
                        };
                        console.log(flipboxData);

                        // Créer un objet contenant les données à envoyer
                        var dataToSend = {
                            userId: userId,
                            title: title,
                            keywords: keywords,
                            currentDate: currentDate,
                            currentTime: currentTime,
                            browserInfo: browserInfo,
                            category: category
                        };

                        // Envoyer les données au script PHP via une requête Ajax
                        $.ajax({
                            type: 'POST',
                            url: '.php/traitment.php', // Remplacez cela par le chemin correct vers votre script PHP
                            data: dataToSend,
                            success: function(response) {
                                // Le traitement PHP a réussi, vous pouvez traiter la réponse si nécessaire
                                console.log('Succès :', response);
                            },
                            error: function(error) {
                                // Une erreur s'est produite lors de la requête Ajax
                                console.error('Erreur Ajax :', error);
                            }
                        });
                    } else {
                        // L'ID de l'utilisateur n'est pas disponible, gérer en conséquence
                        console.error('ID de l\'utilisateur non disponible');
                    }
                });

                //-------------------------------------------------------------------------------------------------

                // Attachez l'événement clic aux flipboxes générées dynamiquement
                $('#resultats').on('click', '.flip-box.clickable', function(event) {
                    // Vérifiez si l'élément cliqué a la classe 'no-flip'
                    if (!$(event.target).hasClass('no-flip')) {
                        // Si l'élément cliqué ne possède pas la classe 'no-flip', effectuez le retournement
                        $(this).find('.flip-box-inner').toggleClass('flipped');
                    }
                });

                // Gérer la saisie dans la barre de recherche
                $('#barre-recherche').on('input', function () {
                    // Fermer le menu lorsque du texte est saisi dans la barre de recherche
                    $('#menu').hide();
                });

                // Attacher la fonction de recherche à l'événement de saisie dans la barre de recherche
                $("#barre-recherche").on("input", function () {
                    // Déclencher la recherche après un léger délai (par exemple, 300 ms) pour éviter une recherche à chaque frappe
                    clearTimeout(window.rechercheTimeout);
                    window.rechercheTimeout = setTimeout(effectuerRecherche, 300);
                });


                // Appeler la méthode pour construire les flipbox au chargement de la page
                construireFlipboxRecommandees();


            });            

        </script>
        
    </body>

</html>
