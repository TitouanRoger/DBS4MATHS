<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puissance 4</title>
    <link rel="stylesheet" href="css/puissance4.css">

</head>

<body class="puissance4-body">
    <h1 class="puissance4-title">Puissance 4</h1>
    <div class="puissance4-game" id="game">
        <div class="puissance4-board" id="board"></div>
        <p class="puissance4-message" id="message">Joueur 1, à toi de jouer !</p>
        <button class="puissance4-reset" id="reset">Rejouer</button>
        <button class="puissance4-playai" id="playai">Jouer contre l'IA</button>
    </div>
    <script>
        const ROWS = 6;
        const COLS = 7;
        let board = Array.from({ length: ROWS }, () => Array(COLS).fill(null));
        let currentPlayer = "red";
        let gameOver = false;
        let isAnimating = false;
        let vsAI = false;
        let lastMove = { row: null, col: null };
        const boardElement = document.getElementById("board");
        const messageElement = document.getElementById("message");
        const resetButton = document.getElementById("reset");
        const playAIButton = document.getElementById("playai");

        const positionCache = new Map();
        let opponentStyle = { aggressive: 0, defensive: 0 };

        const openingBook = {
            '': [3, 2, 4],
            '3': [3, 2, 4],
            '32': [3, 4],
        };

        function adjustBoardSize() {
            const boardSize = Math.min(window.innerWidth * 0.9, 500);
            boardElement.style.width = boardSize + "px";
            boardElement.style.height = (boardSize / COLS) * ROWS + "px";
            boardElement.style.gridTemplateColumns = `repeat(${COLS}, 1fr)`;
            boardElement.style.gridTemplateRows = `repeat(${ROWS}, 1fr)`;
        }

        function createBoard() {
            boardElement.innerHTML = "";
            for (let row = 0; row < ROWS; row++) {
                for (let col = 0; col < COLS; col++) {
                    const cell = document.createElement("div");
                    cell.classList.add("puissance4-cell");
                    cell.dataset.row = row;
                    cell.dataset.col = col;
                    cell.addEventListener("click", () => placeToken(col));
                    boardElement.appendChild(cell);
                }
            }
            adjustBoardSize();
        }

        function placeToken(col, isAI = false) {
            if (gameOver || isAnimating || (vsAI && currentPlayer === "yellow" && !isAI)) return;

            let row = getAvailableRow(col);
            if (row === -1) return;

            isAnimating = true;

            animateTokenDrop(row, col, currentPlayer, () => {
                board[row][col] = currentPlayer;
                lastMove = { row, col };
                updateBoard();

                if (checkWin(row, col)) {
                    messageElement.innerHTML = `Le joueur <span style="color:${currentPlayer};">${currentPlayer === "red" ? "Rouge" : "Jaune"}</span> a gagné !`;
                    gameOver = true;
                    isAnimating = false;
                    return;
                }

                if (board.flat().every(cell => cell !== null)) {
                    messageElement.innerHTML = "Match nul !";
                    gameOver = true;
                    isAnimating = false;
                    return;
                }

                currentPlayer = currentPlayer === "red" ? "yellow" : "red";
                messageElement.innerHTML = `Joueur <span style="color:${currentPlayer};">${currentPlayer === "red" ? "Rouge" : "Jaune"}</span>, à toi !`;
                isAnimating = false;

                if (vsAI && currentPlayer === "yellow" && !gameOver) {
                    setTimeout(() => iaPlay(), 700);
                }
            });
        }

        function animateTokenDrop(row, col, color, callback) {
            const cell = document.querySelector(`[data-row='${row}'][data-col='${col}']`);
            const token = document.createElement("div");
            token.classList.add("puissance4-falling-token", color);
            document.body.appendChild(token);

            const rect = cell.getBoundingClientRect();
            token.style.left = `${rect.left + window.scrollX}px`;
            token.style.top = `${window.scrollY}px`;
            token.style.width = `${rect.width}px`;
            token.style.height = `${rect.height}px`;

            setTimeout(() => {
                token.style.top = `${rect.top + window.scrollY}px`;
            }, 50);

            setTimeout(() => {
                token.remove();
                callback();
            }, 500);
        }

        function updateBoard() {
            document.querySelectorAll(".puissance4-cell").forEach(cell => {
                const row = cell.dataset.row;
                const col = cell.dataset.col;
                cell.className = "puissance4-cell";
                if (board[row][col]) {
                    cell.classList.add(board[row][col]);
                }
            });
        }

        function checkWin(row, col) {
            return checkDirection(row, col, 1, 0) ||
                checkDirection(row, col, 0, 1) ||
                checkDirection(row, col, 1, 1) ||
                checkDirection(row, col, 1, -1);
        }

        function checkDirection(row, col, rowDir, colDir) {
            let count = 1;
            count += countTokens(row, col, rowDir, colDir);
            count += countTokens(row, col, -rowDir, -colDir);
            return count >= 4;
        }

        function countTokens(row, col, rowDir, colDir) {
            let count = 0;
            let r = row + rowDir;
            let c = col + colDir;

            while (r >= 0 && r < ROWS && c >= 0 && c < COLS && board[r][c] === currentPlayer) {
                count++;
                r += rowDir;
                c += colDir;
            }

            return count;
        }

        resetButton.addEventListener("click", resetGame);
        playAIButton.addEventListener("click", toggleAI);

        function resetGame() {
            board = Array.from({ length: ROWS }, () => Array(COLS).fill(null));
            currentPlayer = "red";
            gameOver = false;
            isAnimating = false;
            messageElement.innerHTML = "Joueur <span style='color:red;'>Rouge</span>, à toi de jouer !";
            updateBoard();
            positionCache.clear();
            opponentStyle = { aggressive: 0, defensive: 0 };
        }

        function toggleAI() {
            vsAI = !vsAI;
            resetGame();
            playAIButton.innerText = vsAI ? "Mode IA activé" : "Mode Joueur vs Joueur";
        }

        function iaPlay() {
            if (gameOver) return;
            let col = minimaxDecision();
            placeToken(col, true);

        }

        function getSearchDepth() {
            const movesPlayed = board.flat().filter(cell => cell !== null).length;
            if (movesPlayed < 10) return 5;
            if (movesPlayed < 20) return 7;
            return 9;
        }

        function minimaxDecision() {
            let bestScore = -Infinity;
            let bestCol = null;
            const depth = getSearchDepth();
            const colOrder = [3, 2, 4, 1, 5, 0, 6];

            const openingMove = getOpeningMove(board);
            if (openingMove !== null) return openingMove;

            for (let col of colOrder) {
                let row = getAvailableRow(col);
                if (row === -1) continue;

                board[row][col] = "yellow";
                let score = minimax(board, depth, false, -Infinity, Infinity);
                board[row][col] = null;

                if (score > bestScore) {
                    bestScore = score;
                    bestCol = col;
                }
            }
            return bestCol;
        }

        function minimax(board, depth, isMaximizing, alpha, beta) {
            const cachedScore = getCachedEvaluation(board);
            if (cachedScore !== undefined) return cachedScore;

            if (depth === 0 || checkWinByPlayer("yellow") || checkWinByPlayer("red") || board.flat().every(cell => cell !== null)) {
                const score = evaluateBoard(board);
                setCachedEvaluation(board, score);
                return score;
            }

            const colOrder = [3, 2, 4, 1, 5, 0, 6];

            if (isMaximizing) {
                let maxEval = -Infinity;
                for (let col of colOrder) {
                    let row = getAvailableRow(col);
                    if (row === -1) continue;

                    board[row][col] = "yellow";
                    let eval = minimax(board, depth - 1, false, alpha, beta);
                    board[row][col] = null;
                    maxEval = Math.max(maxEval, eval);
                    alpha = Math.max(alpha, eval);
                    if (beta <= alpha) break;
                }
                return maxEval;
            } else {
                let minEval = Infinity;
                for (let col of colOrder) {
                    let row = getAvailableRow(col);
                    if (row === -1) continue;

                    board[row][col] = "red";
                    let eval = minimax(board, depth - 1, true, alpha, beta);
                    board[row][col] = null;
                    minEval = Math.min(minEval, eval);
                    beta = Math.min(beta, eval);
                    if (beta <= alpha) break;
                }
                return minEval;
            }
        }

        function evaluateBoard(board) {
            if (checkWinByPlayer("yellow")) return 1000;
            if (checkWinByPlayer("red")) return -1000;

            let score = 0;

            for (let row = 0; row < ROWS; row++) {
                for (let col = 0; col < COLS; col++) {
                    if (board[row][col] === "yellow") {
                        score += evaluatePosition(row, col, "yellow");
                    } else if (board[row][col] === "red") {
                        score -= evaluatePosition(row, col, "red");
                    }
                }
            }

            return score;
        }

        function evaluatePosition(row, col, player) {
            let score = 0;
            const directions = [[1, 0], [0, 1], [1, 1], [1, -1]];

            for (const [rowDir, colDir] of directions) {
                let count = 1;
                count += countTokensInDirection(row, col, rowDir, colDir, player);
                count += countTokensInDirection(row, col, -rowDir, -colDir, player);

                if (count === 2) score += 10;
                if (count === 3) score += 50;
                if (count >= 4) score += 1000;
            }

            if (col === Math.floor(COLS / 2)) score += 5;
            if (isBlockingWin(row, col, player)) score += 200;
            if (createsThreat(row, col, player)) score += 100;

            return score;
        }

        function isBlockingWin(row, col, player) {
            const opponent = player === "yellow" ? "red" : "yellow";
            board[row][col] = opponent;
            const blocking = checkWin(row, col);
            board[row][col] = player;
            return blocking;
        }

        function createsThreat(row, col, player) {
            const directions = [[1, 0], [0, 1], [1, 1], [1, -1]];
            for (const [rowDir, colDir] of directions) {
                let count = 1;
                count += countTokensInDirection(row, col, rowDir, colDir, player);
                count += countTokensInDirection(row, col, -rowDir, -colDir, player);
                if (count === 3) {
                    const nextRow = row + rowDir;
                    const nextCol = col + colDir;
                    if (nextRow >= 0 && nextRow < ROWS && nextCol >= 0 && nextCol < COLS && board[nextRow][nextCol] === null) {
                        return true;
                    }
                }
            }
            return false;
        }

        function countTokensInDirection(row, col, rowDir, colDir, player) {
            let r = row + rowDir;
            let c = col + colDir;
            let count = 0;

            while (r >= 0 && r < ROWS && c >= 0 && c < COLS && board[r][c] === player) {
                count++;
                r += rowDir;
                c += colDir;
            }

            return count;
        }

        function checkWinByPlayer(player) {
            for (let row = 0; row < ROWS; row++) {
                for (let col = 0; col < COLS; col++) {
                    if (board[row][col] === player) {
                        if (checkDirection(row, col, 1, 0) ||
                            checkDirection(row, col, 0, 1) ||
                            checkDirection(row, col, 1, 1) ||
                            checkDirection(row, col, 1, -1)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        function getAvailableRow(col) {
            for (let row = ROWS - 1; row >= 0; row--) {
                if (!board[row][col]) return row;
            }
            return -1;
        }

        function getCachedEvaluation(board) {
            const key = board.flat().join('');
            return positionCache.get(key);
        }

        function setCachedEvaluation(board, score) {
            const key = board.flat().join('');
            positionCache.set(key, score);
        }

        function updateOpponentStyle(lastMove) {
            if (lastMove.row === null || lastMove.col === null) return;

            if (createsThreat(lastMove.row, lastMove.col, "red")) {
                opponentStyle.aggressive++;
            } else if (isBlockingWin(lastMove.row, lastMove.col, "red")) {
                opponentStyle.defensive++;
            }
        }

        function getOpeningMove(board) {
            const key = board.flat().join('');
            return openingBook[key] ? openingBook[key][Math.floor(Math.random() * openingBook[key].length)] : null;
        }

        window.addEventListener("resize", adjustBoardSize);
        createBoard();
    </script>
</body>

</html>