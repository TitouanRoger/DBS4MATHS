<?php
// Fichier compteur 
$fichier = "viewstats.txt";

// Lire la valeur actuelle
if (file_exists($fichier)) {
    $compteur = (int) file_get_contents($fichier);
} else {
    $compteur = 0;
}

// Incrémenter
$compteur++;

// Sauvegarder la nouvelle valeur
file_put_contents($fichier, $compteur);

// Redirection vers la page principale
header("Location: ./");
exit;
?>