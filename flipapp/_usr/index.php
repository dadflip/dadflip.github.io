<?php
// Définir l'include path avec un chemin absolu
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\flipapp\_assets\php');

// Inclure le fichier '_database_include.php'
include('x_flipapp_include_db.php');
include('x_flipapp_include_vars.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome on Dadflip App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/flipapp/_assets/css/index.css">
</head>

<body>
    
    <div class="loader" id="loader">
        <img src="/dadflip/assets/img/logo/1-removebg-preview.png" alt="DADFLIP." width="50%"><br>
        <i class="fas fa-circle-notch fa-spin"></i>
        <p>
            🌐 <!-- Globe emoji -->
            🚀 <!-- Rocket emoji -->
            🔄 <!-- Refresh emoji -->
            🌟 <!-- Star emoji -->
            🎉 <!-- Celebration emoji -->
            🤖 <!-- Robot emoji -->
        </p>
    </div>

    <script>
        // Afficher le loader après 1 seconde
        setTimeout(function() {
            document.getElementById("loader").style.display = "block";
            // Rediriger vers une autre page après 1 seconde supplémentaire
            setTimeout(function() {
                window.location.href = "app.php";
            }, 1500);
        }, 200);
    </script>
</body>

</html>
