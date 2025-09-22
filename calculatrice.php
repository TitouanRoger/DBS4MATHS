<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Calculatrice</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.min.js"></script>
    <link rel="stylesheet" href="css/calculatrice.css">
</head>

<body class="calculatrice-body">
    <div class="calculatrice-container">

        <div class="calculatrice-main">
            <div class="calculatrice-left-panel">
                <div class="calculatrice-display-container">
                    <input type="text" id="expression" readonly placeholder="Expression...">
                    <input type="text" id="display" readonly value="0">
                </div>

                <div class="calculatrice-tabs">
                    <button class="calculatrice-button calculatrice-tab active" data-panel="standard">Standard</button>
                    <button class="calculatrice-button calculatrice-tab" data-panel="scientific">Scientifique</button>
                    <button class="calculatrice-button calculatrice-tab" data-panel="matrix">Matricielle</button>
                    <button class="calculatrice-button calculatrice-tab" data-panel="matrix-adv">Matricielle
                        Av.</button>
                </div>

                <div class="calculatrice-content-panels">
                    <div id="standard-panel" class="calculatrice-panel active">
                        <div class="calculatrice-button-grid">
                            <button class="calculatrice-button" onclick="clearDisplay()"
                                class="calculatrice-function">C</button>
                            <button class="calculatrice-button" onclick="backspace()"
                                class="calculatrice-function">⌫</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('(')"
                                class="calculatrice-operator">(</button>
                            <button class="calculatrice-button" onclick="appendToDisplay(')')"
                                class="calculatrice-operator">)</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('/')"
                                class="calculatrice-operator">÷</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('7')">7</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('8')">8</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('9')">9</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('*')"
                                class="calculatrice-operator">×</button>
                            <button class="calculatrice-button" onclick="percentage()"
                                class="calculatrice-function">%</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('4')">4</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('5')">5</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('6')">6</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('-')"
                                class="calculatrice-operator">−</button>
                            <button class="calculatrice-button" onclick="squareRoot()"
                                class="calculatrice-function">√</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('1')">1</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('2')">2</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('3')">3</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('+')"
                                class="calculatrice-operator">+</button>
                            <button class="calculatrice-button" onclick="square()"
                                class="calculatrice-function">x²</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('0')">0</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('.')">.</button>
                            <button class="calculatrice-button" onclick="changeSign()"
                                class="calculatrice-function">±</button>
                            <button class="calculatrice-button" onclick="calculate()"
                                class="calculatrice-operator">=</button>
                            <button class="calculatrice-button" onclick="reciprocal()"
                                class="calculatrice-function">1/x</button>
                        </div>
                    </div>

                    <div id="scientific-panel" class="calculatrice-panel">
                        <div class="calculatrice-button-grid">
                            <button class="calculatrice-button" onclick="clearDisplay()"
                                class="calculatrice-function">C</button>
                            <button class="calculatrice-button" onclick="backspace()"
                                class="calculatrice-function">⌫</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('PI')"
                                class="calculatrice-function">π</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('e')"
                                class="calculatrice-function">e</button>
                            <button class="calculatrice-button" onclick="factorial()"
                                class="calculatrice-function">n!</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('sin(')"
                                class="calculatrice-function">sin</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('cos(')"
                                class="calculatrice-function">cos</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('tan(')"
                                class="calculatrice-function">tan</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('log10(')"
                                class="calculatrice-function">log</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('(')"
                                class="calculatrice-operator">(</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('asin(')"
                                class="calculatrice-function">sin⁻¹</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('acos(')"
                                class="calculatrice-function">cos⁻¹</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('atan(')"
                                class="calculatrice-function">tan⁻¹</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('10^')"
                                class="calculatrice-function">10ˣ</button>
                            <button class="calculatrice-button" onclick="appendToDisplay(')')"
                                class="calculatrice-operator">)</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('7')">7</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('8')">8</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('9')">9</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('/')"
                                class="calculatrice-operator">÷</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('^')"
                                class="calculatrice-function">xʸ</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('4')">4</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('5')">5</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('6')">6</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('*')"
                                class="calculatrice-operator">×</button>
                            <button class="calculatrice-button" onclick="squareRoot()"
                                class="calculatrice-function">√</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('1')">1</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('2')">2</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('3')">3</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('-')"
                                class="calculatrice-operator">−</button>
                            <button class="calculatrice-button" onclick="square()"
                                class="calculatrice-function">x²</button>

                            <button class="calculatrice-button" onclick="appendToDisplay('0')">0</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('.')">.</button>
                            <button class="calculatrice-button" onclick="changeSign()"
                                class="calculatrice-function">±</button>
                            <button class="calculatrice-button" onclick="calculate()"
                                class="calculatrice-operator">=</button>
                            <button class="calculatrice-button" onclick="appendToDisplay('+')"
                                class="calculatrice-operator">+</button>
                        </div>
                    </div>

                    <div id="matrix-panel" class="calculatrice-panel">
                        <div class="calculatrice-matrix-workspace">
                            <div class="calculatrice-matrix-panel">
                                <h3 class="calculatrice-matrix-title">Matrice A</h3>
                                <div class="calculatrice-matrix-controls">
                                    <label class="calculatrice-matrix-label">Lignes:</label>
                                    <input type="number" id="matrixA-rows" class="calculatrice-size-input" value="3"
                                        min="1" max="5">
                                    <label class="calculatrice-matrix-label">Colonnes:</label>
                                    <input type="number" id="matrixA-cols" class="calculatrice-size-input" value="3"
                                        min="1" max="5">
                                    <button class="calculatrice-button" onclick="resizeMatrix('A')"
                                        class="calculatrice-function">Redimensionner</button>
                                </div>
                                <div id="matrixA-grid" class="calculatrice-matrix-grid"></div>
                                <div class="calculatrice-matrix-buttons">
                                    <button class="calculatrice-button" onclick="calculateDeterminant('A')"
                                        class="calculatrice-function">Déterminant</button>
                                    <button class="calculatrice-button" onclick="calculateInverse('A')"
                                        class="calculatrice-function">Inverse</button>
                                    <button class="calculatrice-button" onclick="calculateTranspose('A')"
                                        class="calculatrice-function">Transposée</button>
                                    <button class="calculatrice-button" onclick="calculateRank('A')"
                                        class="calculatrice-function">Rang</button>
                                </div>
                            </div>

                            <div class="calculatrice-matrix-panel">
                                <h3 class="calculatrice-matrix-title">Matrice B</h3>
                                <div class="calculatrice-matrix-controls">
                                    <label class="calculatrice-matrix-label">Lignes:</label>
                                    <input type="number" id="matrixB-rows" class="calculatrice-size-input" value="3"
                                        min="1" max="5">
                                    <label class="calculatrice-matrix-label">Colonnes:</label>
                                    <input type="number" id="matrixB-cols" class="calculatrice-size-input" value="3"
                                        min="1" max="5">
                                    <button class="calculatrice-button" onclick="resizeMatrix('B')"
                                        class="calculatrice-function">Redimensionner</button>
                                </div>
                                <div id="matrixB-grid" class="calculatrice-matrix-grid"></div>
                                <div class="calculatrice-matrix-buttons">
                                    <button class="calculatrice-button" onclick="calculateDeterminant('B')"
                                        class="calculatrice-function">Déterminant</button>
                                    <button class="calculatrice-button" onclick="calculateInverse('B')"
                                        class="calculatrice-function">Inverse</button>
                                    <button class="calculatrice-button" onclick="calculateTranspose('B')"
                                        class="calculatrice-function">Transposée</button>
                                    <button class="calculatrice-button" onclick="calculateRank('B')"
                                        class="calculatrice-function">Rang</button>
                                </div>
                            </div>
                        </div>

                        <div class="calculatrice-matrix-operations">
                            <button class="calculatrice-button" onclick="addMatrices()" class="calculatrice-operator">A
                                + B</button>
                            <button class="calculatrice-button" onclick="subtractMatrices()"
                                class="calculatrice-operator">A - B</button>
                            <button class="calculatrice-button" onclick="multiplyMatrices()"
                                class="calculatrice-operator">A × B</button>
                            <button class="calculatrice-button" onclick="swapMatrices()"
                                class="calculatrice-function">Échanger A ↔ B</button>
                        </div>
                    </div>

                    <div id="matrix-adv-panel" class="calculatrice-panel">
                        <div class="calculatrice-matrix-workspace">
                            <div class="calculatrice-matrix-panel">
                                <h3 class="calculatrice-matrix-title">Opérations Avancées A</h3>
                                <div class="calculatrice-matrix-buttons">
                                    <button class="calculatrice-button" onclick="multiplyByScalar('A')"
                                        class="calculatrice-function">×
                                        Scalaire</button>
                                    <button class="calculatrice-button" onclick="gaussianElimination('A')"
                                        class="calculatrice-function">Échelonnée</button>
                                    <button class="calculatrice-button" onclick="calculatePower('A')"
                                        class="calculatrice-function">A^n</button>
                                    <button class="calculatrice-button" onclick="luDecomposition('A')"
                                        class="calculatrice-function">Décomp.
                                        LU</button>
                                    <button class="calculatrice-button" onclick="choleskyDecomposition('A')"
                                        class="calculatrice-function">Cholesky</button>
                                    <button class="calculatrice-button" onclick="calculateEigenvalues('A')"
                                        class="calculatrice-function">Val.
                                        propres</button>
                                </div>
                            </div>

                            <div class="calculatrice-matrix-panel">
                                <h3 class="calculatrice-matrix-title">Opérations Avancées B</h3>
                                <div class="calculatrice-matrix-buttons">
                                    <button class="calculatrice-button" onclick="multiplyByScalar('B')"
                                        class="calculatrice-function">×
                                        Scalaire</button>
                                    <button class="calculatrice-button" onclick="gaussianElimination('B')"
                                        class="calculatrice-function">Échelonnée</button>
                                    <button class="calculatrice-button" onclick="calculatePower('B')"
                                        class="calculatrice-function">B^n</button>
                                    <button class="calculatrice-button" onclick="luDecomposition('B')"
                                        class="calculatrice-function">Décomp.
                                        LU</button>
                                    <button class="calculatrice-button" onclick="choleskyDecomposition('B')"
                                        class="calculatrice-function">Cholesky</button>
                                    <button class="calculatrice-button" onclick="calculateEigenvalues('B')"
                                        class="calculatrice-function">Val.
                                        propres</button>
                                </div>
                            </div>
                        </div>

                        <div class="calculatrice-matrix-operations">
                            <button class="calculatrice-button" onclick="solveSystem()"
                                class="calculatrice-operator">Résoudre AX = B</button>
                            <button class="calculatrice-button" onclick="calculateQR('A')"
                                class="calculatrice-function">Décomp. QR (A)</button>
                            <button class="calculatrice-button" onclick="calculateSVD('A')"
                                class="calculatrice-function">SVD (A)</button>
                            <button class="calculatrice-button" onclick="calculateCharacteristicPolynomial('A')"
                                class="calculatrice-function">Poly.
                                caract.
                                (A)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="calculatrice-right-panel">
                <h3 class="calculatrice-history-title">Historique <button class="calculatrice-button"
                        onclick="clearHistory()">🗑️</button></h3>
                <div id="history-list"></div>
            </div>
        </div>
    </div>

    <div id="result-modal" class="calculatrice-modal">
        <div class="calculatrice-modal-content">
            <div class="calculatrice-modal-header">
                <h2 id="modal-title" class="calculatrice-modal-title">Résultat</h2>
                <button class="calculatrice-close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div id="modal-body" class="calculatrice-modal-body">
            </div>
        </div>
    </div>

    <script>

        let currentExpression = '';
        let historyList = [];
        let matrices = {
            A: createEmptyMatrix(3, 3),
            B: createEmptyMatrix(3, 3)
        };

        const displayEl = document.getElementById('display');
        const expressionEl = document.getElementById('expression');
        const historyListEl = document.getElementById('history-list');
        const modal = document.getElementById('result-modal');

        window.onload = function () {
            initTabs();
            initializeMatrices();
            loadHistory();

            window.onclick = function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            };
        };

        function clearHistory() {
            localStorage.removeItem('calculatorHistory');
            historyList = [];
            updateHistoryDisplay();
        }

        function initTabs() {
            const tabs = document.querySelectorAll('.calculatrice-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    document.querySelectorAll('.calculatrice-panel').forEach(panel => {
                        panel.classList.remove('active');
                    });

                    const panelId = tab.getAttribute('data-panel') + '-panel';
                    document.getElementById(panelId).classList.add('active');
                });
            });
        }

        function appendToDisplay(value) {
    if (displayEl.value === '0' || displayEl.value === 'Erreur') {
        displayEl.value = '';
    }
    
    // Conversion pour l'affichage utilisateur
    let displayValue = value
        .replace('pi', 'π')
        .replace('e', 'e')
        .replace(/^sin\(/, 'sin(')
        .replace(/^cos\(/, 'cos(')
        .replace(/^tan\(/, 'tan(')
        .replace(/^asin\(/, 'sin⁻¹(')
        .replace(/^acos\(/, 'cos⁻¹(')
        .replace(/^atan\(/, 'tan⁻¹(')
        .replace(/^log10\(/, 'log(')
        .replace(/^log\(/, 'ln(')
        .replace(/^pow\(10,/, '10^')
        .replace(/^exp\(/, 'e^')
        .replace(/^sqrt\(/, '√');

    // Conversion interne pour math.js
    let mathJsValue = value
        .replace('Math.PI', 'pi')
        .replace('Math.E', 'e')
        .replace('Math.sin(', 'sin(')
        .replace('Math.cos(', 'cos(')
        .replace('Math.tan(', 'tan(')
        .replace('Math.asin(', 'asin(')
        .replace('Math.acos(', 'acos(')
        .replace('Math.atan(', 'atan(')
        .replace('Math.log10(', 'log(')
        .replace('Math.log(', 'ln(')
        .replace('Math.pow(10,', '10^')
        .replace('Math.exp(', 'exp(')
        .replace('Math.sqrt(', 'sqrt(');

    displayEl.value += displayValue;
    currentExpression += mathJsValue;
    expressionEl.value = formatExpression(currentExpression);
}

function formatExpression(expr) {
    return expr
        .replace(/pi/g, 'π')
        .replace(/sin\(/g, 'sin(')
        .replace(/cos\(/g, 'cos(')
        .replace(/tan\(/g, 'tan(')
        .replace(/asin\(/g, 'sin⁻¹(')
        .replace(/acos\(/g, 'cos⁻¹(')
        .replace(/atan\(/g, 'tan⁻¹(')
        .replace(/log\(/g, 'log(')
        .replace(/ln\(/g, 'ln(')
        .replace(/10\^/g, '10^')
        .replace(/exp\(/g, 'e^')
        .replace(/sqrt\(/g, '√');
}


        function clearDisplay() {
            displayEl.value = '0';
            expressionEl.value = '';
            currentExpression = '';
        }

        function backspace() {
            if (displayEl.value === 'Erreur' || displayEl.value === '0') {
                clearDisplay();
                return;
            }

            displayEl.value = displayEl.value.slice(0, -1);

            currentExpression = currentExpression.slice(0, -1);

            if (displayEl.value === '') {
                displayEl.value = '0';
            }

            expressionEl.value = formatExpression(currentExpression);
        }

        function calculate() {
            try {
                if (!currentExpression) return;

                const originalExpression = currentExpression;
                const formattedExpression = formatExpression(originalExpression);

                const result = math.evaluate(currentExpression);

                const formattedResult = formatResult(result);

                displayEl.value = formattedResult;
                expressionEl.value = formattedExpression + ' =';

                addToHistory(formattedExpression, formattedResult);

                currentExpression = result.toString();
            } catch (error) {
                console.error('Erreur de calcul:', error);
                displayEl.value = 'Erreur';
            }
        }

        function formatResult(result) {
            if (typeof result === 'number') {
                if (Number.isInteger(result)) {
                    return result.toString();
                } else {
                    return parseFloat(result.toFixed(10)).toString();
                }
            } else {
                return result.toString();
            }
        }

        function percentage() {
            try {
                const value = math.evaluate(currentExpression);
                const result = value / 100;

                displayEl.value = result;
                expressionEl.value = formatExpression(currentExpression) + '% =';

                addToHistory(formatExpression(currentExpression) + '%', result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function squareRoot() {
            try {
                const value = math.evaluate(currentExpression);
                const result = Math.sqrt(value);

                displayEl.value = result;
                expressionEl.value = '√' + formatExpression(currentExpression) + ' =';

                addToHistory('√' + formatExpression(currentExpression), result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function square() {
            try {
                const value = math.evaluate(currentExpression);
                const result = Math.pow(value, 2);

                displayEl.value = result;
                expressionEl.value = formatExpression(currentExpression) + '² =';

                addToHistory(formatExpression(currentExpression) + '²', result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function changeSign() {
            try {
                const value = math.evaluate(currentExpression);
                const result = -value;

                displayEl.value = result;
                expressionEl.value = '-(' + formatExpression(currentExpression) + ')';

                addToHistory('-(' + formatExpression(currentExpression) + ')', result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function reciprocal() {
            try {
                const value = math.evaluate(currentExpression);

                if (value === 0) {
                    throw new Error('Division par zéro');
                }

                const result = 1 / value;

                displayEl.value = result;
                expressionEl.value = '1/' + formatExpression(currentExpression) + ' =';

                addToHistory('1/' + formatExpression(currentExpression), result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function factorial() {
            try {
                const value = Math.round(math.evaluate(currentExpression));

                if (value < 0) {
                    throw new Error('Factorielle définie pour les entiers positifs uniquement');
                }

                let result = 1;
                for (let i = 2; i <= value; i++) {
                    result *= i;
                }

                displayEl.value = result;
                expressionEl.value = formatExpression(currentExpression) + '! =';

                addToHistory(formatExpression(currentExpression) + '!', result);

                currentExpression = result.toString();
            } catch (error) {
                displayEl.value = 'Erreur';
            }
        }

        function showModal(title, content) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-body').innerHTML = content;
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function addToHistory(expression, result) {
            const historyItem = {
                expression: expression,
                result: result,
                timestamp: new Date()
            };

            historyList.unshift(historyItem);

            if (historyList.length > 50) {
                historyList.pop();
            }

            updateHistoryDisplay();

            saveHistory();
        }

        function updateHistoryDisplay() {
            historyListEl.innerHTML = '';

            historyList.forEach(item => {
                const historyItemEl = document.createElement('div');
                historyItemEl.className = 'calculatrice-history-item';

                historyItemEl.innerHTML = `
                    <div class="calculatrice-history-expression">${item.expression}</div>
                    <div class="calculatrice-history-result">${item.result}</div>
                    <div class="calculatrice-history-time">${formatTime(item.timestamp)}</div>
                    <button class="calculatrice-button" onclick="event.stopPropagation(); deleteFromHistory('${item.timestamp}')">🗑️</button>
                `;

                historyItemEl.addEventListener('click', () => {
                    displayEl.value = item.result;
                    expressionEl.value = item.expression;
                    currentExpression = item.result.toString();
                });

                historyListEl.appendChild(historyItemEl);
            });
        }

        function deleteFromHistory(timestamp) {
            historyList = historyList.filter(item => item.timestamp.toString() !== timestamp);

            updateHistoryDisplay();
            saveHistory();
        }

        function formatTime(date) {
            return new Date(date).toLocaleTimeString();
        }

        function saveHistory() {
            localStorage.setItem('calculatorHistory', JSON.stringify(historyList));
        }

        function loadHistory() {
            const savedHistory = localStorage.getItem('calculatorHistory');
            if (savedHistory) {
                historyList = JSON.parse(savedHistory);
                updateHistoryDisplay();
            }
        }

        function createEmptyMatrix(rows, cols) {
            const matrix = [];
            for (let i = 0; i < rows; i++) {
                matrix[i] = Array(cols).fill(0);
                if (i < cols) {
                    matrix[i][i] = 1;
                }
            }
            return matrix;
        }

        function initializeMatrices() {
            createMatrixGrid('A', 3, 3);
            createMatrixGrid('B', 3, 3);
        }

        function createMatrixGrid(matrixId, rows, cols) {
            const container = document.getElementById(`matrix${matrixId}-grid`);
            rows = rows || parseInt(document.getElementById(`matrix${matrixId}-rows`).value);
            cols = cols || parseInt(document.getElementById(`matrix${matrixId}-cols`).value);

            if (!matrices[matrixId] || matrices[matrixId].length !== rows || matrices[matrixId][0].length !== cols) {
                matrices[matrixId] = createEmptyMatrix(rows, cols);
            }

            container.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
            container.innerHTML = '';

            for (let i = 0; i < rows; i++) {
                for (let j = 0; j < cols; j++) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'calculatrice-matrix-cell';
                    input.id = `matrix${matrixId}-${i}-${j}`;
                    input.value = matrices[matrixId][i][j];

                    input.addEventListener('change', function () {
                        const val = this.value;
                        try {
                            matrices[matrixId][i][j] = math.evaluate(val);
                            this.value = matrices[matrixId][i][j];
                        } catch (e) {
                            this.value = 0;
                            matrices[matrixId][i][j] = 0;
                        }
                    });

                    container.appendChild(input);
                }
            }
        }

        function resizeMatrix(matrixId) {
            const rows = parseInt(document.getElementById(`matrix${matrixId}-rows`).value);
            const cols = parseInt(document.getElementById(`matrix${matrixId}-cols`).value);

            if (rows < 1 || rows > 5 || cols < 1 || cols > 5) {
                showModal('Erreur', 'Les dimensions doivent être comprises entre 1 et 5');
                return;
            }

            createMatrixGrid(matrixId, rows, cols);
        }

        function getMatrixFromGrid(matrixId) {
            const rows = parseInt(document.getElementById(`matrix${matrixId}-rows`).value);
            const cols = parseInt(document.getElementById(`matrix${matrixId}-cols`).value);
            const matrix = [];

            for (let i = 0; i < rows; i++) {
                matrix[i] = [];
                for (let j = 0; j < cols; j++) {
                    const input = document.getElementById(`matrix${matrixId}-${i}-${j}`);
                    matrix[i][j] = parseFloat(input.value) || 0;
                }
            }

            return matrix;
        }

        function formatMatrixHTML(matrix) {
            let html = '<table class="calculatrice-result-table">';
            for (let i = 0; i < matrix.length; i++) {
                html += '<tr>';
                for (let j = 0; j < matrix[i].length; j++) {
                    const value = typeof matrix[i][j] === 'number'
                        ? parseFloat(matrix[i][j].toFixed(4))
                        : matrix[i][j];
                    html += `<td>${value}</td>`;
                }
                html += '</tr>';
            }
            html += '</table>';
            return html;
        }

        function addMatrices() {
            try {
                const matrixA = getMatrixFromGrid('A');
                const matrixB = getMatrixFromGrid('B');

                if (matrixA.length !== matrixB.length || matrixA[0].length !== matrixB[0].length) {
                    throw new Error("Les matrices doivent avoir les mêmes dimensions pour l'addition");
                }

                const result = math.add(matrixA, matrixB);

                showModal("Addition de matrices A + B", formatMatrixHTML(result));

                addToHistory("Addition de matrices", "A + B calculée");
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function subtractMatrices() {
            try {
                const matrixA = getMatrixFromGrid('A');
                const matrixB = getMatrixFromGrid('B');

                if (matrixA.length !== matrixB.length || matrixA[0].length !== matrixB[0].length) {
                    throw new Error("Les matrices doivent avoir les mêmes dimensions pour la soustraction");
                }

                const result = math.subtract(matrixA, matrixB);

                showModal("Soustraction de matrices A - B", formatMatrixHTML(result));

                addToHistory("Soustraction de matrices", "A - B calculée");
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function multiplyMatrices() {
            try {
                const matrixA = getMatrixFromGrid('A');
                const matrixB = getMatrixFromGrid('B');

                if (matrixA[0].length !== matrixB.length) {
                    throw new Error(`Dimensions incompatibles pour la multiplication: A(${matrixA.length}×${matrixA[0].length}) et B(${matrixB.length}×${matrixB[0].length})`);
                }

                const result = math.multiply(matrixA, matrixB);

                showModal("Multiplication de matrices A × B", formatMatrixHTML(result));

                addToHistory("Multiplication de matrices", "A × B calculée");
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateDeterminant(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour calculer son déterminant");
                }

                const det = math.det(matrix);

                showModal(`Déterminant de ${matrixId}`, `<p style="font-size: 18px; text-align: center; margin: 20px 0;">det(${matrixId}) = ${parseFloat(det.toFixed(6))}</p>`);

                addToHistory(`Déterminant de ${matrixId}`, det);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateInverse(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour calculer son inverse");
                }

                const det = math.det(matrix);
                if (Math.abs(det) < 1e-10) {
                    throw new Error("La matrice n'est pas inversible (déterminant ≈ 0)");
                }

                const inverse = math.inv(matrix);

                showModal(`Inverse de ${matrixId}`, formatMatrixHTML(inverse));

                addToHistory(`Inverse de ${matrixId}`, `${matrixId}^(-1) calculée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateTranspose(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                const transpose = math.transpose(matrix);

                showModal(`Transposée de ${matrixId}`, formatMatrixHTML(transpose));

                addToHistory(`Transposée de ${matrixId}`, `${matrixId}^T calculée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateRank(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                const svd = math.svd(matrix);
                let rank = 0;

                for (let i = 0; i < svd.s.length; i++) {
                    if (Math.abs(svd.s[i]) > 1e-10) {
                        rank++;
                    }
                }

                showModal(`Rang de ${matrixId}`, `<p style="font-size: 18px; text-align: center; margin: 20px 0;">rang(${matrixId}) = ${rank}</p>`);

                addToHistory(`Rang de ${matrixId}`, rank);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function multiplyByScalar(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);
                const scalar = parseFloat(prompt(`Entrez le scalaire pour multiplier la matrice ${matrixId}:`, "2"));

                if (isNaN(scalar)) {
                    throw new Error("Veuillez entrer un nombre valide");
                }

                const result = math.multiply(scalar, matrix);

                showModal(`${scalar} × ${matrixId}`, formatMatrixHTML(result));

                addToHistory(`Multiplication par scalaire`, `${scalar} × ${matrixId} calculée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function gaussianElimination(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                const result = math.rref(matrix);

                showModal(`Forme échelonnée de ${matrixId}`, formatMatrixHTML(result));

                addToHistory(`Forme échelonnée de ${matrixId}`, `Calculée avec succès`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculatePower(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour calculer sa puissance");
                }

                const power = parseInt(prompt(`Entrez la puissance pour la matrice ${matrixId}:`, "2"));

                if (isNaN(power) || !Number.isInteger(power)) {
                    throw new Error("La puissance doit être un nombre entier");
                }

                let result;

                if (power === 0) {
                    result = math.identity(matrix.length)._data;
                } else if (power < 0) {
                    const inverse = math.inv(matrix);
                    result = inverse;
                    for (let i = 1; i < Math.abs(power); i++) {
                        result = math.multiply(result, inverse);
                    }
                } else {
                    result = matrix;
                    for (let i = 1; i < power; i++) {
                        result = math.multiply(result, matrix);
                    }
                }

                showModal(`${matrixId}^${power}`, formatMatrixHTML(result));

                addToHistory(`Puissance de matrice`, `${matrixId}^${power} calculée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function luDecomposition(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour la décomposition LU");
                }

                const { L, U, P } = math.lup(matrix);

                let resultHTML = '<div class="calculatrice-decomposition-result">';
                resultHTML += '<h4>Décomposition LU</h4>';

                resultHTML += '<div style="margin-top: 15px;">';
                resultHTML += '<h5>Matrice L (triangulaire inférieure)</h5>';
                resultHTML += formatMatrixHTML(L._data);
                resultHTML += '</div>';

                resultHTML += '<div style="margin-top: 15px;">';
                resultHTML += '<h5>Matrice U (triangulaire supérieure)</h5>';
                resultHTML += formatMatrixHTML(U._data);
                resultHTML += '</div>';

                resultHTML += '<div style="margin-top: 15px;">';
                resultHTML += '<h5>Matrice P (permutation)</h5>';
                resultHTML += formatMatrixHTML(P._data);
                resultHTML += '</div>';

                resultHTML += '</div>';

                showModal(`Décomposition LU de ${matrixId}`, resultHTML);

                addToHistory(`Décomposition LU de ${matrixId}`, `Calculée avec succès`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function choleskyDecomposition(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour la factorisation de Cholesky");
                }

                for (let i = 0; i < matrix.length; i++) {
                    for (let j = 0; j < i; j++) {
                        if (Math.abs(matrix[i][j] - matrix[j][i]) > 1e-10) {
                            throw new Error("La matrice doit être symétrique pour la factorisation de Cholesky");
                        }
                    }
                }

                try {
                    const L = math.cholesky(matrix);

                    let resultHTML = '<div class="calculatrice-decomposition-result">';
                    resultHTML += '<h4>Factorisation de Cholesky</h4>';

                    resultHTML += '<div style="margin-top: 15px;">';
                    resultHTML += '<h5>Matrice L</h5>';
                    resultHTML += formatMatrixHTML(L._data);
                    resultHTML += '</div>';

                    resultHTML += '<div style="margin-top: 15px;">';
                    resultHTML += '<h5>Matrice L transposée</h5>';
                    resultHTML += formatMatrixHTML(math.transpose(L)._data);
                    resultHTML += '</div>';

                    resultHTML += '</div>';

                    showModal(`Factorisation de Cholesky de ${matrixId}`, resultHTML);

                    addToHistory(`Factorisation de Cholesky de ${matrixId}`, `Calculée avec succès`);
                } catch (error) {
                    throw new Error("La matrice n'est pas définie positive");
                }
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function swapMatrices() {
            try {
                const matrixA = getMatrixFromGrid('A');
                const matrixB = getMatrixFromGrid('B');
                const rowsA = parseInt(document.getElementById('matrixA-rows').value);
                const colsA = parseInt(document.getElementById('matrixA-cols').value);
                const rowsB = parseInt(document.getElementById('matrixB-rows').value);
                const colsB = parseInt(document.getElementById('matrixB-cols').value);

                document.getElementById('matrixA-rows').value = rowsB;
                document.getElementById('matrixA-cols').value = colsB;
                document.getElementById('matrixB-rows').value = rowsA;
                document.getElementById('matrixB-cols').value = colsA;

                createMatrixGrid('A', rowsB, colsB);
                createMatrixGrid('B', rowsA, colsA);

                for (let i = 0; i < rowsB; i++) {
                    for (let j = 0; j < colsB; j++) {
                        if (i < matrixB.length && j < matrixB[0].length) {
                            document.getElementById(`matrixA-${i}-${j}`).value = matrixB[i][j];
                            matrices.A[i][j] = matrixB[i][j];
                        }
                    }
                }

                for (let i = 0; i < rowsA; i++) {
                    for (let j = 0; j < colsA; j++) {
                        if (i < matrixA.length && j < matrixA[0].length) {
                            document.getElementById(`matrixB-${i}-${j}`).value = matrixA[i][j];
                            matrices.B[i][j] = matrixA[i][j];
                        }
                    }
                }

                showModal("Échange de matrices", "Matrices A et B échangées avec succès");
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateEigenvalues(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour calculer ses valeurs propres");
                }

                let resultHTML = '<p>Cette fonctionnalité nécessite une bibliothèque plus avancée pour une implémentation complète.</p>';

                if (matrix.length === 2) {
                    const a = matrix[0][0];
                    const b = matrix[0][1];
                    const c = matrix[1][0];
                    const d = matrix[1][1];

                    const trace = a + d;
                    const det = a * d - b * c;

                    const discriminant = trace * trace - 4 * det;

                    if (discriminant >= 0) {
                        const lambda1 = (trace + Math.sqrt(discriminant)) / 2;
                        const lambda2 = (trace - Math.sqrt(discriminant)) / 2;

                        resultHTML = `
                            <p style="margin-top: 15px;">Valeurs propres:</p>
                            <ul style="list-style-type: none; padding-left: 20px;">
                                <li>λ₁ = ${lambda1.toFixed(4)}</li>
                                <li>λ₂ = ${lambda2.toFixed(4)}</li>
                            </ul>
                        `;
                    } else {
                        const realPart = trace / 2;
                        const imagPart = Math.sqrt(-discriminant) / 2;

                        resultHTML = `
                            <p style="margin-top: 15px;">Valeurs propres complexes:</p>
                            <ul style="list-style-type: none; padding-left: 20px;">
                                <li>λ₁ = ${realPart.toFixed(4)} + ${imagPart.toFixed(4)}i</li>
                                <li>λ₂ = ${realPart.toFixed(4)} - ${imagPart.toFixed(4)}i</li>
                            </ul>
                        `;
                    }
                }

                showModal(`Valeurs propres de ${matrixId}`, resultHTML);
                addToHistory(`Valeurs propres de ${matrixId}`, `Calculées`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateQR(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour la décomposition QR");
                }

                const resultHTML = `
                    <p>La décomposition QR exprime une matrice A sous la forme A = QR où:</p>
                    <ul style="margin: 15px 0; padding-left: 30px;">
                        <li>Q est une matrice orthogonale (Q'Q = I)</li>
                        <li>R est une matrice triangulaire supérieure</li>
                    </ul>
                    <p>Cette décomposition est utile pour calculer les valeurs propres de manière itérative.</p>
                `;

                showModal(`Décomposition QR de ${matrixId}`, resultHTML);
                addToHistory(`Décomposition QR de ${matrixId}`, `Information affichée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateSVD(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                let resultHTML = `
                    <p>La décomposition en valeurs singulières (SVD) exprime une matrice A sous la forme A = UΣV' où:</p>
                    <ul style="margin: 15px 0; padding-left: 30px;">
                        <li>U et V sont des matrices orthogonales</li>
                        <li>Σ est une matrice diagonale contenant les valeurs singulières</li>
                    </ul>
                `;

                try {
                    const svd = math.svd(matrix);

                    resultHTML += `<p style="margin-top: 15px;"><strong>Valeurs singulières:</strong></p>`;
                    resultHTML += `<p style="text-align: center; margin: 10px 0;">[${svd.s.map(v => v.toFixed(4)).join(', ')}]</p>`;

                } catch (e) {
                    resultHTML += `<p style="color: #e61736; margin-top: 15px;">Le calcul complet n'a pas pu être effectué.</p>`;
                }

                showModal(`Décomposition SVD de ${matrixId}`, resultHTML);
                addToHistory(`SVD de ${matrixId}`, `Information affichée`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function calculateCharacteristicPolynomial(matrixId) {
            try {
                const matrix = getMatrixFromGrid(matrixId);

                if (matrix.length !== matrix[0].length) {
                    throw new Error("La matrice doit être carrée pour calculer son polynôme caractéristique");
                }

                let resultHTML = `<p>Le polynôme caractéristique P(λ) = det(A - λI).</p>`;

                if (matrix.length === 2) {
                    const a = matrix[0][0];
                    const b = matrix[0][1];
                    const c = matrix[1][0];
                    const d = matrix[1][1];

                    const trace = a + d;
                    const det = a * d - b * c;

                    resultHTML += `<p style="text-align: center; margin: 20px 0; font-size: 18px;">P(λ) = λ² - ${trace}λ + ${det}</p>`;
                } else if (matrix.length === 3) {
                    const a = matrix[0][0], b = matrix[0][1], c = matrix[0][2];
                    const d = matrix[1][0], e = matrix[1][1], f = matrix[1][2];
                    const g = matrix[2][0], h = matrix[2][1], i = matrix[2][2];

                    const trace = a + e + i;

                    const minorSum = (a * e - b * d) + (a * i - c * g) + (e * i - f * h);

                    const det = a * (e * i - f * h) - b * (d * i - f * g) + c * (d * h - e * g);

                    resultHTML += `<p style="text-align: center; margin: 20px 0; font-size: 18px;">P(λ) = -λ³ + ${trace}λ² - ${minorSum}λ + ${det}</p>`;
                } else {
                    resultHTML += `<p style="color: #e61736; margin-top: 15px;">Le calcul explicite n'est implémenté que pour les matrices 2×2 et 3×3.</p>`;
                }

                showModal(`Polynôme caractéristique de ${matrixId}`, resultHTML);
                addToHistory(`Polynôme caractéristique de ${matrixId}`, `Calculé`);
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }

        function solveSystem() {
            try {
                const matrixA = getMatrixFromGrid('A');
                const matrixB = getMatrixFromGrid('B');

                if (matrixA.length !== matrixB.length) {
                    throw new Error(`Dimensions incompatibles: A(${matrixA.length}×${matrixA[0].length}) et B(${matrixB.length}×${matrixB[0].length})`);
                }

                if (matrixA.length !== matrixA[0].length) {
                    throw new Error("La matrice A doit être carrée pour résoudre le système AX = B");
                }

                const det = math.det(matrixA);
                if (Math.abs(det) < 1e-10) {
                    throw new Error("La matrice A n'est pas inversible, le système peut ne pas avoir de solution unique");
                }

                const X = math.lusolve(matrixA, matrixB);

                showModal("Solution du système AX = B", formatMatrixHTML(X));
                addToHistory("Système d'équations", "AX = B résolu");
            } catch (error) {
                showModal("Erreur", error.message);
            }
        }
    </script>
</body>

</html>