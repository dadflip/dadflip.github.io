<?php
// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');

session_start();

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'];

if(!isset($userId)){
    header('Location: http://localhost/flipapp/_oauth/login.php');
}

// Vérifier si la variable de session 'email' existe
if (!isset($userEmail)) {
    // Rediriger vers la page de login si l'utilisateur n'est pas authentifié
    header('Location: http://localhost/flipapp/_oauth/login.php');
    exit();
}

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
        <link rel="stylesheet" href="http://localhost/flipapp/_assets/css/flipapp.css">

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
                <img class="user-avatar" src="http://localhost/flipapp/_assets/img/logo/green_white_notext.png" alt="User Avatar">
            </div>
            <div class="loading-icon">
                <img class="loading-logo" src="http://localhost/flipapp/_assets/img/apps_logo/flip_black_rmbg.png" alt="Custom Loading Icon">
            </div>
        </header>

        <!-- Bouton pour ouvrir/fermer le menu -->
        <button class="menu-btn" onclick="toggleMenu()"><center>&#9673;</center></button>

        <!-- Menu de navigation vertical -->
        <div class="vertical-menu" id="menu">
            <ul>
                <li><a href="http://localhost/dadflip/flipApps/flip/app/user/main.php">APPLICATIONS</a></li>
                <li><a href="#">PROFIL</a></li>
                <li><a href="#">PARAMETRES</a></li>
                <li><a href="http://localhost/flipapp/_usr/exit.php">DECONNEXION</a></li>
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
        <script src="http://localhost/flipapp/_assets/js/flipapp.js"></script>
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


        <script>
            /*document.getElementById("capturePhoto").addEventListener("click", function () {
                captureMedia("image");
            });

            document.getElementById("captureVideo").addEventListener("click", function () {
                captureMedia("video");
            });*/

            // Fonction pour échapper les caractères spéciaux dans une expression régulière
            function escapeRegex(str) {
                return str.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
            }

            // Fonction pour gérer la touche "Enter"
            function handleKeyPress(event) {
                if (event.key === "Enter") {
                    // Appeler la fonction effectuerRecherche lorsque la touche "Enter" est pressée
                    effectuerRecherche();
                }
            }

            // Fonction pour revenir à la page précédente
            function goBack() {
                window.history.back();
            }


            // -------------- Toogle -------------------------------------------------------

            // Fonction pour afficher ou masquer le formulaire
            function toggleForm() {
                var newTopicForm = document.getElementById("newTopicForm");
                var loadingIcon = document.querySelector(".loading-icon");

                // Inversez l'état d'affichage du formulaire
                if (newTopicForm.style.display === "none") {
                    newTopicForm.style.display = "block";
                    loadingIcon.style.display = "none"; // Masquer l'icône de chargement
                } else {
                    newTopicForm.style.display = "none";
                    loadingIcon.style.display = "none"; // Afficher l'icône de chargement
                }
            }

            function toggleMenu() {
                var menu = document.getElementById("menu");
                menu.style.display = menu.style.display === "block" ? "none" : "block";
            }


            // ----------------- User actions ---------------------------------------------

            function captureMedia(mediaType) {
                navigator.mediaDevices
                    .getUserMedia({ video: true, audio: true })
                    .then(function (stream) {
                        if (mediaType === "image") {
                            // Capturer une photo
                            var video = document.createElement("video");
                            video.srcObject = stream;

                            video.onloadedmetadata = function () {
                                var canvas = document.createElement("canvas");
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvas.getContext("2d").drawImage(video, 0, 0);

                                // Convertir le canvas en base64 (vous pouvez utiliser une autre méthode selon vos besoins)
                                var imageDataURL = canvas.toDataURL("image/png");
                                console.log(imageDataURL);

                                // Fermer le flux vidéo
                                stream.getTracks().forEach((track) => track.stop());
                            };
                        } else if (mediaType === "video") {
                            // Enregistrer une vidéo
                            var videoElement = document.createElement("video");
                            videoElement.srcObject = stream;

                            // Créer une balise vidéo pour l'enregistrement
                            var mediaRecorder = new MediaRecorder(stream);
                            var chunks = [];

                            mediaRecorder.ondataavailable = function (e) {
                                if (e.data.size > 0) {
                                    chunks.push(e.data);
                                }
                            };

                            mediaRecorder.onstop = function () {
                                var blob = new Blob(chunks, { type: "video/webm" });

                                // Créer un objet URL à partir du blob et l'assigner à une balise vidéo
                                var videoURL = URL.createObjectURL(blob);
                                videoElement.src = videoURL;

                                // Fermer le flux vidéo
                                stream.getTracks().forEach((track) => track.stop());
                            };

                            mediaRecorder.start();

                            // Arrêtez l'enregistrement après quelques secondes (à ajuster selon vos besoins)
                            setTimeout(function () {
                                mediaRecorder.stop();
                            }, 5000);
                        }
                    })
                    .catch(function (error) {
                        console.error("Erreur lors de l'accès à la caméra/microphone:", error);
                    });
            }


            function envoyerCommentaire(textId) {
                var commentaire = document.getElementById("zoneCommentaire").value;
                console.log(commentaire);

                // Envoyer le commentaire via AJAX
                effectuerAction("envoyer_commentaire", {
                    textId: textId,
                    commentaire: commentaire,
                });

                // Vider le contenu du textarea
                document.getElementById("zoneCommentaire").value = "";

                $(document).ready(function () {
                    construireFlipboxRecommandees();
                });
            }

            // Dans la fonction liker
            function liker(textId) {
                effectuerAction("liker", { textId: textId });
                mettreAJourResultats(textId); // Passer textId lors de l'appel
            }

            // Dans la fonction disliker
            function disliker(textId) {
                effectuerAction("disliker", { textId: textId });
                mettreAJourResultats(textId); // Passer textId lors de l'appel
            }

            // Dans la fonction mettreEnFavori
            function mettreEnFavori(textId) {
                effectuerAction("mettre_en_favori", { textId: textId });
                mettreAJourResultats(textId); // Passer textId lors de l'appel
            }

            // Dans la fonction faireUnDon
            function faireUnDon(textId) {
                effectuerAction("faire_un_don", { textId: textId });
                mettreAJourResultats(textId); // Passer textId lors de l'appel
            }

            // Dans la fonction mettreAJourResultats
            function mettreAJourResultats(textId, action) {
                if (!textId) {
                    console.error(
                        "textId non défini. Impossible de mettre à jour les résultats."
                    );
                    return;
                }

                $.ajax({
                    url: ".php/search.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        // La mise à jour a réussi
                        var newTextLikes = response.likes;
                        var newTextDislikes = response.dislikes;

                        console.log("textId:", textId);

                        // Trouver le conteneur de likes et dislikes dans la flipbox spécifique
                        var likesContainer = $(
                            '.flip-box[data-text-id="' + textId + '"] .likes-container'
                        );

                        // Récupérer les valeurs actuelles de likes et dislikes
                        var likes = parseInt(likesContainer.attr("data-likes"));
                        var dislikes = parseInt(likesContainer.attr("data-dislikes"));

                        // Mettre à jour les valeurs en fonction de l'action
                        if (action === "like") {
                            likes++;
                        } else if (action === "dislike") {
                            dislikes++;
                        }

                        // Mettre à jour les données dans le conteneur
                        likesContainer.attr("data-likes", likes);
                        likesContainer.attr("data-dislikes", dislikes);

                        // Mettre à jour l'affichage des likes et dislikes dans l'interface utilisateur
                        likesContainer.find(".likes").text("Likes: " + likes);
                        likesContainer.find(".dislikes").text("Dislikes: " + dislikes);

                        // Afficher le contenu de l'élément .likes
                        console.log("Réponse Ajax:", response);
                        console.log("Likes Element:");

                        console.log(textId);
                        //var termeRecherche = $('#barre-recherche').val(); // Utilisez le bon ID ici
                        //var termeRecherche = '.';
                        //effectuerRecherche(termeRecherche); // Refaites la recherche pour obtenir les résultats mis à jour
                        $(document).ready(function () {
                            construireFlipboxRecommandees();
                        });
                    },
                    error: function () {
                        console.error("Erreur lors de la mise à jour des résultats");
                    },
                });
            }

            function effectuerAction(action, data) {
                $.ajax({
                    url: ".php/actions.php",
                    type: "POST",
                    data: { action: action, data: data },
                    success: function (response) {
                        //
                    },
                    error: function () {
                        console.error("Erreur lors de l'action " + action);
                    },
                });
            }


            // ----------------- Getting Infos ---------------------------------------------

            // Fonction pour récupérer la date actuelle (à implémenter)
            function getCurrentDate() {
                var currentDate = new Date();
                // Formater la date selon vos besoins
                return currentDate.toISOString().slice(0, 10);
            }

            // Fonction pour récupérer l'heure actuelle (à implémenter)
            function getCurrentTime() {
                var currentTime = new Date();
                // Formater l'heure selon vos besoins
                return currentTime.toISOString().slice(11, 19);
            }

            // Fonction pour récupérer les informations du navigateur
            function getBrowserInfo() {
                var browserInfo = {
                    browser_name: navigator.appName,
                    browser_version: navigator.appVersion,
                    user_agent: navigator.userAgent,
                    language: navigator.language,
                    platform: navigator.platform,
                    geolocation: navigator.geolocation ? true : false,
                };

                return browserInfo;
            }

            //------------------------ Flip Boxes Builder --------------------------------------

            // Fonction pour construire les flipbox au chargement de la page
            function construireFlipboxRecommandees() {
                $.ajax({
                    url: ".php/algo.php",
                    type: "GET",
                    dataType: "json",
                    success: function (resultatsRecommandes) {
                        console.log("Réponse Ajax réussie :", resultatsRecommandes);
                        if (resultatsRecommandes != null) {
                            construireFlipbox(resultatsRecommandes);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            "Erreur lors de la requête Ajax pour les résultats recommandés:",
                            status,
                            error
                        );
                        console.log("Réponse Ajax échouée :", xhr.responseText);
                    },
                });
            }

            // Fonction pour construire les flipbox avec les résultats donnés
            function construireFlipbox(resultats) {
                // Effacer les résultats précédents
                $("#resultats").empty();

                var $loadingIcon = $(".loading-icon");

                $loadingIcon.hide();

                // Modifier l'overlay (ajuster la couleur, etc.)
                $(".overlay-menu").css({
                    backgroundColor: "#333333",
                    // Autres styles de l'overlay
                });

                //for (var i = 0; i < resultats.length; i++) {
                var recommandMaxSize = 8;

                // Afficher les nouveaux résultats dans des flipboxes
                for (var i = 0; i < recommandMaxSize; i++) {
                    var resultat = resultats[i];

                    if(resultat.keywords){
                        console.log(resultat.keywords);
                        var flipbox =
                        '<div class="flip-box clickable" data-keywords="' +
                        resultat.keywords +
                        '" data-category="' +
                        resultat.category +
                        '" data-title="' +
                        resultat.title +
                        '" data-text-id="' +
                        resultat.text_id +
                        '">';
                    }else{
                        var tmpkeywd = 'all';
                        console.log(tmpkeywd);
                        var flipbox =
                        '<div class="flip-box clickable" data-keywords="' +
                        tmpkeywd +
                        '" data-category="' +
                        resultat.category +
                        '" data-title="' +
                        resultat.title +
                        '" data-text-id="' +
                        resultat.text_id +
                        '">';
                    }
                    

                    flipbox += '<div class="flip-box-inner">';

                    flipbox += '<div class="flip-box-front">';
                    // Ajouter le nom de l'utilisateur sur la face avant de la flipbox
                    flipbox += "<h5>" + resultat.username + "</h5>";
                    // Ajouter le cadre pour afficher une image, une vidéo, du texte, de l'audio...
                    flipbox += '<div class="cadre">';

                    // Condition pour afficher une image ou une vidéo en fonction du type de média
                    if (resultat.media_type === "image") {
                        flipbox +=
                            '<img class="media-center" src="' +
                            resultat.media_url +
                            '" alt="Image">';
                    } else if (resultat.media_type === "video") {
                        flipbox += '<video class="media-center" controls>';
                        flipbox += '<source src="' + resultat.media_url + '" type="video/mp4">';
                        flipbox += "Votre navigateur ne supporte pas la lecture de la vidéo.";
                        flipbox += "</video>";
                    }

                    // Afficher l'URL du média
                    //flipbox += '<p>URL du média : ' + resultat.media_url + '</p>';

                    // Exemple d'affichage d'une image (ajustez selon vos besoins)
                    flipbox += "</div>";
                    flipbox += "<h6>" + resultat.title + "</h6>";
                    flipbox += "</div>";

                    flipbox += '<div class="flip-box-back">';

                    flipbox += '<div class="scrollable-content">'; // Ajoutez cette ligne
                    flipbox += "<p>" + resultat.content + "</p>";
                    flipbox += "</div>"; // Ajoutez cette ligne

                    // Ajouter des icônes cliquables pour liker, disliker, mettre en favori, faire un don en bas des flipbox
                    flipbox += '<div class="actions no-flip">';
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="liker(' +
                        resultat.text_id +
                        ')"><i class="fas fa-thumbs-up"></i> ' +
                        resultat.likes +
                        "</span>";
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="disliker(' +
                        resultat.text_id +
                        ')"><i class="fas fa-thumbs-down"></i> ' +
                        resultat.dislikes +
                        "</span>";
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="mettreEnFavori(' +
                        resultat.text_id +
                        ')"><i class="fas fa-heart"></i></span>';
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="faireUnDon(' +
                        resultat.text_id +
                        ')"><i class="fas fa-donate"></i></span>';
                    flipbox += "</div>";

                    // Ajouter la zone de commentaires et l'icône cliquable pour l'envoi
                    flipbox += '<div class="commentaires no-flip">';

                    flipbox +=
                        '<textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>';
                    flipbox +=
                        '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(' +
                        resultat.text_id +
                        ')">Envoyer</button>';

                    flipbox += "</div>";

                    flipbox += "</div>";
                    flipbox += "</div>";

                    flipbox += "</div>";

                    // Ajouter la flipbox à la liste des résultats
                    $("#resultats").append(flipbox);
                }
            }


            //------------------------ Recherches et Resultats ---------------------------------

            // Fonction pour effectuer la recherche AJAX
            function effectuerRecherche() {
                // Récupérer le terme de recherche depuis la barre de recherche
                var termeRecherche = $("#barre-recherche").val();

                // Afficher le logo de chargement et ajuster sa position et sa taille
                var $loadingIcon = $(".loading-icon");
                var $searchBar = $(".search-bar");

                if (termeRecherche.trim() !== "") {
                    // Si la barre de recherche n'est pas vide
                    $loadingIcon.hide();

                    // Modifier l'overlay (ajuster la couleur, etc.)
                    $(".overlay-menu").css({
                        backgroundColor: "#333333",
                        // Autres styles de l'overlay
                    });

                    // Réduire la taille et déplacer à gauche
                    $searchBar.css({
                        width: "7%",
                        left: "5%",
                        transition: "width 0.5s, left 0.5s",
                    });
                } else {
                    // Si la barre de recherche est vide, afficher l'icône centrée
                    $loadingIcon.show();
                    $loadingIcon.css({
                        position: "fixed",
                        top: "40%",
                        left: "50%",
                        transform: "translate(-50%, -50%)",
                        zIndex: 4,
                    });

                    // Ajuster la taille et la position d'origine
                    $searchBar.css({
                        width: "40%",
                        left: "50%",
                        transition: "width 0.5s, left 0.5s",
                    });

                    $searchBar.css("border-radius", "20px"); // Ajuster la bordure de la barre de recherche
                    // Réinitialiser l'overlay à sa forme initiale
                    $(".overlay-menu").css({
                        backgroundColor: "#000000",
                        // Autres styles de l'overlay
                    });
                }

                // Effectuer la requête AJAX vers le script de recherche (search.php)
                $.ajax({
                    url: ".php/search.php",
                    type: "GET",
                    data: { q: termeRecherche },
                    dataType: "json",
                    success: function (resultats) {
                        // Afficher les résultats dynamiquement avec surbrillance
                        console.log(resultats);
                        afficherResultatsAvecSurbrillance(resultats, termeRecherche);
                    },
                    error: function () {
                        console.error("Erreur lors de la requête AJAX");
                    },
                });
            }

            // Fonction pour afficher les resultats de recherche
            function afficherResultatsAvecSurbrillance(resultats, termeRecherche) {
                // Effacer les résultats précédents
                $("#resultats").empty();

                // Afficher les nouveaux résultats dans des flipboxes
                for (var i = 0; i < resultats.length; i++) {
                    var resultat = resultats[i];

                    //Afficher tous les champs de la variable dans la console
                    //console.log('Résultat:', resultat.media_url);
                    //console.log('Résultat:', resultat);

                    // Mettre en surbrillance le titre et le contenu
                    var titreSurligne = mettreEnSurbrillance(termeRecherche, resultat.title);
                    var contenuSurligne = mettreEnSurbrillance(
                        termeRecherche,
                        resultat.content
                    );
                    console.log(resultat.title);

                    // Créer une flipbox avec les données incluses
                    var flipbox =
                        '<div class="flip-box clickable" data-keywords="' +
                        resultat.keywords +
                        '" data-category="' +
                        resultat.category +
                        '" data-title="' +
                        resultat.title +
                        '" data-text-id="' +
                        resultat.text_id +
                        '">';

                    flipbox += '<div class="flip-box-inner">';

                    flipbox += '<div class="flip-box-front">';
                    // Ajouter le nom de l'utilisateur sur la face avant de la flipbox
                    flipbox += "<h5>" + resultat.username + "</h5>";
                    // Ajouter le cadre pour afficher une image, une vidéo, du texte, de l'audio...
                    flipbox += '<div class="cadre">';

                    // Condition pour afficher une image ou une vidéo en fonction du type de média
                    if (resultat.media_type === "image") {
                        flipbox +=
                            '<img class="media-center" src="' +
                            resultat.media_url +
                            '" alt="Image">';
                    } else if (resultat.media_type === "video") {
                        flipbox += '<video class="media-center" controls>';
                        flipbox += '<source src="' + resultat.media_url + '" type="video/mp4">';
                        flipbox += "Votre navigateur ne supporte pas la lecture de la vidéo.";
                        flipbox += "</video>";
                    }

                    // Afficher l'URL du média
                    //flipbox += '<p>URL du média : ' + resultat.media_url + '</p>';

                    // Exemple d'affichage d'une image (ajustez selon vos besoins)
                    flipbox += "</div>";
                    flipbox += "<h6>" + titreSurligne + "</h6>";
                    flipbox += "</div>";

                    flipbox += '<div class="flip-box-back">';
                    flipbox += '<div class="scrollable-content">';
                    flipbox += "<p>" + contenuSurligne + "</p>";
                    flipbox += "</div>";

                    // Ajouter des icônes cliquables pour liker, disliker, mettre en favori, faire un don en bas des flipbox
                    flipbox += '<div class="actions no-flip">';
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="liker(' +
                        resultat.text_id +
                        ')"><i class="fas fa-thumbs-up"></i> ' +
                        resultat.likes +
                        "</span>";
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="disliker(' +
                        resultat.text_id +
                        ')"><i class="fas fa-thumbs-down"></i> ' +
                        resultat.dislikes +
                        "</span>";
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="mettreEnFavori(' +
                        resultat.text_id +
                        ')"><i class="fas fa-heart"></i></span>';
                    flipbox +=
                        '<span class="action-icon no-flip" onclick="faireUnDon(' +
                        resultat.text_id +
                        ')"><i class="fas fa-donate"></i></span>';
                    flipbox += "</div>";


                    // Ajouter la zone de commentaires et l'icône cliquable pour l'envoi
                    flipbox += '<div class="commentaires no-flip">';
                    flipbox +=
                        '<textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>';
                    flipbox +=
                        '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(' +
                        resultat.text_id +
                        ')">Envoyer</button>';
                    flipbox += "</div>";
                    flipbox += "</div>";
                    flipbox += "</div>";

                    flipbox += "</div>";

                    $("#resultats").append(flipbox);
                }
            }

            // Fonction pour mettre en surbrillance le texte de recherche dans les résultats
            function mettreEnSurbrillance(texteRecherche, contenu) {
                // Créer une expression régulière pour rechercher le texte de recherche de manière insensible à la casse
                var regex = new RegExp("(" + escapeRegex(texteRecherche) + ")", "ig");

                // Remplacer le texte correspondant par le même texte enveloppé de balises <span> pour la surbrillance
                var resultatSurligne = contenu.replace(
                    regex,
                    '<span class="surligne">$1</span>'
                );

                return resultatSurligne;
            }
        </script>
        
    </body>

</html>
