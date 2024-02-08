<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f0f0f0;
            overflow: auto; /* Ajout de la barre de défilement */
            transition: background-color 0.5s;
        }

        .overlay-menu {
            height: 0;
            width: 0;
            position: fixed;
            z-index: 1;
            bottom: 20px;
            left: 50%;
            background-color: #000000;
            overflow-y: hidden;
            transition: width 0.5s, height 0.5s;
            border-radius: 0%;
            border: #ccc, 2px;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay-menu a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 20px;
            color: #818181;
            display: block;
            transition: 0.3s;
            text-align: center;
        }

        .overlay-menu a:hover,
        .overlay-menu a:focus {
            color: #f1f1f1;
        }

        .overlay-menu .closebtn {
            position: absolute;
            bottom: 20px;
            right: 25px;
            font-size: 40px;
            cursor: pointer;
            color: #818181;
            transition: transform 0.3s;
        }

        .menu-btn {
            font-size: 70px;
            cursor: pointer;
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            color: #000000;
            z-index: 2;
            transition: color 0.5s;
        }

        .content {
            text-align: center;
            margin-top: 50px;
            position: relative;
        }

        /* Ajout de marges pour éviter le chevauchement avec la barre de recherche */
        .content > div {
            margin-top: 80px;
        }

        /* Ajout du style pour la barre de défilement */
        .resultats {
            max-height: 70vh; /* Hauteur maximale de la zone de résultats */
            overflow-y: auto; /* Ajout de la barre de défilement uniquement si nécessaire */
        }

        .swiper-container {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #f0f0f0;
            /* Autres styles pour chaque diapositive */
        }

        .banner {
            background-color: #f0f0f0;
            color: rgb(0, 0, 0);
            padding: 5px;
            text-align: center;
            border-radius: 10px;
            height: 80%;
            width: 75%;
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

        .search-bar {
            width: 85%;
            height: 10%;
            background-color: #fff;
            border: 1px solid #004113;
            border-radius: 20px;
            position: fixed;
            bottom: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: width 0.5s;
            z-index: 3; /* Ajustement pour placer la barre de recherche au-dessus des flipboxes */
        }

        .search-bar input {
            border: none;
            outline: none;
            width: calc(100% - 30px);
            padding: 8px;
            box-sizing: border-box;
            border-radius: 20px;
        }

        .search-bar:hover {
            width: 60%;
        }

        .search-bar .search-icon {
            font-size: 20px;
            color: #055e08;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .search-bar:hover .search-icon {
            transform: rotate(90deg);
        }

        .flip-box {
            background-color: transparent;
            border-radius: 20px;
            width: 40%; /* Ajuster la largeur selon vos besoins */
            height: 200px;
            perspective: 1000px;
            margin: 20px;
            display: inline-block;
        }

        .flip-box-inner {
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.5s;
        }

        .flip-box:hover .flip-box-inner {
            transform: rotateY(180deg);
        }

        .flip-box-front,
        .flip-box-back {
            width: 100%;
            height: 100%;
            position: absolute;
            backface-visibility: hidden;
        }

        .flip-box-front {
            background-color: #ffffff65;
            color: #000;
            text-align: center;
            border: 1px solid #004113;
            border-radius: 20px;
        }

        .flip-box-back {
            background-color: #ffffff7e;
            color: #005515;
            text-align: center;
            border: 1px solid #004113;
            border-radius: 20px;
            transform: rotateY(180deg);
        }

        .back-button {
            position: fixed;
            bottom: 8%;
            left: 50%;
            z-index: 3; /* Au-dessus des flipboxes et de la barre de recherche */
            background-color: #003a05; /* Couleur de fond */
            border-radius: 8px;
            color: white; /* Couleur du texte */
            border: none; /* Supprimer la bordure */
            padding: 10px 15px; /* Espacement intérieur */
            font-size: 16px; /* Taille de la police */
            cursor: pointer;
            display: block; /* Afficher en tant que bloc pour le centrer */
            margin: 20px auto; /* Marge pour centrer horizontalement */
            width: 20%;
            transform: translateX(-50%); /* Centrer horizontalement */
        }


        .loading-icon {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 4; /* Au-dessus de tout, y compris la barre de recherche, les résultats et le bouton Back */
        }


        /* Animation de rotation */
        @keyframes rotate {
            0% {
                transform: rotate(0deg) scale(1);
            }
            100% {
                transform: rotate(360deg) scale(0.8);
            }
        }

        /* Appliquer l'animation à l'icône */
        .loading-logo {
            animation: rotate 0.5s linear forwards; /* 2s de rotation, arrêt après 2 secondes */
        }

        .vertical-menu {
            display: none; /* Caché par défaut */
            width: 30%;
            border-radius: 10px;
            position: fixed;
            top: 30%;
            left: 10%;
            transform: translateY(-50%);
            background-color: #000000;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            z-index: 5; /* Au-dessus des flipboxes, de la barre de recherche et du bouton Back */
        }

        .vertical-menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .vertical-menu li {
            padding: 15px;
            text-align: center;
        }

        .vertical-menu a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            display: block;
            transition: 0.3s;
        }

        .vertical-menu a:hover {
            background-color: #555;
        }

        .menu-btn {
            font-size: 24px;
            position: sticky;
            border-radius: 10px;
            top: 10%;
            left: 12%;
            cursor: pointer;
            z-index: 8; /* Au-dessus de tout, y compris le menu */
            background: #000000;
            color: #fff;
            border: none;
            padding: 0px;
            width: 5%;
        }

        .user-info-bar {
            background-color: #000000; /* Couleur de fond de la bande utilisateur */
            color: #fff; /* Couleur du texte */
            padding: 10px; /* Espacement interne */
            text-align: right; /* Alignement du texte à droite */
        }

        .user-avatar {
            width: 30px; /* Ajustez la taille de l'avatar selon vos besoins */
            height: 30px;
            border-radius: 50%; /* Pour obtenir une forme circulaire */
            margin-left: 10px; /* Espacement entre le nom et l'avatar */
        }

        .user-name {
            font-weight: bold; /* Pour mettre en gras le nom de l'utilisateur */
            margin-right: 10px; /* Espacement entre le nom et le bord droit */
        }

        /* Ajouter ces styles pour rendre la surbrillance visible */
        .surligne {
            background-color: green;
            font-weight: bold;
        }


    </style>
