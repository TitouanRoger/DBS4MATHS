<?php
function genererSudoku($difficulte)
{
    $grille = array_fill(0, 9, array_fill(0, 9, 0));

    genererSolutionValide($grille);

    $solution = array_map(function ($row) {
        return $row; }, $grille);

    $casesAVider = match ($difficulte) {
        'debutant' => 36,
        'facile' => 43,
        'moyen' => 49,
        'difficile' => 55,
        'expert' => 59,
        'maitre' => 64
    };

    $positions = range(0, 80);
    shuffle($positions);
    foreach ($positions as $pos) {
        $row = floor($pos / 9);
        $col = $pos % 9;
        $temp = $grille[$row][$col];
        $grille[$row][$col] = 0;

        if (!aSolutionUnique($grille)) {
            $grille[$row][$col] = $temp;
        } else {
            $casesAVider--;
            if ($casesAVider == 0)
                break;
        }
    }

    return ['grille' => $grille, 'solution' => $solution];
}

function aSolutionUnique($grille)
{
    $copie = array_map(function ($row) {
        return $row; }, $grille);
    return compterSolutions($copie, 0, 0) == 1;
}

function compterSolutions(&$grille, $row, $col)
{
    if ($row == 9) {
        $row = 0;
        if (++$col == 9)
            return 1;
    }
    if ($grille[$row][$col] != 0)
        return compterSolutions($grille, $row + 1, $col);

    $count = 0;
    for ($num = 1; $num <= 9; $num++) {
        if (estValide($grille, $row, $col, $num)) {
            $grille[$row][$col] = $num;
            $count += compterSolutions($grille, $row + 1, $col);
            if ($count > 1)
                break;
            $grille[$row][$col] = 0;
        }
    }
    return $count;
}

function genererSolutionValide(&$grille)
{
    for ($i = 0; $i < 9; $i++) {
        for ($j = 0; $j < 9; $j++) {
            if ($grille[$i][$j] == 0) {
                $chiffres = range(1, 9);
                shuffle($chiffres);
                foreach ($chiffres as $chiffre) {
                    if (estValide($grille, $i, $j, $chiffre)) {
                        $grille[$i][$j] = $chiffre;
                        if ($j == 8 && $i == 8) {
                            return true;
                        }
                        if (genererSolutionValide($grille)) {
                            return true;
                        }
                        $grille[$i][$j] = 0;
                    }
                }
                return false;
            }
        }
    }
    return true;
}

function estValide($grille, $row, $col, $num)
{
    for ($x = 0; $x < 9; $x++) {
        if ($grille[$row][$x] == $num) {
            return false;
        }
    }

    for ($x = 0; $x < 9; $x++) {
        if ($grille[$x][$col] == $num) {
            return false;
        }
    }

    $startRow = $row - $row % 3;
    $startCol = $col - $col % 3;
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($grille[$i + $startRow][$j + $startCol] == $num) {
                return false;
            }
        }
    }

    return true;
}

