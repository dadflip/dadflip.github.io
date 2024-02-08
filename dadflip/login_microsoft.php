<?php
// Redirection vers l'URL d'authentification Microsoft
$microsoftAuthUrl = 'URL_d_authentification_Microsoft';
header('Location: ' . $microsoftAuthUrl);
exit;
?>
