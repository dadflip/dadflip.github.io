<?php
// recherche.php

// Simule une liste de textes avec des mots-clés associés
$textes = [
    ['title' => 'Guide de déménagement', 'category' => 'Particulier', 'content' => 'Conseils et astuces pour un déménagement réussi.', 'keywords' => ['déménagement', 'conseils']],
    ['title' => 'Idées pour organiser un anniversaire', 'category' => 'Particulier', 'content' => "Inspiration pour la planification d'une fête d\'anniversaire mémorable.", 'keywords' => ['anniversaire', 'organiser']],
    ['title' => 'Optimisation de la productivité', 'category' => 'Entreprise', 'content' => 'Stratégies pour augmenter l\'efficacité et la productivité en entreprise.', 'keywords' => ['productivité', 'stratégies']],
    ['title' => 'Recettes faciles pour tous les jours', 'category' => 'Cuisine', 'content' => 'Idées de plats simples et délicieux à préparer au quotidien.', 'keywords' => ['recettes', 'cuisine']],
    ['title' => 'Conseils de voyage économiques', 'category' => 'Voyage', 'content' => 'Astuces pour voyager à moindre coût sans compromettre l\'expérience.', 'keywords' => ['voyage', 'économique']],
    ['title' => 'Guide de photographie pour débutants', 'category' => 'Photographie', 'content' => 'Introduction aux bases de la photographie pour les débutants.', 'keywords' => ['photographie', 'guide']],
    ['title' => 'Exercices de fitness à domicile', 'category' => 'Fitness', 'content' => 'Entraînements simples que vous pouvez faire dans le confort de votre maison.', 'keywords' => ['fitness', 'exercices']],
    ['title' => 'Conseils de jardinage pour débutants', 'category' => 'Jardinage', 'content' => 'Introduction aux principes de base du jardinage pour les novices.', 'keywords' => ['jardinage', 'conseils']],
    ['title' => 'Actualités technologiques', 'category' => 'Technologie', 'content' => 'Dernières informations sur les avancées technologiques et les gadgets.', 'keywords' => ['technologie', 'actualités']],
    ['title' => 'Tendances mode printemps-été', 'category' => 'Mode', 'content' => 'Découvrez les dernières tendances en matière de mode pour la saison.', 'keywords' => ['mode', 'tendances']],
    ['title' => 'Conseils financiers pour épargner', 'category' => 'Finance', 'content' => 'Stratégies pour économiser de l\'argent et gérer vos finances efficacement.', 'keywords' => ['finance', 'conseils']],
    ['title' => 'Solutions pour un sommeil de qualité', 'category' => 'Bien-être', 'content' => 'Conseils et produits pour améliorer la qualité de votre sommeil.', 'keywords' => ['bien-être', 'sommeil']],
    ['title' => 'Recettes de smoothies santé', 'category' => 'Nutrition', 'content' => 'Smoothies délicieux et nutritifs pour maintenir une alimentation équilibrée.', 'keywords' => ['nutrition', 'smoothies']],
    ['title' => 'Conseils de maquillage pour débutants', 'category' => 'Beauté', 'content' => 'Introduction aux techniques de maquillage pour les débutants.', 'keywords' => ['maquillage', 'conseils']],
    ['title' => 'Nouvelles tendances en décoration intérieure', 'category' => 'Décoration', 'content' => 'Découvrez les dernières tendances pour embellir votre espace intérieur.', 'keywords' => ['décoration', 'tendances']],
    ['title' => 'Guide de rédaction de CV', 'category' => 'Emploi', 'content' => 'Conseils et modèles pour rédiger un curriculum vitae efficace.', 'keywords' => ['emploi', 'CV']],
    ['title' => 'Conseils de sécurité en ligne', 'category' => 'Internet', 'content' => 'Mesures à prendre pour assurer votre sécurité lors de la navigation en ligne.', 'keywords' => ['internet', 'sécurité']],
    ['title' => 'Musique relaxante pour la méditation', 'category' => 'Méditation', 'content' => 'Sélection de musique apaisante pour accompagner vos séances de méditation.', 'keywords' => ['méditation', 'musique']],
    ['title' => "Routines d'entraînement pour tous niveaux", 'category' => 'Fitness', 'content' => 'Entraînements adaptés à différents niveaux de forme physique.', 'keywords' => ['fitness', 'routines']],
    ['title' => 'Guide de voyage solo', 'category' => 'Voyage', 'content' => 'Conseils et destinations recommandées pour les voyageurs en solo.', 'keywords' => ['voyage', 'solo']],
    // ... Ajoutez autant de textes que nécessaire
];

// Récupère le terme de recherche depuis la barre de recherche
$searchTerm = isset($_GET['q']) ? strtolower($_GET['q']) : '';

// Initialise un tableau pour les résultats de la recherche
$searchResults = [];

// Vérifie si le terme de recherche est vide
if (!empty($searchTerm)) {
    // Parcourt les textes et ajoute ceux qui contiennent le terme de recherche aux résultats
    foreach ($textes as $texte) {
        // Combine tous les mots-clés en une seule chaîne pour faciliter la recherche
        $allKeywords = implode(' ', $texte['keywords']);
        
        // Vérifie si le terme de recherche est présent dans le titre, la catégorie ou le contenu du texte
        if (
            stripos(strtolower($texte['title']), $searchTerm) !== false ||
            stripos(strtolower($texte['category']), $searchTerm) !== false ||
            stripos(strtolower($texte['content']), $searchTerm) !== false ||
            stripos($allKeywords, $searchTerm) !== false
        ) {
            $searchResults[] = $texte;
        }
    }
}

// Retourne les résultats au format JSON
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
