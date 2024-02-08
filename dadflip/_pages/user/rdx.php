<?php
// rdx.php

// Connexion à la base de données (assurez-vous de configurer les informations de connexion)
$connexion = new mysqli("localhost", "nom_utilisateur", "mot_de_passe", "nom_base_de_donnees");

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}

// Déchiffrer le texte reçu du client (utilisez la même clé secrète utilisée côté client)
$encryptedText = $_POST['q'];
$key = 'clé_secrète';

// Déchiffrer en utilisant OpenSSL (AES-256-CBC)
$ivSize = openssl_cipher_iv_length('aes-256-cbc');
$iv = substr($encryptedText, 0, $ivSize);
$encryptedText = substr($encryptedText, $ivSize);

$texteDechiffre = openssl_decrypt($encryptedText, 'aes-256-cbc', $key, 0, $iv);

// Utiliser $texteDechiffre dans votre logique de traitement
// Par exemple, vous pouvez l'utiliser comme terme de recherche
$termeRecherche = $texteDechiffre;

// Enregistrer le texte dans la base de données
$requeteInsertion = "INSERT INTO messages (contenu) VALUES ('$termeRecherche')";
$resultatInsertion = $connexion->query($requeteInsertion);

// Vérifier si l'insertion a réussi
if (!$resultatInsertion) {
    die("Échec de l'insertion dans la base de données : " . $connexion->error);
}

// Fermer la connexion à la base de données
$connexion->close();

// Charger les mots-clés à partir du fichier texte
$motsCles = [];
$fichierMotsCles = "mots_cles.txt";

if (file_exists($fichierMotsCles)) {
    $contenuFichier = file_get_contents($fichierMotsCles);
    $lignes = explode("\n", $contenuFichier);

    foreach ($lignes as $ligne) {
        $mots = explode(" ", $ligne);
        $categorie = array_shift($mots); // La première partie est la catégorie
        $motsCles[$categorie] = $mots;
    }
}

// Analyser le texte pour trouver la catégorie correspondante
$categorieTrouvee = '';
foreach ($motsCles as $categorie => $mots) {
    foreach ($mots as $mot) {
        if (stripos($termeRecherche, $mot) !== false) {
            $categorieTrouvee = $categorie;
            break 2; // Sortir des deux boucles
        }
    }
}

// Retourner les résultats au format JSON
header('Content-Type: application/json');
echo json_encode(['categorie' => $categorieTrouvee]);
?>