</head>

<body>
    <header>
        <div class="user-info-bar">
            <!-- Ajoutez ici les informations de l'utilisateur, par exemple, son nom, image, etc. -->
            <span class="user-name">John Doe</span>
            <img class="user-avatar" src="/dadflip/assets/img/logo/1-removebg-preview.png" alt="User Avatar">
        </div>
        <div class="loading-icon">
            <img class="loading-logo" src="/dadflip/assets/img/logo/8-removebg-preview.png" alt="Custom Loading Icon">
        </div>
    </header>

    <!-- Bouton pour ouvrir/fermer le menu -->
    <button class="menu-btn" onclick="toggleMenu()"><center>&#9673;</center></button>

    <!-- Menu de navigation vertical -->
    <div class="vertical-menu" id="menu">
        <ul>
            <li><a href="/dadflip/flipApps/flip/app/user/main.php">Applications</a></li>
            <li><a href="#">Profil</a></li>
            <li><a href="#">Paramètres</a></li>
            <li><a href="#">Aide</a></li>
        </ul>
    </div>


    <!-- Bouton pour revenir à la page précédente -->
    <button class="back-button" onclick="goBack()">Back</button>

    <div class="content">
        <!-- Barre de recherche -->
        <div class="search-bar">
            <div class="banner">
                <h4>Search BOX</h4>
                <h6>v24.001</h6>
            </div>
            <input id="barre-recherche" type="text" placeholder="What is your request?" onkeydown="handleKeyPress(event)">
            <div class="search-icon" onclick="effectuerRecherche()">&#10147;</div>
        </div>

        <!-- Ajout d'une marge pour éviter le chevauchement avec la barre de recherche -->
        <div style="margin-top: 80px;">
            <!-- Zone pour afficher les résultats -->
            <div id="resultats" class="resultats"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Gérer la saisie dans la barre de recherche
            $('#barre-recherche').on('input', function () {
                // Fermer le menu lorsque du texte est saisi dans la barre de recherche
                $('#menu').hide();
            });
        });

        // Fonction pour mettre en surbrillance le texte de recherche dans les résultats
        function mettreEnSurbrillance(texteRecherche, contenu) {
            // Créer une expression régulière pour rechercher le texte de recherche de manière insensible à la casse
            var regex = new RegExp('(' + escapeRegex(texteRecherche) + ')', 'ig');

            // Remplacer le texte correspondant par le même texte enveloppé de balises <span> pour la surbrillance
            var resultatSurligne = contenu.replace(regex, '<span class="surligne">$1</span>');

            return resultatSurligne;
        }

        // Fonction pour échapper les caractères spéciaux dans une expression régulière
        function escapeRegex(str) {
            return str.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        }

        // Fonction pour effectuer la recherche AJAX
        function effectuerRecherche() {
            // Récupérer le terme de recherche depuis la barre de recherche
            var termeRecherche = $('#barre-recherche').val();

            // Afficher le logo de chargement et ajuster sa position et sa taille
            var $loadingIcon = $('.loading-icon');
            var $searchBar = $('.search-bar');

            if (termeRecherche.trim() !== '') {
                // Si la barre de recherche n'est pas vide
                $loadingIcon.hide();
                
                // Modifier l'overlay (ajuster la couleur, etc.)
                $('.overlay-menu').css({
                    backgroundColor: '#333333',
                    // Autres styles de l'overlay
                });
            } else {
                // Si la barre de recherche est vide, afficher l'icône centrée
                $loadingIcon.show();
                $loadingIcon.css({
                    position: 'fixed',
                    top: '30%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    zIndex: 4
                });

                $searchBar.css('border-radius', '20px'); // Ajuster la bordure de la barre de recherche
                // Réinitialiser l'overlay à sa forme initiale
                $('.overlay-menu').css({
                    backgroundColor: '#000000',
                    // Autres styles de l'overlay
                });
            }

            // Effectuer la requête AJAX vers le script de recherche (recherche.php)
            $.ajax({
                url: 'recherche.php',
                type: 'GET',
                data: { q: termeRecherche },
                dataType: 'json',
                success: function (resultats) {
                    // Afficher les résultats dynamiquement avec surbrillance
                    afficherResultatsAvecSurbrillance(resultats, termeRecherche);
                },
                error: function () {
                    console.error('Erreur lors de la requête AJAX');
                }
            });
        }

        // Fonction pour afficher les résultats avec surbrillance
        function afficherResultatsAvecSurbrillance(resultats, termeRecherche) {
            // Effacer les résultats précédents
            $('#resultats').empty();

            // Afficher les nouveaux résultats dans des flipboxes
            for (var i = 0; i < resultats.length; i++) {
                var resultat = resultats[i];

                // Mettre en surbrillance le titre et le contenu
                var titreSurligne = mettreEnSurbrillance(termeRecherche, resultat.title);
                var contenuSurligne = mettreEnSurbrillance(termeRecherche, resultat.content);

                var flipbox = '<div class="flip-box">';
                flipbox += '<div class="flip-box-inner">';
                flipbox += '<div class="flip-box-front">';
                flipbox += '<h3>' + titreSurligne + '</h3>';
                flipbox += '</div>';
                flipbox += '<div class="flip-box-back">';
                flipbox += '<p>' + contenuSurligne + '</p>';
                flipbox += '</div>';
                flipbox += '</div>';
                flipbox += '</div>';

                $('#resultats').append(flipbox);
            }
        }

        // Attacher la fonction de recherche à l'événement de saisie dans la barre de recherche
        $('#barre-recherche').on('input', function () {
            // Déclencher la recherche après un léger délai (par exemple, 300 ms) pour éviter une recherche à chaque frappe
            clearTimeout(window.rechercheTimeout);
            window.rechercheTimeout = setTimeout(effectuerRecherche, 300);
        });

        // Fonction pour gérer la touche "Enter"
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                // Appeler la fonction effectuerRecherche lorsque la touche "Enter" est pressée
                effectuerRecherche();
            }
        }

        // Fonction pour revenir à la page précédente
        function goBack() {
            window.history.back();
        }

        function toggleMenu() {
            var menu = document.getElementById('menu');
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    </script>
    <footer>
        <p> &copy; Dadflip Solutions 2024</p>
    </footer>
</body>

</html>
