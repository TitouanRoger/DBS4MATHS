<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marelle à 3 Pions - IA</title>
  <link rel="stylesheet" href="css/marelleia.css">
</head>

<body class="marelle-body">
  <div class="marelle-status">Tour du joueur X</div>
  <div class="marelle-board"></div>
  <button class="marelle-restart-button marelle-button" onclick="restartGame()">Recommencer</button>
  <button class="marelle-level-button marelle-button" onclick="toggleAILevel()">Niveau : Facile</button>
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
    let currentPlayer;

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
    const levelButton = document.querySelector('.marelle-level-button');

    let board = Array(9).fill(null);
    let player = 'X';
    let ai = 'O';
    let moveCount = 0;
    let gamePhase = 1;
    let playerPlaced = 0;
    let aiPlaced = 0;
    let selectedIndex = null;
    let aiLevel = 1;

    if (Math.random() < 0.5) {
      player = 'X';
      ai = 'O';
      currentPlayer = player;
      statusElement.textContent = "Tour du joueur X";
    } else {
      player = 'O';
      ai = 'X';
      currentPlayer = ai;
      statusElement.textContent = "Tour de l'IA";
      setTimeout(aiMove, 500);
    }

    function setupBoard() {
      boardElement.innerHTML = '';
      board.forEach((cell, index) => {
        const cellElement = document.createElement('div');
        cellElement.classList.add('marelle-cell');
        cellElement.dataset.index = index;

        if (cell) {
          cellElement.textContent = cell;
          cellElement.classList.add(`marelle-player-${cell}`);
        }

        cellElement.addEventListener('click', () => handleCellClick(index));
        boardElement.appendChild(cellElement);
      });
    }

    function playerTurn() {
      return currentPlayer === player;
    }


    function handleCellClick(index) {
      if (!playerTurn()) return;

      console.log(`Clique détecté sur la case ${index}, phase ${gamePhase}`);

      if (gamePhase === 1 && board[index] === null && playerPlaced < 3) {
        board[index] = player;
        playerPlaced++;
        moveCount++;
        currentPlayer = ai;

        setupBoard();
        checkWinOrNextPhase();
        setTimeout(aiMove, 500);
      } else if (gamePhase === 2) {
        if (board[index] === player) {
          selectedIndex = index;
          setupBoard();
        } else if (selectedIndex !== null && board[index] === null && isValidMove(selectedIndex, index)) {
          board[index] = player;
          board[selectedIndex] = null;
          selectedIndex = null;
          moveCount++;
          currentPlayer = ai;

          setupBoard();
          checkWinOrNextPhase();
          setTimeout(aiMove, 500);
        }
      }
    }


    function isValidMove(from, to) {
      const validMoves = getValidMoves(from);
      return validMoves.includes(to);
    }

    function checkWinOrNextPhase() {
      if (checkWin(player)) {
        statusElement.textContent = `Bravo ! Vous avez gagné 🎉`;
        boardElement.style.pointerEvents = 'none';
        return;
      }

      if (checkWin(ai)) {
        statusElement.textContent = `L'IA a gagné 😈`;
        boardElement.style.pointerEvents = 'none';
        return;
      }

      if (playerPlaced === 3 && aiPlaced === 3) {
        gamePhase = 2;
        statusElement.textContent = "Phase 2 : Déplacez vos pions";
      }
    }

    function aiMove() {
      if (!playerTurn() && !checkWin(player) && !checkWin(ai)) {
        let bestMove;
        if (aiLevel === 1) {
          bestMove = gamePhase === 1 ? findBestPlacement() : findBestMove();
        } else {
          bestMove = gamePhase === 1 ? findBestPlacementMinimax() : findBestMoveMinimax();
        }

        if (bestMove !== null) {
          if (gamePhase === 1) {
            board[bestMove] = ai;
            aiPlaced++;
          } else {
            board[bestMove.to] = ai;
            board[bestMove.from] = null;
          }
          moveCount++;
          currentPlayer = player;

          setupBoard();
          checkWinOrNextPhase();
        }
      }
    }


    function findBestPlacement() {
      let emptyCells = board.map((val, idx) => val === null ? idx : null).filter(v => v !== null);
      return emptyCells.length > 0 ? emptyCells[Math.floor(Math.random() * emptyCells.length)] : null;
    }

    function findBestPlacementMinimax() {
      let bestMove = minimax(board, ai, 0).index;
      return bestMove;
    }

    function findBestMove() {
      let aiPawns = board.map((val, idx) => val === ai ? idx : null).filter(v => v !== null);
      for (let pawn of aiPawns) {
        let validMoves = getValidMoves(pawn);
        if (validMoves.length > 0) {
          return { from: pawn, to: validMoves[Math.floor(Math.random() * validMoves.length)] };
        }
      }
      return null;
    }

    function findBestMoveMinimax() {
      let bestMove = null;
      let bestScore = -Infinity;
      let aiPawns = board.map((val, idx) => val === ai ? idx : null).filter(v => v !== null);

      for (let pawn of aiPawns) {
        let validMoves = getValidMoves(pawn);
        for (let move of validMoves) {
          let newBoard = [...board];
          newBoard[pawn] = null;
          newBoard[move] = ai;
          let score = minimax(newBoard, player, 0).score;
          if (score > bestScore) {
            bestScore = score;
            bestMove = { from: pawn, to: move };
          }
        }
      }
      return bestMove;
    }

    function minimax(newBoard, playerTurn, depth) {
      let emptyCells = newBoard.map((val, idx) => val === null ? idx : null).filter(v => v !== null);
      if (checkWin(player)) return { score: -10 + depth };
      if (checkWin(ai)) return { score: 10 - depth };
      if (emptyCells.length === 0) return { score: 0 };

      let moves = [];
      for (let index of emptyCells) {
        let move = {};
        move.index = index;
        newBoard[index] = playerTurn;
        move.score = minimax(newBoard, playerTurn === ai ? player : ai, depth + 1).score;
        newBoard[index] = null;
        moves.push(move);
      }

      return moves.reduce((best, move) =>
        (playerTurn === ai ? move.score > best.score : move.score < best.score) ? move : best,
        { score: playerTurn === ai ? -Infinity : Infinity }
      );
    }

    function checkWin(symbol) {
      return [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
      ].some(combo => combo.every(i => board[i] === symbol));
    }

    function getValidMoves(from) {
      const adjacentPositions = {
        0: [1, 3, 4], 1: [0, 2, 4], 2: [1, 5, 4],
        3: [0, 4, 6], 4: [0, 1, 2, 3, 5, 6, 7, 8],
        5: [2, 4, 8], 6: [3, 4, 7], 7: [4, 6, 8], 8: [4, 5, 7]
      };
      return adjacentPositions[from]?.filter(to => board[to] === null) || [];
    }

    function toggleAILevel() {
      aiLevel = aiLevel === 1 ? 2 : 1;
      levelButton.textContent = aiLevel === 1 ? "Niveau : Facile" : "Niveau : Difficile";
      restartGame();
    }

    function restartGame() {
      board = Array(9).fill(null);
      moveCount = 0;
      gamePhase = 1;
      playerPlaced = 0;
      aiPlaced = 0;
      selectedIndex = null;

      if (Math.random() < 0.5) {
        player = 'X';
        ai = 'O';
        currentPlayer = player;
        statusElement.textContent = "Tour du joueur X";
      } else {
        player = 'O';
        ai = 'X';
        currentPlayer = ai;
        statusElement.textContent = "Tour de l'IA";
        setTimeout(aiMove, 500);
      }

      boardElement.style.pointerEvents = 'auto';
      setupBoard();
    }


    setupBoard();
  </script>
</body>

</html>