<?php
// Démarrez ou reprenez la session
session_start();

// Détruisez toutes les variables de session
session_unset();

// Détruisez la session
session_destroy();

// Redirigez l'utilisateur vers la page de connexion
header("Location: login.php");
exit();
?>
