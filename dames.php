<?php
if (!isset($_SESSION['board'])) {
    header("Location: php/dames-init.php");
    exit();
}

$board = $_SESSION['board'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dames.css">
    <title>Dames</title>
</head>

<body class="dames-body">

    <button class="dames-button dames-rulesButton" id="rulesButton">Règles</button>
    <p class="dames-currentTurn" id="currentTurn">Au tour du joueur : <?php echo $_SESSION['turn'] == 'w' ? 'Blanc' : 'Noir'; ?></p>
    <div class="dames-board">
        <?php
        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                $cellColor = ($row + $col) % 2 == 0 ? "white" : "black";
                $pieceClass = "";
                $pieceText = "";

                if ($board[$row][$col] == "w") {
                    $pieceClass = "dames-white-piece";
                    $pieceText = "⚪";
                } elseif ($board[$row][$col] == "b") {
                    $pieceClass = "dames-black-piece";
                    $pieceText = "⚫";
                } elseif ($board[$row][$col] == "W") {
                    $pieceClass = "dames-white-piece";
                    $pieceText = "👑";
                } elseif ($board[$row][$col] == "B") {
                    $pieceClass = "dames-black-piece";
                    $pieceText = "👑";
                }

                echo "<div class='dames-cell $cellColor' data-row='$row' data-col='$col'>";
                if ($pieceClass) {
                    echo "<div class='dames-piece $pieceClass'>$pieceText</div>";
                }
                echo "</div>";
            }
        }
        ?>
    </div>
    <button class="dames-button" id="restartButton">Recommencer</button>

    <div id="rulesModal" class="dames-modal">
        <div class="dames-modal-content">
            <span class="close">&times;</span>
            <h2>Règles du Jeu</h2>
            <p>
                DAME ANGLAISE
                Vous avez 12 pièces chacun à disposer sur le plateau.
                <br>
                <br>

                - PIONS:
                Les pions se déplacent en avant et en diagonale. Ils ne peuvent pas reculer.
                Si vous déplacez un pion sans capture, vous ne vous déplacez que d’une seule case.
                Pour capturer une pièce, vous devez sauter par-dessus et atterrir sur la case en diagonale.
                Lorsque plusieurs pions se trouvent sur votre chemin, vous pouvez tous les manger en un seul coup.
                S’il a la possibilité de capturer, le joueur doit effectuer le mouvement peu importent les conséquences
                (il se sacrifie pour le peuple, honneur à lui)
                <br>
                <br>

                - PROMOTION: Jeune pion deviendra grand.
                Si l’un d’eux atteint le bout du plateau, il est promu et devient une Dame.
                Pour qu’un pion puisse devenir une Dame, il doit s’arrêter sur la case et non la traverser
                <br>
                <br>

                - DAMES:
                Pour la distinguer des autres pièces elle possède une couronne.
                Elle peut alors se déplacer en avant et en arrière mais d’une seule case à la fois.
                Elle est considérée comme plus forte et est donc supérieure aux Pions.
                <br>
                <br>

                BONNE PARTIE💪
            </p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedPiece = null;

            document.querySelectorAll(".dames-cell").forEach(cell => {
                cell.addEventListener("click", function () {
                    let row = this.getAttribute("data-row");
                    let col = this.getAttribute("data-col");

                    if (selectedPiece) {
                        movePiece(selectedPiece.row, selectedPiece.col, row, col);
                        selectedPiece = null;
                    } else if (this.querySelector(".dames-piece")) {
                        selectedPiece = { row, col };
                    }
                });
            });

            document.getElementById("restartButton").addEventListener("click", function () {
                fetch("php/dames-init.php")
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            console.error("Erreur lors de la réinitialisation :", response.statusText);
                        }
                    })
                    .catch(error => console.error("Erreur :", error));
            });

            const rulesButton = document.getElementById("rulesButton");
            const rulesModal = document.getElementById("rulesModal");
            const closeModal = document.querySelector(".dames-modal .close");

            rulesButton.addEventListener("click", function () {
                rulesModal.style.display = "block";
            });

            closeModal.addEventListener("click", function () {
                rulesModal.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === rulesModal) {
                    rulesModal.style.display = "none";
                }
            });

            function movePiece(fromRow, fromCol, toRow, toCol) {
                fetch("php/dames-game.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ fromRow, fromCol, toRow, toCol })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.winner) {
                                alert(data.winner);
                                location.reload();
                            } else {
                                document.getElementById("currentTurn").textContent =
                                    "Au tour du joueur : " + (data.turn === "w" ? "Blanc" : "Noir");
                                location.reload();
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error("Erreur :", error));
            }
        });
    </script>

</body>

</html>