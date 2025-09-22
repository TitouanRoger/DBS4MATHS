<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux de Tirage Aléatoire</title>
    <link rel="stylesheet" href="css/simulation_tirages.css">
</head>

<body>

    <div class="game-container">
        <div class="choice-buttons">
            <button class="choice-btn active" onclick="showGame(event, 'dice')">Lancer un dé</button>
            <button class="choice-btn" onclick="showGame(event, 'card')">Tirer une carte</button>
            <button class="choice-btn" onclick="showGame(event, 'name')">Tirage de nom</button>
        </div>

        <div id="dice-game" class="game-area">
            <div class="dice-container">
                <div class="dice" id="dice">
                    <div class="dice-face face-1">
                        <div class="dot"></div>
                    </div>
                    <div class="dice-face face-2">
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="dice-face face-3">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="dice-face face-4">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="dice-face face-5">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="dice-face face-6">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </div>
            <button class="action-btn" onclick="rollDice()">Lancer le dé</button>

        </div>

        <div id="card-game" class="game-area hidden">
            <div class="card" id="card">?</div>
            <button class="action-btn" onclick="drawCard()">Tirer une carte</button>

        </div>

        <div id="name-game" class="game-area hidden">
            <input type="text" id="name-input" placeholder="Ajoutez un nom">
            <button class="action-btn" onclick="addName()">Ajouter</button>
            <ul id="name-list"></ul>
            <button class="action-btn" onclick="drawName()">Tirer un nom</button>
            <p id="drawn-name"></p>
        </div>


    </div>

    <script>
        let nameList = [];
        let history = [];

        const diceValues = [
            { value: 1, rotation: 'rotateX(0deg) rotateY(0deg) rotateZ(0deg)' },
            { value: 2, rotation: 'rotateX(-180deg) rotateY(0deg) rotateZ(0deg)' },
            { value: 3, rotation: 'rotateX(0deg) rotateY(90deg) rotateZ(0deg)' },
            { value: 4, rotation: 'rotateX(0deg) rotateY(-90deg) rotateZ(0deg)' },
            { value: 5, rotation: 'rotateX(90deg) rotateY(0deg) rotateZ(0deg)' },
            { value: 6, rotation: 'rotateX(-90deg) rotateY(0deg) rotateZ(0deg)' }
        ];

        function showGame(event, gameType) {
            document.querySelectorAll('.game-area').forEach(game => game.classList.add('hidden'));
            const selectedGame = document.getElementById(gameType + '-game');
            if (selectedGame) {
                selectedGame.classList.remove('hidden');
            }
            document.querySelectorAll('.choice-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

        }

        function rollDice() {
            const dice = document.getElementById("dice");
            const rollBtn = document.querySelector("#dice-game .action-btn");

            rollBtn.disabled = true;
            dice.classList.add('rolling');

            setTimeout(() => {
                const randomValue = Math.floor(Math.random() * 6) + 1;
                const diceValue = diceValues.find(d => d.value === randomValue);

                if (diceValue) {
                    dice.style.transform = diceValue.rotation;
                }

                dice.classList.remove('rolling');
                rollBtn.disabled = false;

                document.getElementById("dice-result").textContent = "Résultat du dé : " + randomValue;


            }, 1500);
        }

        function drawCard() {
            const card = document.getElementById("card");

            card.textContent = "🃏";
            card.className = "card";
            card.style.transform = "rotateY(180deg)";
            card.style.transition = "transform 0.5s ease-out";

            setTimeout(() => {
                const suits = ["heart", "diamond", "club", "spade"];
                const values = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

                const randomSuit = suits[Math.floor(Math.random() * suits.length)];
                const randomValue = values[Math.floor(Math.random() * values.length)];

                let suitSymbol;
                switch (randomSuit) {
                    case "heart": suitSymbol = "♥"; break;
                    case "diamond": suitSymbol = "♦"; break;
                    case "club": suitSymbol = "♣"; break;
                    case "spade": suitSymbol = "♠"; break;
                }

                card.className = "card " + randomSuit;

                card.innerHTML = `
                    <span class="corner top-left">${randomValue}${suitSymbol}</span>
                    <div class="card-symbol">${suitSymbol}</div>
                    <span class="corner bottom-right">${randomValue}${suitSymbol}</span>
                `;
                card.style.transform = "rotateY(0deg)";

                const suitNames = { 'heart': 'Coeur', 'diamond': 'Carreau', 'club': 'Trèfle', 'spade': 'Pique' };
                document.getElementById("card-result").textContent = `Carte tirée : ${randomValue} de ${suitNames[randomSuit]}`;


            }, 500);
        }

        function addName() {
            const nameInput = document.getElementById("name-input");
            const name = nameInput.value.trim();
            if (name) {
                nameList.push(name);
                const nameListElement = document.getElementById("name-list");
                const li = document.createElement("li");
                li.textContent = name;

                const deleteBtn = document.createElement("button");
                deleteBtn.textContent = "×";
                deleteBtn.onclick = function () {
                    nameList = nameList.filter(n => n !== name);
                    li.remove();
                };

                li.appendChild(deleteBtn);
                nameListElement.appendChild(li);
                nameInput.value = "";
            }
        }

        function drawName() {
            if (nameList.length > 0) {
                const randomIndex = Math.floor(Math.random() * nameList.length);
                document.getElementById("drawn-name").textContent = "Nom tiré : " + nameList[randomIndex];
            } else {
                document.getElementById("drawn-name").textContent = "Ajoutez des noms d'abord";
            }
        }



    </script>
</body>

</html>