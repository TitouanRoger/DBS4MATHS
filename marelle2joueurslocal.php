<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marelle à 3 Pions - 2 Joueurs (Local)</title>
  <link rel="stylesheet" href="css/marelle2joueurslocal.css">
</head>

<body class="marelle-body">
  <div class="marelle-status">Tour du joueur X</div>
  <div class="marelle-board"></div>
  <button class="marelle-restart-button marelle-button" onclick="restartGame()">Recommencer</button>
  <button class="marelle-rules-button marelle-button" onclick="toggleRules()">Règles</button>

  <div id="rules" class="marelle-rules">
    <h2>Règles du jeu</h2>
    <p>
      🔹 Au début de la partie, le plateau est vide et chaque joueur a 3 pions de couleur.<br>
      🔹 Chaque joueur pose, à son tour, un de ses pions sur une intersection libre pour aligner 3 pions en ligne,
      colonne ou diagonale.<br>

      🔹 Si aucun joueur n'a aligné ses pions après la pose, la phase de déplacement commence.<br>
      🔹 Chaque joueur peut déplacer un pion vers une intersection libre adjacente (horizontale, verticale ou
      diagonale).<br>
      🔹 Il est interdit de sauter un autre pion ou d'atterrir sur une case occupée.<br>

      <strong>🎉 Le premier joueur à aligner ses trois pions gagne !</strong>
    </p>
  </div>

  <script>
    function toggleRules() {
      const rules = document.getElementById("rules");
      rules.classList.toggle("show");
      if (rules.classList.contains("show")) {
        rules.style.zIndex = 1;
      } else {
        rules.style.zIndex = -1;
      }
    }

    const boardElement = document.querySelector('.marelle-board');
    const statusElement = document.querySelector('.marelle-status');
    let board = Array(9).fill(null);
    let currentPlayer = 'X';
    let moveCount = 0;
    let gamePhase = 1;
    let selectedIndex = null;

    function setupBoard() {
      boardElement.innerHTML = '';
      board.forEach((cell, index) => {
        const cellElement = document.createElement('div');
        cellElement.classList.add('marelle-cell');
        if (cell) {
          cellElement.textContent = cell;
          cellElement.classList.add(`marelle-player-${cell}`);
        }
        cellElement.addEventListener('click', () => handleCellClick(index));
        boardElement.appendChild(cellElement);
      });

      document.querySelectorAll('.marelle-cell').forEach(cell => cell.classList.remove('marelle-hint'));
    }

    function handleCellClick(index) {
      if (gamePhase === 1) {
        if (board[index] === null) {
          board[index] = currentPlayer;
          moveCount++;
          checkWinOrSwitchPlayer();
        }
      } else if (gamePhase === 2) {
        if (selectedIndex === null && board[index] === currentPlayer) {
          selectedIndex = index;
          highlightSelected(index);
        } else if (selectedIndex !== null && board[index] === null && isValidMove(selectedIndex, index)) {
          board[index] = currentPlayer;
          board[selectedIndex] = null;
          selectedIndex = null;
          checkWinOrSwitchPlayer();
        } else {
          selectedIndex = null;
          setupBoard();
        }
      }
      setupBoard();
    }

    function highlightSelected(index) {
      document.querySelectorAll('.marelle-cell')[index].classList.add('marelle-selected');
    }

    function isValidMove(from, to) {
      const adjacentPositions = {
        0: [1, 3, 4], 1: [0, 2, 4], 2: [1, 4, 5],
        3: [0, 4, 6], 4: [0, 1, 2, 3, 5, 6, 7, 8],
        5: [2, 4, 8], 6: [3, 4, 7], 7: [4, 6, 8], 8: [4, 5, 7]
      };
      return adjacentPositions[from]?.includes(to);
    }

    function showPossibleMoves() {
      if (gamePhase === 2 && selectedIndex === null) {
        document.querySelectorAll('.marelle-cell').forEach((cell, index) => {
          if (board[index] === currentPlayer) {
            selectedIndex = index;
            highlightMoves(index);
          }
        });
        selectedIndex = null;
      }
    }

    function highlightMoves(index) {
      const validMoves = getValidMoves(index);
      validMoves.forEach(move => {
        document.querySelectorAll('.marelle-cell')[move].classList.add('marelle-hint');
      });
    }

    function getValidMoves(index) {
      const adjacentPositions = {
        0: [1, 3, 4], 1: [0, 2, 4], 2: [1, 4, 5],
        3: [0, 4, 6], 4: [0, 1, 2, 3, 5, 6, 7, 8],
        5: [2, 4, 8], 6: [3, 4, 7], 7: [4, 6, 8], 8: [4, 5, 7]
      };
      return adjacentPositions[index]?.filter(pos => board[pos] === null) || [];
    }

    function checkWin() {
      const winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
      ];
      return winningCombinations.some(combo =>
        board[combo[0]] === currentPlayer &&
        board[combo[1]] === currentPlayer &&
        board[combo[2]] === currentPlayer
      );
    }

    function checkWinOrSwitchPlayer() {
      if (checkWin()) {
        statusElement.style.color = '#FA193B';
        statusElement.textContent = `Le joueur ${currentPlayer} gagne !`;
        boardElement.style.pointerEvents = 'none';
      } else if (moveCount >= 6 && gamePhase === 1) {
        gamePhase = 2;
        currentPlayer = 'X';
        statusElement.textContent = `Phase 2 : Déplacement - Tour du joueur ${currentPlayer}`;
      }
      else {
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        statusElement.style.color = '#FA193B';
        statusElement.textContent = `Tour du joueur ${currentPlayer}`;
      }
    }

    function restartGame() {
      board = Array(9).fill(null);
      currentPlayer = 'X';
      moveCount = 0;
      gamePhase = 1;
      selectedIndex = null;
      statusElement.style.color = '#FA193B';
      statusElement.textContent = 'Tour du joueur X';
      boardElement.style.pointerEvents = 'auto';
      setupBoard();
    }

    setupBoard();
  </script>
</body>

</html>