<?php 
define('BASE_URL', '/dadflip/');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flip Applications</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            overflow: hidden;
            margin: 0;
            background-image: url('bg/img.png'); /* Correction ici */
            background: linear-gradient(80deg, #ffffff, #f0f0f0);
            /* Trois couleurs en dégradé */
            /*background-size: 70%;*/
            /*background-position: center;*/
        }

        h1 {
            color: #ffffff;
            font-size: 50px;
            margin-bottom: 20px;
        }

        /* Style de base pour les liens */
        a {
            text-decoration: none; /* Supprime le soulignement par défaut */
            color: #007BFF; /* Couleur du lien */
            transition: color 0.3s; /* Animation de transition de la couleur */
        }

        /* Effet de survol sur les liens */
        a:hover {
            color: #0056b3; /* Nouvelle couleur au survol */
            text-decoration: none; /* Soulignement au survol */
        }

        /* Style pour les liens visités */
        a:visited {
            color: #6c757d; /* Couleur des liens visités */
        }


        .w3-btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: fixed;
            bottom: 10px;
            right: 20px;
        }

        .w3-btn:hover {
            background-color: #45a049;
        }

        .floating-frame {
            position: relative;
            margin: 10px;
            padding: 30px;
            /* Ajustez la taille des cadres ici */
            background-color: rgba(255, 255, 255, 0.13);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            transform-origin: center bottom;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: none;
            /*background-image: url('https://via.placeholder.com/300'); /* Remplacez 'votre_image.jpg' par le chemin de votre image */
            background-size: cover;
            background-position: center;
            flex: 1;
            max-width: auto;
            /*calc(25% - 20px);*/
            height: 20vh; /* 50% de la hauteur de la fenêtre */
            width: 70vw; /* 50% de la largeur de la fenêtre */ /* Utilisez 'solid' et définissez la couleur après 'solid' */
            /* Limitez à 4 cadres par ligne en tenant compte des marges */
        }

        .floating-frame:hover {
            color: #f0f0f0;
            transform: scale(1.05);
            background-color: #007BFF;
            border: 2px, #007BFF;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        .floating-frame a {
            color: #000000;
            text-decoration: none;
            overflow: hidden;
        }

        #hidden-menu {
            position: fixed;
            left: 0;
            top: 100%;
            width: 100%;
            height: 200%;
            background-color: #111111;
            border: 1px solid, #4CAF50;
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            transition: left 0.3s, top 0.3s; /* Ajout de l'animation de transition */
            z-index: 2;
        }

        #hidden-menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid #555;
            border: 1px solid, #ffffff;
        }

        #hidden-menu a:hover {
            background-color: #000000;
        }

        #toggle-menu-btn {
            transition: background-color 0.3s;
            background-color: #001601;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 5;
        }

        #toggle-menu-btn:hover,
        #close-menu-btn:hover {
            background-color: #45a049;
        }

        #frames-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: stretch;
            /* Permet aux cadres de s'étirer pour occuper la hauteur complète */
            margin-top: 20px;
            padding: 20px;
            box-sizing: border-box;
        }
        


        .w3-btn-carousel {
            margin: 10px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .w3-btn-carousel:hover {
            background-color: #555;
        }

        .banner {
            background-color: #000000;
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* Add a black background color to the top navigation */
        .topnav {
        background-color: #f0f0f0;
        overflow: hidden;
        border-radius: 20px;
        }

        /* Style the links inside the navigation bar */
        .topnav a {
        float: left;
        display: block;
        color: #020202;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
        }

        .topnav a:hover {
        border-bottom: 3px solid green;
        }

        .topnav a.active {
        border-bottom: 3px solid green;
        }

        /* Create a right-aligned (split) link inside the navigation bar */
        .topnav a.split {
        float: right;
        background-color: #013603;
        color: white;
        }

        /* Style the search box */
        #mySearch {
        width: 100%;
        font-size: 18px;
        padding: 11px;
        border: 1px solid #ddd;
        }

        /* Style the navigation menu */
        #myMenu {
        list-style-type: none;
        padding: 0;
        margin: 0;
        }

        /* Style the navigation links */
        #myMenu li a {
        padding: 12px;
        text-decoration: none;
        color: black;
        display: block
        }

        #myMenu li a:hover {
        background-color: #eee;
        }
        
        .bg-img {
        /* The image used */
        background-image: url("/dadflip/assets/img/main/internet.jpg");

        min-height: 380px;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;

        /* Needed to position the navbar */
        position: relative;
        }

        /* Position the navbar container inside the image */
        .container {
        position: absolute;
        margin: 20px;
        width: auto;
        }

        .add-container {
        position: relative;
        margin: 20px;
        width: auto;
        }

        /* Style the header with a grey background and some padding */
        .header {
            overflow: hidden;
            background-color: #f1f1f1;
            padding: 20px 10px;
            top: 0; /* Positionnement en haut de la page */
            z-index: 100; /* Pour s'assurer que la barre de navigation reste au-dessus du contenu */
        }

        /* Style the header links */
        .header a {
        float: left;
        color: black;
        text-align: center;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        line-height: 25px;
        border-radius: 4px;
        }

        /* Style the logo link (notice that we set the same value of line-height and font-size to prevent the header to increase when the font gets bigger */
        .header a.logo {
        font-size: 25px;
        font-weight: bold;
        }

        /* Change the background color on mouse-over */
        .header a:hover {
        background-color: #ddd;
        color: black;
        }

        /* Style the active/current link*/
        .header a.active {
        background-color: green;
        color: white;
        }

        /* Float the link section to the right */
        .header-right {
        float: right;
        }

        /* Add media queries for responsiveness - when the screen is 500px wide or less, stack the links on top of each other */
        @media screen and (max-width: 500px) {
        .header a {
            float: none;
            display: block;
            text-align: left;
        }
        .header-right {
            float: none;
        }
        }

        .main-header{
            z-index: 1000;
        }

        .user-section {
            transition: transform 0.3s; /* Ajout de transition pour une animation fluide */
        }

        .user-section.small {
            transform: scale(0.5); /* Réduire la taille de la section utilisateur lors du défilement */
            transform-origin: top right; /* Centrer à droite */
        }

        /* Masquer la partie gauche du menu lorsque la classe 'small' est appliquée */
        .user-section.small .add-container {
            display: none;
        }

        .user-section.small .topnav a {
            font-size: 200%; /* Ajuster la taille de la police pour compenser la réduction de la section utilisateur */
        }



        /* Style buttons */
        .btn {
        background-color: DodgerBlue;
        border: none;
        color: white;
        padding: 12px 30px;
        cursor: pointer;
        font-size: 20px;
        }

        /* Darker background on mouse-over */
        .btn:hover {
        background-color: RoyalBlue;
        }

        /* The actual timeline (the vertical ruler) */
        .timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        }

        /* The actual timeline (the vertical ruler) */
        .timeline::after {
        content: '';
        position: absolute;
        width: 6px;
        background-color: white;
        top: 0;
        bottom: 0;
        left: 50%;
        margin-left: -3px;
        }

        /* Container around content */
        .container {
        padding: 10px 40px;
        position: relative;
        background-color: inherit;
        width: 50%;
        }

        /* The circles on the timeline */
        .container::after {
        content: '';
        position: absolute;
        width: 25px;
        height: 25px;
        right: -17px;
        background-color: white;
        border: 4px solid #FF9F55;
        top: 15px;
        border-radius: 50%;
        z-index: 1;
        }

        /* Place the container to the left */
        .left {
        left: 0;
        }

        /* Place the container to the right */
        .right {
        left: 50%;
        }

        /* Add arrows to the left container (pointing right) */
        .left::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        right: 30px;
        border: medium solid white;
        border-width: 10px 0 10px 10px;
        border-color: transparent transparent transparent white;
        }

        /* Add arrows to the right container (pointing left) */
        .right::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        left: 30px;
        border: medium solid white;
        border-width: 10px 10px 10px 0;
        border-color: transparent white transparent transparent;
        }

        /* Fix the circle for containers on the right side */
        .right::after {
        left: -16px;
        }

        /* Media queries - Responsive timeline on screens less than 600px wide */
        @media screen and (max-width: 600px) {
        /* Place the timelime to the left */
        .timeline::after {
            left: 31px;
        }

        /* Full-width containers */
        .container {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }

        /* Make sure that all arrows are pointing leftwards */
        .container::before {
            left: 60px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }

        /* Make sure all circles are at the same spot */
        .left::after, .right::after {
            left: 15px;
        }

        /* Make all right containers behave like the left ones */
        .right {
            left: 0%;
        }
        }

        /* The sticky class is added to the header with JS when it reaches its scroll position */
        .sticky {
        position: fixed;
        top: 0;
        width: 100%
        }

        /* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
        .sticky + .content {
        padding-top: 102px;
        }
        
    </style>
</head>

<body>
    <div class="banner">
        <img width="15%" height="15%" class="loading-logo" src="/dadflip/assets/img/logo/2-removebg-preview.png" alt="Custom Loading Icon">
    </div>

    <div class="main-header" id="headerComp">
        <div class="header">
            <a href="/dadflip/" class="logo" onclick="toggleMenu()">DADFLIP.</a>
            <div class="header-right">
                <a class="active" href="<?= BASE_URL ?>index.php">ACCUEIL</a>
                <a href="https://dadflipcorp.wixsite.com/dadflip-solutions">DADFLIP SOLUTIONS</a>
                <a href="<?= BASE_URL ?>contact.html">CONTACT</a>
                <a href="<?= BASE_URL ?>about.php">ABOUT</a>
            </div>
        </div>

        <button id="toggle-menu-btn" class="w3-btn" onclick="toggleMenu()">☰</button>

        <div id="user-section" class="user-section">
            <?php
            require_once 'db.php';

            function connectDatabase() {
                global $db_host, $db_name, $db_user, $db_password;
                $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }
            

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                $pdo = connectDatabase();

                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :user_id");
                $stmt->bindParam(':user_id', $userId);
                $stmt->execute();

                if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="add-container">
                        <div class="topnav">
                            <a href="<?= BASE_URL ?>user-settings.php">PARAMETRES</a>
                            <a href="dashboard.php">DASHBOARD</a>
                            <a href="<?= BASE_URL ?>exit.php">DECONNEXION</a>
                            <a href="#" class="split">
                                <img src="<?= BASE_URL ?>assets/img/logo/1-removebg-preview.png" alt="User Avatar" width="20%">
                                <p>Bienvenue, <?= $user['username'] ?></p>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<a style="color: red; text: size 60 " href="'.BASE_URL.'login.php">Connexion</a>';
            }
            ?>
        </div>
    </div>

    <div id='carousel-container'>
        <div id="frames-container">
            <!-- Cadres ici -->
            <div class='floating-frame animate__animated animate__fadeIn'>
                <a href="/dadflip/flipApps/flipPOP/">
                    <img width="80px" height="80px" src="modules/flip-img/12-removebg-preview.png" alt="Custom Loading Icon">
                    <p>FLIP POP &trade;</p>
                </a>
            </div>
            <div class='floating-frame animate__animated animate__fadeIn'>
                <a href="/dadflip/_pages/user/main.html">
                    <img width="80px" height="80px" src="modules/flip-img/5-removebg-preview.png" alt="Custom Loading Icon">
                    <p>GEANY RDX &trade;</p>
                </a>
            </div>
            <div class='floating-frame animate__animated animate__fadeIn'>
                <a href="/dadflip/flipApps/EDX/main.html">
                    <img width="80px" height="80px" src="modules/flip-img/8-removebg-preview.png" alt="Custom Loading Icon">
                    <p>EDX</p>
                </a>
            </div>

            <div class='floating-frame animate__animated animate__fadeIn'>
                <a href="/dadflip/flipApps/flipapp/app.php">
                    <img width="80px" height="80px" src="modules/flip-img/1-removebg-preview.png" alt="Custom Loading Icon">
                    <p>FLIP</p>
                </a>
            </div>
            <!-- ... Ajoutez autant de cadres que nécessaire ... -->
        </div>
        <br>
        <div id="frames-container">
            <!-- Cadres ici -->
            <div class='floating-frame animate__animated animate__fadeIn'>
                <p>Coming soon ...</p>
            </div>
            <div class='floating-frame animate__animated animate__fadeIn'>
                <p>Coming soon ...</p>
            </div>
            <div class='floating-frame animate__animated animate__fadeIn'>
                <p>Coming soon ...</p>
            </div>
            <div class='floating-frame animate__animated animate__fadeIn'>
                <p>Coming soon ...</p>
            </div>
            <!-- ... Ajoutez autant de cadres que nécessaire ... -->
        </div>
        <br>

        <div class="timeline">
            <div class="container left">
                <div class="content">
                <h2>2017</h2>
                <p>Lorem ipsum..</p>
                </div>
            </div>
            <div class="container right">
                <div class="content">
                <h2>2016</h2>
                <p>Lorem ipsum..</p>
                </div>
            </div>
        </div>



        <input type="text" id="mySearch" onkeyup="searchInList()" placeholder="Search.." title="Type in a category">

        <ul id="myMenu">
        <li><a href="#">HTML</a></li>
        <li><a href="#">CSS</a></li>
        <li><a href="#">JavaScript</a></li>
        <li><a href="#">PHP</a></li>
        <li><a href="#">Python</a></li>
        <li><a href="#">jQuery</a></li>
        <li><a href="#">SQL</a></li>
        <li><a href="#">Bootstrap</a></li>
        <li><a href="#">Node.js</a></li>
        </ul>

        <!-- Full width -->
        <button class="btn" style="width:100%"><i class="fa fa-download"></i> Download</button>
    </div>

    <script>
        function scrollLeft() {
            document.getElementById("frames-container").scrollLeft -= 200;
        }

        function scrollRight() {
            document.getElementById("frames-container").scrollLeft += 200;
        }

        // Fonction pour basculer l'état du menu
        function toggleMenu() {
            var userSection = document.getElementById('user-section');
            userSection.classList.toggle('small'); // Ajoute ou supprime la classe 'small'
        }

        function searchInList() {
            // Declare variables
            var input, filter, ul, li, a, i;
            input = document.getElementById("mySearch");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myMenu");
            li = ul.getElementsByTagName("li");

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
                } else {
                li[i].style.display = "none";
                }
            }
        }

        // When the user scrolls the page, execute myFunction
        window.onscroll = function () { stickyHeader() };

        // Get the header and user section
        var header = document.getElementById("headerComp");
        var userSection = document.getElementById("user-section");

        // Get the offset position of the navbar
        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function stickyHeader() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
                userSection.classList.add("small");
            } else {
                header.classList.remove("sticky");
                userSection.classList.remove("small");
            }
        }
    </script>
</body>

</html>