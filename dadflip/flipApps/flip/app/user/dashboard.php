<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /dadflip/");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Récupérer les modules de l'utilisateur depuis la base de données
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM modules_utilisateur WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <!-- Inclure jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.gridster/0.5.6/jquery.gridster.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-5sArzRZldQWlnEea6z5uVVpFzYFdAZCeHMPtSHK4vzL6yI3c5pD24FccsuL13f1ibJ6N56lL1pyR1N5OUwqY6+g==" crossorigin="anonymous" />


    <style>
        .toggle-panel-btn {
            position: fixed;
            top: 10%;
            right: 2%;
            background-color: #007700;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-size: 18px;
            cursor: pointer;
        }

        .module-thumbnails-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .module-thumbnail {
            width: 50px; /* Ajustez la largeur des miniatures selon vos besoins */
            height: 50px; /* Ajustez la hauteur des miniatures selon vos besoins */
            border-radius: 5px;
            margin: 5px;
        }

        #moduleThumbnail {
            width: 50px; /* Ajustez la largeur de la miniature selon vos besoins */
            height: 50px; /* Ajustez la hauteur de la miniature selon vos besoins */
            border-radius: 5px;
            margin-left: 10px; /* Ajoutez une marge à gauche pour l'espacement */
        }


        .user-info {
            color: #fff;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: blue;
        }

        a.no-underline {
            text-decoration: none;
        }

        .navbar {
            background-color: #000;
            padding: 15px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            box-shadow: 0px 5px 15px 0px rgba(0,0,0,0.3); /* Ajout d'une ombre */
        }

        .navbar a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 80px 20px 20px;
            overflow: hidden;
            background-color: #f0f0f0;
            background-image: url('modules/bg/8.png');
            background-repeat: no-repeat; /* Éviter la répétition */
            background-size: cover; /
        }

        .module {
            background-color: #fff;
            color: #000;
            padding: 20px;
            margin: 10px;
            width: 50%;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
            overflow: hidden;
        }

        .inner-module {
            background-color: #fff;
            color: #000;
            padding: 20px;
            margin: 10px;
            width: auto; /* Ajustez la largeur selon vos besoins */
            height: 90%; /* Ajustez la hauteur selon vos besoins */
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
            overflow: hidden;
        }


        .module, .inner-module {
            position: relative;
        }

        iframe {
            overflow: hidden;
        }

        /* Style pour l'élément input redimensionnable */
        .resizable-input {
            width: 100%; /* Largeur initiale à 100% de son conteneur */
            box-sizing: border-box; /* La largeur inclut le padding et la bordure */
            padding: 10px; /* Ajoutez le padding souhaité */
            margin-bottom: 10px; /* Ajoutez une marge en bas pour l'espacement */
        }


        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px; /* Ajout d'une marge basse */
            overflow: hidden;
        }

        .bottom-right-container {
            position: fixed;
            bottom: 10px;
            right: 10px;
            padding: 20px;
            background-color: #fff; /* Couleur de fond du cadre */
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5); /* Effet d'ombre */
            border-radius: 10px; /* Bord arrondi du cadre */
            height: 40%;
            width: 20%;
        }

        /* Styles pour le quadrillage */
        .gridster {
            width: 100%;
            height: 100%;
        }

        .gridster ul {
            width: 100%;
            height: 100%;
        }

        .gridster li {
            background-color: #eee;
            border: 1px solid #ddd;
        }

        label, select {
            margin-bottom: 10px;
            font-size: 18px;
            color: #000000;
        }

        select {
            padding: 10px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #222;
            color: #ffffff;
            width: 80%;
        }

        button {
            background-color: #007700;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            overflow: hidden;
            width: 80%;
        }

        button:hover {
            background-color: #005500;
        }

        button.mod-close-btn {
            background-color: red;
            color: #000;
            text-align: center;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            overflow: hidden;
            width: 10%;
        }

        :root {
            --background-color: #fff;
            --text-color: #000;
        }
    </style>
</head>

