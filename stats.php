<?php
// Lecture du total de vues
$fichier = "viewstats.txt";
$compteur_total = 0;
if (file_exists($fichier)) {
    $lignes = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) {
        if (preg_match('/^total:(\d+)/', $ligne, $matches)) {
            $compteur_total = (int) $matches[1];
            break;
        }
    }
}
echo "<div style='text-align:center;margin:20px;'><strong>Nombre total de visites :</strong> $compteur_total</div>";

// Fonction pour lire les stats totales par page
function lireStatsPagesTotal()
{
    $fichier = __DIR__ . '/stats.txt';
    $pages = [];
    if (file_exists($fichier)) {
        $lines = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/^total_(.+):(\d+)/', $line, $matches)) {
                $pages[$matches[1]] = (int) $matches[2];
            }
        }
    }
    return $pages;
}

// Fonction pour lire les dates disponibles
function lireDates()
{
    $fichier = __DIR__ . '/viewstats.txt';
    $dates = [];
    if (file_exists($fichier)) {
        $lines = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/^(\d{4}-\d{2}-\d{2}):(\d+)/', $line, $matches)) {
                $dates[$matches[1]] = (int) $matches[2];
            }
        }
    }
    return $dates;
}

// Fonction pour lire les stats par page pour une date donnée
function lireStatsPagesParDate($date)
{
    $fichier = __DIR__ . '/stats.txt';
    $pages = [];
    if (file_exists($fichier)) {
        $lines = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match("/^page_(.+)_{$date}:(\d+)/", $line, $matches)) {
                $pages[$matches[1]] = (int) $matches[2];
            }
        }
    }
    return $pages;
}

// Fonction utilitaire pour générer une palette de couleurs dynamiquement
function genererCouleurs($nombre)
{
    $couleurs = [];
    for ($i = 0; $i < $nombre; $i++) {
        $angle = 360 * $i / $nombre;
        $couleurs[] = "hsl($angle, 70%, 60%)";
    }
    return $couleurs;
}

// Affichage du graphique camembert total
$statsPagesTotal = lireStatsPagesTotal();
if (!empty($statsPagesTotal)) {
    $labels = json_encode(array_keys($statsPagesTotal));
    $data = json_encode(array_values($statsPagesTotal));
    $couleursTotal = json_encode(genererCouleurs(count($statsPagesTotal)));
    ?>
    <div style="max-width:400px;margin:20px auto;">
        <strong>Répartition totale des vues par page :</strong>
        <canvas id="statsPieChartTotal"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxTotal = document.getElementById('statsPieChartTotal').getContext('2d');
        new Chart(ctxTotal, {
            type: 'pie',
            data: {
                labels: <?php echo $labels; ?>,
                datasets: [{
                    data: <?php echo $data; ?>,
                    backgroundColor: <?php echo $couleursTotal; ?>
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
    <?php
} else {
    echo "<div style='text-align:center;margin:20px;'>Aucune donnée.</div>";
}

// Fonction pour lire les dates dans stats.txt
function lireDatesStatsTxt()
{
    $fichier = __DIR__ . '/stats.txt';
    $dates = [];
    if (file_exists($fichier)) {
        $lines = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/^page_.+_(\d{4}-\d{2}-\d{2}):\d+/', $line, $matches)) {
                $dates[$matches[1]] = true;
            }
        }
    }
    return array_keys($dates);
}

// Fusionner les dates des deux fichiers
$datesViewStats = array_keys(lireDates());
$datesStatsTxt = lireDatesStatsTxt();
$allDates = array_unique(array_merge($datesViewStats, $datesStatsTxt));
sort($allDates);

// Sélection de la date
$selectedDate = $_GET['date'] ?? (in_array(date('Y-m-d'), $allDates) ? date('Y-m-d') : ($allDates ? $allDates[count($allDates) - 1] : null));

// Plage min/max pour le calendrier
if (!empty($allDates)) {
    $minDate = min($allDates);
    $maxDate = max($allDates);
} else {
    $minDate = date('Y-m-d');
    $maxDate = date('Y-m-d');
}

// Affichage du sélecteur de date
echo "<form method='get' style='text-align:center;margin:20px;'>";
echo "<label for='date'>Sélectionnez une date :</label> ";
echo "<input type='date' name='date' id='date' value='" . htmlspecialchars($selectedDate) . "' min='$minDate' max='$maxDate' onchange='this.form.submit()'>";
echo "<datalist id='datesList'>";
foreach ($allDates as $date) {
    echo "<option value='$date'>";
}
echo "</datalist>";
echo "</form>";

// Affichage des stats pour la date sélectionnée
if ($selectedDate) {
    $nbVuesDate = 0 + $dates[$selectedDate];
    echo "<div style='text-align:center;margin:20px;'><strong>Nombre de visites pour le $selectedDate :</strong> $nbVuesDate</div>";

    $statsPagesDate = lireStatsPagesParDate($selectedDate);
    if (!empty($statsPagesDate)) {
        $labelsDate = json_encode(array_keys($statsPagesDate));
        $dataDate = json_encode(array_values($statsPagesDate));
        $couleursDate = json_encode(genererCouleurs(count($statsPagesDate)));
        ?>
        <div style="max-width:400px;margin:20px auto;">
            <strong>Répartition des vues par page pour le <?php echo $selectedDate; ?> :</strong>
            <canvas id="statsPieChartDate"></canvas>
        </div>
        <script>
            const ctxDate = document.getElementById('statsPieChartDate').getContext('2d');
            new Chart(ctxDate, {
                type: 'pie',
                data: {
                    labels: <?php echo $labelsDate; ?>,
                    datasets: [{
                        data: <?php echo $dataDate; ?>,
                        backgroundColor: <?php echo $couleursDate; ?>
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        </script>
        <?php
    } else {
        echo "<div style='text-align:center;margin:20px;'>Aucune donnée pour cette date.</div>";
    }
}
?>