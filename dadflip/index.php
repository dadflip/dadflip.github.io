<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome on Dadflip App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            font-size: 20px;
        }

        .loader img {
            width: 50%;
            margin-bottom: 20px; /* Espacement en bas de l'image */
            animation: rotateImage 2s linear infinite; /* Ajout de l'animation */
        }

        .loader i {
            font-size: 30px; /* Taille de l'icône */
            margin-bottom: 20px; /* Espacement en bas de l'icône */
        }

        .loader p {
            font-size: 24px; /* Taille du texte avec les emojis */
        }

        @keyframes rotateImage {
            to {
                transform: perspective(500px) rotateY(360deg); /* Effet de perspective et de rotation */
            }
        }

        /* ... Vos styles CSS existants ... */
    </style>
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
                window.location.href = "/dadflip/flipApps/flip/app/user/main.php";
            }, 1500);
        }, 200);
    </script>
</body>

</html>
