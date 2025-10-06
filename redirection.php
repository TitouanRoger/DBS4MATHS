<?php
$fichier = "viewstats.txt";
$aujourdhui = date('Y-m-d');

// Initialiser les compteurs
$stats_jours = [];

// Lire le fichier existant
if (file_exists($fichier)) {
    $lignes = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    for ($i = 1; $i < count($lignes); $i++) {
        [$jour, $compte] = explode(':', $lignes[$i]);
        $stats_jours[$jour] = (int) $compte;
    }
}

// Incrémenter le compteur du jour
if (isset($stats_jours[$aujourdhui])) {
    $stats_jours[$aujourdhui]++;
} else {
    $stats_jours[$aujourdhui] = 1;
}

// Trier les jours du plus récent au plus ancien
krsort($stats_jours);

// Recalculer le total à partir des stats journalières
$compteur_total = array_sum($stats_jours);

// Préparer le contenu à sauvegarder
$contenu = "total:{$compteur_total}\n";
foreach ($stats_jours as $jour => $compte) {
    $contenu .= "{$jour}:{$compte}\n";
}

// Sauvegarder dans le fichier
file_put_contents($fichier, $contenu);

// Redirection vers la page principale
header("Location: ./");
exit;