<body>
    <!-- Menu de navigation flottant -->
    <div class="navbar">
        <div class="user-info">
            <?php
            // Connexion à la base de données (à adapter selon votre configuration)
            require_once 'db.php';

            // Vérification de la session et récupération des informations de l'utilisateur depuis la base de données
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                try {
                    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("SELECT username FROM utilisateurs WHERE id = :user_id");
                    $stmt->bindParam(':user_id', $userId);
                    $stmt->execute();

                    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $username = $user['username'];
                        echo '<script>var username = "' . $username . '";</script>';
                    }
                } catch (PDOException $e) {
                    echo "Erreur de connexion à la base de données: " . $e->getMessage();
                }
            }
            ?>
        </div>
        <div class="options">
            <a href="#">MY</a>
            <a href="#">BOUTIQUE</a>
            <a href="/dadflip/flipApps/flip/app/user/main.php">APPLICATIONS</a>
            <!-- Ajoutez d'autres options au besoin -->
        </div>
    </div>

    <h1>Tableau de bord</h1>
    <div class="dashboard">
        <?php if (!empty($modules)) : ?>
            <?php foreach ($modules as $module) : ?>
                <div class="module" data-module-id="<?= $module['id'] ?>">
                    <!-- Ajoutez une croix pour supprimer le module -->
                    <form action="manage_modules.php" method="post">
                        <input type="hidden" name="removeModule" value="<?= $module['id'] ?>">
                        <button class="mod-close-btn" type="submit" name="action" value="remove"><i class="fas fa-cog">✖</i></button>
                    </form>

                    <h2><?= strtoupper($module['module_name']) ?></h2>

                    <?php if ($module['module_name'] === 'agenda') : ?>
                        <div class="inner-module" id="agendaModule">
                            <!-- Afficher ici le contenu de l'agenda -->
                            <iframe src="modules/agenda.php" style="border: 0; width: 100%; height: 100%;" frameborder="0"></iframe>
                        </div>
                    <?php elseif ($module['module_name'] === 'todolist') : ?>
                        <div class="inner-module" id="todoListModule">
                            <a class="no-underline" href="/dadflip/flipApps/flip/app/user/modules/todolist.php">
                                <button>Ouvrir</button>
                            </a>
                            <!-- Afficher ici le contenu de la to-do list -->
                            <iframe src="modules/todolist.php" style="border: 0; width: 100%; height: 100%;" frameborder="0"></iframe>
                        </div>
                    <?php elseif ($module['module_name'] === 'panier') : ?>
                        <div class="inner-module" id="shoppingCartModule">
                            <!-- Afficher ici le contenu du panier -->
                            <iframe src="modules/panier.php" style="border: 0; width: 100%; height: 100%;" frameborder="0"></iframe>
                        </div>
                    <?php elseif ($module['module_name'] === 'gRDX') : ?>
                        <div class="inner-module" id="gRDXModule">
                            <a class="no-underline" href="/dadflip/_pages/user/main.html">
                                <button>Ouvrir</button>
                            </a>
                            <!-- Afficher ici le contenu de gRDX -->
                            <iframe src="/dadflip/_pages/user/main.html" style="border: 0; width: 100%; height: 100%;" frameborder="0"></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun module disponible.</p>
        <?php endif; ?>
    </div>

    <!-- Ajoutez le bouton circulaire avec une icône -->
    <div class="toggle-panel-btn" id="togglePanelBtn">
        <i class="fas fa-cog">☰</i>
    </div>


    <div class="bottom-right-container">
        <!-- Formulaire pour ajouter un module -->
        <form action="manage_modules.php" method="post" id="addModuleForm">
            <!-- Ajoutez une image miniature ici -->
            <img src="/dadflip/assets/img/default.png" alt="Miniature" id="moduleThumbnail">

            <label for="addModule">Ajouter un module :</label>
            <select name="addModule" id="addModule" onchange="updateThumbnail()">
                <option value="todolist">To-Do List</option>
                <option value="agenda">Agenda</option>
                <option value="panier">Panier</option>
                <option value="gRDX">Geany RDX</option>
                <option value="flipApp">Flip App</option>
                <!-- Ajouter d'autres options de modules ici -->
            </select>

            <button type="submit" name="action" value="add">Ajouter</button><br>
        </form>

        <form action="manage_modules.php" method="post">
            <label for="removeModule">Retirer un module :</label>
            <select name="removeModule" id="removeModule">
                <?php foreach ($modules as $module) : ?>
                    <option value="<?= $module['id'] ?>"><?= $module['module_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="action" value="remove">Retirer</button>
        </form>

        <script>
            function updateThumbnail() {
                var selectedModule = document.getElementById('addModule').value;
                var thumbnailUrl = "/dadflip/assets/img/main/" + selectedModule + ".png";
                document.getElementById('moduleThumbnail').src = thumbnailUrl;
            }

            // Appeler la fonction updateThumbnail lors du chargement initial
            document.addEventListener('DOMContentLoaded', function () {
                updateThumbnail();
            });
        </script>
    </div>

    
    <script>
        // Redimensionner l'élément input en fonction de la taille de la fenêtre
        window.addEventListener('resize', function() {
            var inputElement = document.querySelector('.resizable-input');
            var windowWidth = window.innerWidth;

            // Vous pouvez ajuster le facteur multiplicatif selon vos besoins
            var newWidth = windowWidth * 0.8; 

            // Limitez la largeur minimale et maximale si nécessaire
            var minWidth = 50;
            var maxWidth = 100;

            // Appliquer la nouvelle largeur à l'élément input
            inputElement.style.width = Math.max(minWidth, Math.min(newWidth, maxWidth)) + '%';
        });

        // Afficher les informations de l'utilisateur dans le menu
        document.querySelector('.user-info').innerHTML = 'Bienvenue, ' + username;
    </script>

    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Inclure jQuery UI après jQuery -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Inclure le plugin Gridster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.gridster/0.5.6/jquery.gridster.min.js"></script>
    

    <!-- Votre script personnalisé -->
    <script>
        // Initialiser Gridster
        $(function () {
            // Gérez l'affichage/masquage du panel avec le bouton circulaire
            $('#togglePanelBtn').on('click', function() {
                $('.bottom-right-container').toggle();
                $('.module-thumbnails-container').toggle(); // Ajoutez cette ligne
            });

            // Ajoutez une miniature pour chaque module
            $('.module').each(function() {
                var moduleId = $(this).data('module-id');
                var thumbnailUrl = '/dadflip/assets/img/main/' + moduleId; // Remplacez par la vraie URL
                console.log(thumbnailUrl);
                $(this).append('<img src="' + thumbnailUrl + '" alt="Miniature du module" class="module-thumbnail">');
            });

            // Rendre les modules redimensionnables et déplaçables
            $(".module").resizable({
                handles: 'n, e, s, w, ne, se, sw, nw',
                containment: '.dashboard', // Centrer par rapport au document
                start: function (event, ui) {
                    // Code à exécuter au début du redimensionnement
                },
                stop: function (event, ui) {
                    // Code à exécuter à la fin du redimensionnement
                    saveModuleDimensions(ui.helper);
                }
            });//.draggable();

            $(".inner-module").resizable({
                handles: 'n, e, s, w, ne, se, sw, nw',
                //containment: 'document', // Centrer à gauche par rapport au document
                start: function (event, ui) {
                    // Code à exécuter au début du redimensionnement
                },
                stop: function (event, ui) {
                    // Code à exécuter à la fin du redimensionnement
                    saveModuleDimensions(ui.helper);
                }
            });

            var dashboard = $(".dashboard");

            // Écoutez l'événement de défilement
            $(window).scroll(function() {
                // Calculez la position de la moitié de la page
                var halfPage = $(window).scrollTop() + $(window).height() / 2;

                // Si la position actuelle est supérieure à 50 % de la page, commencez le défilement
                if (halfPage > $(window).height() * 0.5) {
                    var scrollHeight = $(document).height() - $(window).height();
                    var newScrollTop = scrollHeight - $(window).scrollTop();

                    // Appliquez le défilement à la .dashboard
                    dashboard.animate({ scrollTop: newScrollTop }, 500);

                    // Détachez l'événement de défilement une fois qu'il a été déclenché
                    $(window).off("scroll");
                }
            });

            // Rendez la .dashboard scrollable
            dashboard.css("overflow-y", "auto");

            // Appliquez la fonction de redimensionnement à la .dashboard
            dashboard.resizable({
                handles: 'n, e, s, w',
                start: function (event, ui) {
                    // Code à exécuter au début du redimensionnement
                },
                stop: function (event, ui) {
                    // Code à exécuter à la fin du redimensionnement
                    saveModuleDimensions(ui.helper);
                }
            });

            function saveModuleDimensions(module) {
                // Récupérez les informations nécessaires
                var moduleId = module.data("module-id");
                var width = module.width();
                var height = module.height();

                // Récupérez la position du centre de l'élément par rapport au document
                var offset = module.offset();
                var centerX = offset.left + width / 2;
                var centerY = offset.top + height / 2;

                // Effectuez une requête Ajax pour enregistrer les dimensions et la position dans la base de données
                $.ajax({
                    url: 'saveModuleDimensions.php',
                    type: 'POST',
                    data: {
                        moduleId: moduleId,
                        width: width,
                        height: height,
                        centerX: centerX,
                        centerY: centerY
                    },
                    success: function(response) {
                        console.log(moduleId, width, height, centerX, centerY);
                        console.log('Dimensions et position enregistrées avec succès.');
                    },
                    error: function(error) {
                        console.error('Erreur lors de l\'enregistrement des dimensions et de la position: ' + JSON.stringify(error));
                    }
                });
            }

            // Charger les dimensions et les appliquer aux modules
            loadModuleDimensions();

            function loadModuleDimensions() {
                <?php foreach ($modules as $module) : ?>
                    var moduleId = <?= $module['id'] ?>;
                    var width = <?= $module['width'] ?>;
                    var height = <?= $module['height'] ?>;
                    var centerX = <?= $module['centerX'] ?>;
                    var centerY = <?= $module['centerY'] ?>;


                    // Sélectionner le module correspondant par son ID
                    var moduleElement = $(".module[data-module-id='" + moduleId + "']");

                    // Appliquer les dimensions au module
                    moduleElement.width(width);
                    moduleElement.height(height);
                <?php endforeach; ?>
                
            }

        });
    </script>

</body>

</html>