if (isset($_GET['action']) && $_GET['action'] == 'generer') {
    $difficulte = $_GET['difficulte'] ?? 'facile';
    header('Content-Type: application/json');
    echo json_encode(genererSudoku($difficulte));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudoku</title>
    <link rel="stylesheet" href="css/sudoku.css">
</head>

<body class="sudoku-body">
    <div id="sudoku-game-info" class="sudoku-game-info">
        <div id="sudoku-timer" class="sudoku-timer">Temps: 00:00</div>
        <div id="sudoku-best-time" class="sudoku-best-time">Meilleur temps: --:--</div>
    </div>
    <div id="sudoku-controls" class="sudoku-controls">
        <select id="sudoku-difficulte" class="sudoku-select">
            <option value="debutant">Débutant</option>
            <option value="facile">Facile</option>
            <option value="moyen">Moyen</option>
            <option value="difficile">Difficile</option>
            <option value="expert">Expert</option>
            <option value="maitre">Maître</option>
        </select>
        <button id="sudoku-nouvelle-partie" class="sudoku-button">Nouvelle partie</button>
        <button id="sudoku-sauvegarder" class="sudoku-button">Sauvegarder</button>
        <button id="sudoku-charger" class="sudoku-button">Charger</button>
        <button id="sudoku-indice" class="sudoku-button">Indice (+30s)</button>
        <button id="sudoku-regles" class="sudoku-button">Règles</button>
    </div>
    <div id="sudoku-grid" class="sudoku-grid"></div>
    <br>
    <button id="sudoku-verifier" class="sudoku-button verify-button" disabled>Vérifier</button>

    <div id="sudoku-popup" class="sudoku-popup">
        <div class="sudoku-popup-content">
            <h2 id="sudoku-popup-title"></h2>
            <p id="sudoku-popup-message"></p>
            <div id="sudoku-popup-actions">
                <button id="sudoku-popup-close" class="sudoku-button">OK</button>
            </div>
        </div>
    </div>

    <div id="sudoku-regles-container" class="sudoku-regles-container">
        <div class="sudoku-regles-content">
            <h2>Règles du Sudoku</h2>
            <p>Le Sudoku est un jeu de réflexion où vous devez remplir une grille de 9x9 avec des chiffres de 1 à 9.</p>
            <p>Chaque ligne, colonne et carré 3x3 doit contenir chaque chiffre une seule fois.</p>
            <button id="sudoku-regles-close" class="sudoku-button">Fermer</button>
        </div>
    </div>

    <div id="sudoku-loading" class="sudoku-loading">
        <div class="sudoku-loading-content">
            <p>Génération de la grille en cours...</p>
            <div class="sudoku-spinner"></div>
        </div>
    </div>

    <script>
        let solution;
        let timer;
        let secondsElapsed = 0;
        let bestTimes = JSON.parse(localStorage.getItem('sudokuBestTimes')) || {};
        let currentDifficulty;

        function showMessage(title, message) {
            document.getElementById('sudoku-popup-title').textContent = title;
            document.getElementById('sudoku-popup-message').textContent = message;
            document.getElementById('sudoku-popup').style.display = 'flex';
        }

        document.getElementById('sudoku-popup-close').addEventListener('click', function () {
            document.getElementById('sudoku-popup').style.display = 'none';
        });

        function creerGrille(sudoku) {
            const grid = document.getElementById('sudoku-grid');
            grid.innerHTML = '';
            const verifierButton = document.getElementById('sudoku-verifier');
            verifierButton.disabled = true;

            for (let i = 0; i < 9; i++) {
                for (let j = 0; j < 9; j++) {
                    const cell = document.createElement('input');
                    cell.type = 'text';
                    cell.maxLength = 1;
                    cell.dataset.row = i;
                    cell.dataset.col = j;

                    if (sudoku[i][j] !== 0) {
                        cell.value = sudoku[i][j];
                        cell.readOnly = true;
                        cell.classList.add('sudoku-pre-filled');
                    }

                    cell.addEventListener('input', handleInput);
                    cell.addEventListener('input', checkAllCellsFilled);
                    grid.appendChild(cell);
                }
            }
        }

        function handleInput(e) {
            const cell = e.target;
            cell.value = cell.value.replace(/[^1-9]/g, '');
        }

        async function genererNouvellePartie() {
            currentDifficulty = document.getElementById('sudoku-difficulte').value;
            document.getElementById('sudoku-loading').style.display = 'flex';
            document.getElementById('sudoku-nouvelle-partie').disabled = true;
            try {
                const response = await fetch(`./sudoku.php?action=generer&difficulte=${currentDifficulty}`);
                console.log(response);
                const data = await response.json();

                creerGrille(data.grille);
                solution = data.solution;
                secondsElapsed = 0;
                updateTimerDisplay();
                demarrerTimer();
                displayBestTime(currentDifficulty);
            } catch (error) {
                console.error("Erreur lors de la génération de la grille:", error);
                showMessage("Erreur", "Impossible de générer une nouvelle grille. Veuillez réessayer.");
            } finally {
                document.getElementById('sudoku-loading').style.display = 'none';
                document.getElementById('sudoku-nouvelle-partie').disabled = false;
            }
        }

        function verifierSolution() {
            const cells = document.querySelectorAll('#sudoku-grid input:not(.sudoku-pre-filled)');
            let correct = true;

            cells.forEach(cell => {
                const row = parseInt(cell.dataset.row);
                const col = parseInt(cell.dataset.col);

                if (cell.value != solution[row][col]) {
                    cell.classList.remove('sudoku-correct');
                    cell.classList.add('sudoku-incorrect');
                    correct = false;
                } else {
                    cell.classList.remove('sudoku-incorrect');
                    cell.classList.add('sudoku-correct');
                }
            });

            if (correct) {
                stopTimer();
                updateBestTime(currentDifficulty, secondsElapsed);
                showMessage("Félicitations !", "Vous avez résolu le Sudoku avec succès !");
            }
        }

        function demarrerTimer() {
            clearInterval(timer);
            updateTimerDisplay();
            timer = setInterval(() => {
                secondsElapsed++;
                updateTimerDisplay();
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = String(Math.floor(secondsElapsed / 60)).padStart(2, '0');
            const secs = String(secondsElapsed % 60).padStart(2, '0');
            document.getElementById('sudoku-timer').textContent = `Temps: ${minutes}:${secs}`;
        }

        function stopTimer() {
            clearInterval(timer);
        }

        function updateBestTime(difficulty, time) {
            if (!bestTimes[difficulty] || time < bestTimes[difficulty]) {
                bestTimes[difficulty] = time;
                localStorage.setItem('sudokuBestTimes', JSON.stringify(bestTimes));
            }
            displayBestTime(difficulty);
        }

        function displayBestTime(difficulty) {
            const bestTimeElement = document.getElementById('sudoku-best-time');
            const bestTime = bestTimes[difficulty];
            if (bestTime) {
                const minutes = String(Math.floor(bestTime / 60)).padStart(2, '0');
                const seconds = String(bestTime % 60).padStart(2, '0');
                bestTimeElement.textContent = `Meilleur temps: ${minutes}:${seconds}`;
            } else {
                bestTimeElement.textContent = 'Meilleur temps: --:--';
            }
        }

        function sauvegarderPartie() {
            const preFilled = Array.from(document.querySelectorAll('.sudoku-pre-filled'))
                .map(cell => ({
                    row: parseInt(cell.dataset.row),
                    col: parseInt(cell.dataset.col)
                }));

            const gameState = {
                grid: Array.from(document.querySelectorAll('#sudoku-grid input')).map(c => c.value),
                solution: solution,
                time: secondsElapsed,
                preFilled: preFilled,
                difficulty: currentDifficulty
            };

            localStorage.setItem('sudokuSave', JSON.stringify(gameState));
            showMessage("Sauvegarde", "Partie sauvegardée !");
        }

        function chargerPartie() {
            const savedGameStateJSON = localStorage.getItem('sudokuSave');

            if (!savedGameStateJSON) return showMessage("Erreur", "Aucune sauvegarde trouvée !");

            const gameState = JSON.parse(savedGameStateJSON);

            chargerGrille(gameState.grid, gameState.preFilled);
            solution = gameState.solution;
            secondsElapsed = gameState.time;
            currentDifficulty = gameState.difficulty;

            updateTimerDisplay();
            demarrerTimer();
            displayBestTime(currentDifficulty);
            showMessage("Chargement", "Partie chargée !");
        }

        function chargerGrille(gridValues, preFilled) {
            const grid = document.getElementById('sudoku-grid');
            grid.innerHTML = '';
            const verifierButton = document.getElementById('sudoku-verifier');
            verifierButton.disabled = true;

            for (let i = 0; i < 9; i++) {
                for (let j = 0; j < 9; j++) {
                    const index = i * 9 + j;
                    const cell = document.createElement('input');
                    cell.type = 'text';
                    cell.maxLength = 1;
                    cell.dataset.row = i;
                    cell.dataset.col = j;
                    cell.value = gridValues[index];

                    const estPreRemplie = preFilled.some(pos => pos.row === i && pos.col === j);
                    if (estPreRemplie) {
                        cell.readOnly = true;
                        cell.classList.add('sudoku-pre-filled');
                    }

                    cell.addEventListener('input', handleInput);
                    cell.addEventListener('input', checkAllCellsFilled);
                    grid.appendChild(cell);
                }
            }
            checkAllCellsFilled();
        }

        function donnerIndice() {
            const emptyCells = Array.from(document.querySelectorAll('#sudoku-grid input:not([readonly]):not(.sudoku-pre-filled)'))
                .filter(c => c.value === '');

            if (emptyCells.length > 0) {
                const cell = emptyCells[Math.floor(Math.random() * emptyCells.length)];
                const row = parseInt(cell.dataset.row);
                const col = parseInt(cell.dataset.col);
                cell.value = solution[row][col];
                secondsElapsed += 30;
                updateTimerDisplay();
                checkAllCellsFilled();
            } else {
                const indiceButton = document.getElementById('sudoku-indice');
                indiceButton.classList.add('sudoku-shake');
                setTimeout(() => {
                    indiceButton.classList.remove('sudoku-shake');
                }, 500);
            }
        }

        function checkAllCellsFilled() {
            const cells = document.querySelectorAll('#sudoku-grid input:not(.sudoku-pre-filled)');
            const verifierButton = document.getElementById('sudoku-verifier');
            let allFilled = true;

            cells.forEach(cell => {
                if (cell.value === '') {
                    allFilled = false;
                }
            });

            verifierButton.disabled = !allFilled;
        }

        document.getElementById('sudoku-nouvelle-partie').addEventListener('click', genererNouvellePartie);
        document.getElementById('sudoku-verifier').addEventListener('click', verifierSolution);
        document.getElementById('sudoku-sauvegarder').addEventListener('click', sauvegarderPartie);
        document.getElementById('sudoku-charger').addEventListener('click', chargerPartie);
        document.getElementById('sudoku-indice').addEventListener('click', donnerIndice);
        document.getElementById('sudoku-difficulte').addEventListener('change', function () {
            displayBestTime(this.value);
        });
        document.getElementById('sudoku-regles').addEventListener('click', function () {
            document.getElementById('sudoku-regles-container').style.display = 'flex';
        });
        document.getElementById('sudoku-regles-close').addEventListener('click', function () {
            document.getElementById('sudoku-regles-container').style.display = 'none';
        });

        displayBestTime(document.getElementById('sudoku-difficulte').value);
    </script>
</body>

</html>