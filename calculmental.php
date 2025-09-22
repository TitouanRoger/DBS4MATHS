<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul Mental</title>
    <link rel="stylesheet" href="css/calculmental.css">
</head>

<body class="calculmental-body">
    <div class="calculmental-container">
        <div id="levelScreen">
            <button class="calculmental-level-button" data-level="primary">Primaire</button>
            <button class="calculmental-level-button" data-level="college">Collège</button>
            <button class="calculmental-level-button" data-level="lycee">Lycée</button>
            <button class="calculmental-level-button" data-level="fac">Université</button>
        </div>

        <div id="gameScreen" style="display:none;">
            <div class="calculmental-progress-bar">
                <div class="calculmental-progress"></div>
            </div>
            <div class="calculmental-timer">⏳ Temps: <span id="time">0</span>s</div>
            <div class="calculmental-question-box" id="question"></div>
            <input class="calculmental-input" type="number" id="answer" placeholder="Entrez la réponse">
            <button class="calculmental-level-button" onclick="checkAnswer()">Valider</button>
        </div>

        <div id="resultScreen" style="display:none;">
            <h2 class="calculmental-title">Résultats 🎯</h2>
            <p>Score final: <b><span id="totalScore">0</span>/1000</b></p>
            <p>🏁 Temps total: <span id="totalTime">0</span> secondes</p>
            <table class="calculmental-results-table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Votre réponse</th>
                        <th>Résultat</th>
                        <th>Temps</th>
                    </tr>
                </thead>
                <tbody id="resultsBody"></tbody>
            </table>
            <button class="calculmental-level-button" onclick="resetGame()">🔄 Rejouer</button>
        </div>
    </div>

    <script>let gameData = {
            level: null,
            questions: [],
            currentQuestion: 0,
            startTime: null,
            totalTime: 0,
            results: [],
            timerInterval: null,
            usedQuestions: {}
        };

        const levels = {
            primary: {
                timePerQuestion: 20,
                generateQuestion: () => {
                    const ops = ['+', '-', '×', '÷'];
                    let a, b, op, q;
                    let questionKey;

                    do {
                        op = ops[Math.floor(Math.random() * 4)];
                        switch (op) {
                            case '+': a = rand(10, 50); b = rand(10, 50); break;
                            case '-': a = rand(30, 100); b = rand(10, a - 1); break;
                            case '×': a = rand(2, 12); b = rand(2, 12); break;
                            case '÷': b = rand(2, 12); a = b * rand(2, 10); break;
                        }
                        q = `${a} ${op} ${b}`;
                        questionKey = `primary_${q}`;
                    } while (gameData.usedQuestions[questionKey]);

                    gameData.usedQuestions[questionKey] = true;

                    return {
                        q: q,
                        answer: eval(a + op.replace('×', '*').replace('÷', '/') + b)
                    };
                }
            },
            college: {
                timePerQuestion: 30,
                generateQuestion: () => {
                    let q, answer, questionKey;

                    do {
                        const type = rand(1, 4);

                        switch (type) {
                            case 1:
                                const d1 = rand(10, 200) / 10, d2 = rand(10, 200) / 10;
                                const op = rand(0, 1) ? '+' : '-';
                                q = `${d1} ${op} ${d2}`;
                                answer = eval(d1 + op + d2);
                                break;
                            case 2:
                                const den = rand(2, 12);
                                const num1 = rand(1, den - 1);
                                const num2 = rand(1, den - 1);
                                q = `${num1}/${den} + ${num2}/${den}`;
                                answer = (num1 + num2) / den;
                                break;
                            case 3:
                                const val = rand(10, 200);
                                const percentage = rand(5, 95);
                                q = `${percentage}% de ${val}`;
                                answer = val * percentage / 100;
                                break;
                            case 4:
                                const sq = rand(5, 15);
                                q = `√${sq * sq}`;
                                answer = sq;
                                break;
                        }
                        questionKey = `college_${q}`;
                    } while (gameData.usedQuestions[questionKey]);

                    gameData.usedQuestions[questionKey] = true;

                    return { q, answer: Number(answer.toFixed(2)) };
                }
            },
            lycee: {
                timePerQuestion: 40,
                generateQuestion: () => {
                    let q, answer, questionKey;

                    do {
                        const type = rand(1, 4);

                        switch (type) {
                            case 1:
                                const x = rand(1, 10);
                                const coef = rand(2, 5);
                                const constant = rand(5, 20);
                                q = `${coef}x + ${constant} = ${coef * x + constant}`;
                                answer = x;
                                break;
                            case 2:
                                const b = rand(2, 5);
                                q = `${b}⁴`;
                                answer = Math.pow(b, 4);
                                break;
                            case 3:
                                const v = rand(-50, 50);
                                q = `|${v}|`;
                                answer = Math.abs(v);
                                break;
                            case 4:
                                const f = rand(2, 6);
                                q = `${f}x + ${f * 2}`;
                                answer = f;
                                break;
                        }
                        questionKey = `lycee_${q}`;
                    } while (gameData.usedQuestions[questionKey]);

                    gameData.usedQuestions[questionKey] = true;

                    return { q, answer };
                }
            },
            fac: {
                timePerQuestion: 50,
                generateQuestion: () => {
                    let q, answer, questionKey;

                    do {
                        const type = rand(1, 3);

                        switch (type) {
                            case 1:
                                const p = rand(2, 4);
                                q = `d/dx (x${p})`;
                                answer = p;
                                break;
                            case 2:
                                const bases = [2, 10, Math.E];
                                const baseIndex = rand(0, 2);
                                const base = bases[baseIndex];
                                const power = rand(1, 4);
                                q = `log${base === Math.E ? 'ₑ' : base}(${Math.pow(base, power)})`;
                                answer = power;
                                break;
                            case 3:
                                const angles = [0, 30, 45, 60, 90];
                                const angle = angles[rand(0, 4)];
                                q = `cos(${angle}°)`;
                                answer = Math.cos(angle * Math.PI / 180).toFixed(3);
                                break;
                        }
                        questionKey = `fac_${q}`;
                    } while (gameData.usedQuestions[questionKey]);

                    gameData.usedQuestions[questionKey] = true;

                    return { q, answer: Number(answer) };
                }
            }
        };

        function startGame(level) {
            gameData = {
                level,
                questions: [],
                currentQuestion: 0,
                startTime: Date.now(),
                totalTime: 0,
                results: [],
                timerInterval: null,
                usedQuestions: {}
            };

            for (let i = 0; i < 20; i++) {
                gameData.questions.push(levels[level].generateQuestion());
            }

            document.getElementById('levelScreen').style.display = 'none';
            document.getElementById('gameScreen').style.display = 'block';
            showQuestion();
            startTimer();
        }

        function showQuestion() {
            const q = gameData.questions[gameData.currentQuestion];
            document.getElementById('question').textContent = q.q;
            document.getElementById('answer').value = '';
            document.getElementById('answer').focus();
            updateProgress();
        }

        function updateProgress() {
            const progress = (gameData.currentQuestion / 20) * 100;
            document.querySelector('.calculmental-progress').style.width = `${progress}%`;
        }

        function startTimer() {
            clearInterval(gameData.timerInterval);
            let timeSpent = 0;
            document.getElementById('time').textContent = '0';
            gameData.timerInterval = setInterval(() => {
                timeSpent++;
                document.getElementById('time').textContent = timeSpent;
            }, 1000);
        }

        function checkAnswer() {
            const userAnswer = parseFloat(document.getElementById('answer').value);
            const correctAnswer = gameData.questions[gameData.currentQuestion].answer;
            const timeSpent = parseInt(document.getElementById('time').textContent);

            gameData.results.push({
                question: gameData.questions[gameData.currentQuestion].q,
                userAnswer: isNaN(userAnswer) ? '-' : userAnswer,
                correctAnswer: correctAnswer,
                time: timeSpent,
                isCorrect: Math.abs(userAnswer - correctAnswer) < 0.01
            });

            gameData.totalTime += timeSpent;
            gameData.currentQuestion++;
            clearInterval(gameData.timerInterval);

            if (gameData.currentQuestion >= 20) {
                showResults();
            } else {
                showQuestion();
                startTimer();
            }
        }

        function calculateScore() {
            return gameData.results.reduce((acc, result) => {
                return acc + (result.isCorrect ? 40 + Math.max(0, 10 - result.time) : 0);
            }, 0);
        }

        function showResults() {
            document.getElementById('gameScreen').style.display = 'none';
            document.getElementById('resultScreen').style.display = 'block';
            document.getElementById('totalScore').textContent = calculateScore();
            document.getElementById('totalTime').textContent = gameData.totalTime;

            const resultsBody = document.getElementById('resultsBody');
            resultsBody.innerHTML = gameData.results.map((result, i) => `
        <tr>
            <td>${result.question}</td>
            <td>${result.userAnswer}</td>
            <td class="${result.isCorrect ? 'calculmental-correct' : 'calculmental-wrong'}">
                ${result.correctAnswer} ${result.isCorrect ? '✅' : '❌'}
            </td>
            <td>${result.time}s</td>
        </tr>
    `).join('');
        }

        function resetGame() {
            clearInterval(gameData.timerInterval);
            document.getElementById('resultScreen').style.display = 'none';
            document.getElementById('levelScreen').style.display = 'block';
        }

        const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('answer').addEventListener('keyup', e => {
                if (e.key === 'Enter') checkAnswer();
            });

            document.querySelectorAll('.calculmental-level-button').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.dataset.level) startGame(btn.dataset.level);
                });
            });
        });
    </script>
</body>

</html>