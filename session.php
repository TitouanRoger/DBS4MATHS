<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBS4MATHS</title>
    <link rel="icon" href="images/logo.jpg">
    <?php
    try {
        $db = new PDO('mysql:host=localhost;dbname=dbs4maths', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
    ob_start();
    session_start();
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
        }

        .sidebar {
            background-color: #FA193B;
            color: white;
            width: 100%;
            height: 100px;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: transform 0.5s;
            align-items: center;
            gap: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: inherit;
        }

        .logo {
            width: 100px;
        }

        .content {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }
        
        .import-container {
            text-align: center;
        }

        .content h1 {
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            line-height: 1.5;
        }

        .toggle-sidebar {
            position: absolute;
            right: 3%;
        }

        .toggle-sidebar:hover {
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: fixed;
            background-color: rgb(170, 3, 3);
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 10px;
        }

        .dropdown-content li {
            padding: 8px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content li:hover {
            background-color: rgb(180, 3, 3);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        @media (min-width: 435px) {
            .sidebar {
                display: flex;
            }

            .deconnexion {
            display: block;
            margin: -50px 80%;
            padding: 10px;
            background-color: #FA193B;
            color: white;
            text-align: center;
            border: 2px solid white;
            border-radius: 20px;
            text-decoration: none;
            width: 200px;
            box-sizing: border-box;
            }
        }

        @media (max-width: 435px) {
            .sidebar {
                width: 100%;
                height: 100vh;
                position: fixed;
                transform: translateX(-90%);
                padding: 10px;
            }

            .content {
                margin-left: 30px;
                padding-top: 20px;
            }

            .sidebar .logo {
                width: 150px;
                padding-left: 30%;
            }

            .sidebar  .onglets{
                padding-left: 30%;
            }

            .deconnexion {
                left: 30%;
            }

            .content h1 {
                font-size: 32px;

            }

            .content p {
                font-size: 18px;
            }
        }

        .import-container label {
            background-color: #FA193B;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .import-container label:hover {
            background-color: #e61b4b;
        }

        .message {
            padding: 15px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: auto;
        }

        .deconnexion {
            display: block;
            padding: 10px;
            background-color: #FA193B;
            color: white;
            text-align: center;
            border: 2px solid white;
            border-radius: 20px;
            text-decoration: none;
            width: 200px;
            box-sizing: border-box;
        }

        .deconnexion:hover {
            background-color: #e61b4b;
        }

        .button {
            display: block;
            padding: 15px;
            font-size: 18px;
            background-color: #FA193B;
            border: none;
            color: white;
            border-radius: 10px;
            text-align: center;
        }

        .button:hover {
            background-color: #e61b4b;
        }
        
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #FA193B;
            color: white;
            text-align: center;
            padding: 1px;
        }
    </style>
    <script>
        let inactivityTimer;

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                localStorage.clear();
            }, 600000); // 10 minutes
        }

        function toggleDropdown() {
            const dropdownContent = document.querySelector('.dropdown-content');
            if (dropdownContent.style.display === 'block') {
                dropdownContent.style.display = 'none';
            } else {
                dropdownContent.style.display = 'block';
                dropdownContent.addEventListener('mouseleave', function() {
                    dropdownContent.style.display = 'none';
                }, { once: true });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.dropdown-content');

            toggleButton.addEventListener('click', function() {
                toggleDropdown();
            });
        });

        // Fonction pour changer le contenu et stocker l'état dans localStorage
        function changeContent(content, section) {
            const toggleButton = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');
            document.getElementById('content').innerHTML = content;

            // Enregistrer la section actuelle dans localStorage
            localStorage.setItem('lastSection', section);

            if (window.innerWidth <= 435) {
                sidebar.style.transform = 'translateX(-90%)';
            }
        }

        // Fonction pour afficher l'accueil
        function showAccueil() {
            changeContent(`
                <div class="import-container">
                    <?php
                    $mail = $_SESSION['mail'];
                    $sql = $db->prepare("SELECT prenom, nom FROM utilisateurs WHERE mail = :mail");
                    $sql->bindParam(':mail', $mail);
                    $sql->execute();
                    $user = $sql->fetch(PDO::FETCH_ASSOC);
                    $prenom = $user['prenom'];
                    $nom = $user['nom'];
                    echo "<h1>Bienvenue, $prenom $nom.</h1>";
                    ?>
                </div>
            `, 'accueil');
        }

        // Fonction pour afficher l'addition
        function showAddition() {
            changeContent(`
                <div class="import-container">
                    <h1>Addition</h1>
                </div>
            `, 'addition');
        }

        // Fonction pour afficher la soustraction
        function showSoustraction() {
            changeContent(`
                <div class="import-container">
                    <h1>Soustraction</h1>
                </div>
            `, 'soustraction');
        }

        // Fonction pour afficher la multiplication
        function showMultiplication() {
            changeContent(`
                <div class="import-container">
                    <h1>Multiplication</h1>
                </div>
            `, 'multiplication');
        }

        // Fonction pour afficher la division
        function showDivision() {
            changeContent(`
                <div class="import-container">
                    <h1>Division</h1>
                </div>
            `, 'division');
        }

        // Fonction pour afficher la simulation de notes
        function showSimulationDeNotes() {
            changeContent(`
                <div class="import-container">
                    <h1>Simulation De Notes</h1>
                </div>
            `, 'simulationdenotes');
        }

        // Fonction pour afficher le jeu
        function showJeu() {
            changeContent(`
                <div class="import-container">
                    <h1>Jeu</h1>
                </div>
            `, 'jeu');
        }

        // Charger le contenu par défaut à partir de localStorage au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');

            if (window.innerWidth <= 435) {
                toggleButton.style.display = 'block';
                sidebar.style.transform = 'translateX(-90%)';
            } else {
                toggleButton.style.display = 'none';
            }

            toggleButton.addEventListener('click', function() {
                if (sidebar.style.transform === 'translateX(-20%)') {
                    sidebar.style.transform = 'translateX(-90%)';
                } else {
                    sidebar.style.transform = 'translateX(-20%)';
                }
            });

            // Vérifier si une section est stockée dans localStorage et la charger
            const lastSection = localStorage.getItem('lastSection');
            if (lastSection === 'addition') {
                showAddition();
            } else if (lastSection === 'soustraction') {
                showSoustraction();
            } else if (lastSection === 'multiplication') {
                showMultiplication();
            } else if (lastSection === 'division') {
                showDivision();
            } else if (lastSection === 'simulationdenotes') {
                showSimulationDeNotes();
            } else if (lastSection === 'jeu') {
                showJeu();
            } else {
                showAccueil();
            }
        });

        // Gérer le redimensionnement de la fenêtre
        window.addEventListener('resize', function() {
            const toggleButton = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');

            if (window.innerWidth <= 435) {
                toggleButton.style.display = 'block';
                sidebar.style.transform = 'translateX(-90%)';
            } else {
                toggleButton.style.display = 'none';
                sidebar.style.transform = 'translateX(-20%)';
            }
        });
    </script>
</head>
<body>
    <?php
        if (isset($_SESSION['mail'])) {
            $mail = $_SESSION['mail'];
            $sql = $db->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
            $sql->bindParam(':mail', $mail);
            $sql->execute();

            if ($sql->rowCount() == 0) {
                session_unset();
                session_destroy();
                echo '<div class="message" style="background-color: #f44336;">Votre compte a été supprimé. Vous allez être déconnecté.</div>';
                setcookie(session_name(), '', 0, '/');
                header("Refresh: 2; URL=index.php");
                exit;
            }

            $inactive_time = 900; // 15 minutes
            if (isset($_SESSION['last_activity'])) {
                $session_lifetime = time() - $_SESSION['last_activity'];
            
                if ($session_lifetime > $inactive_time) {
                    session_unset();
                    session_destroy();
                    setcookie(session_name(), '', 0, '/');
                    header("Location: index.php");
                    exit;
                }
            }
        }

        $_SESSION['last_activity'] = time();

        if (isset($_GET['logout'])) {
            session_destroy();
            setcookie(session_name(), '', 0, '/');
            header("Location: index.php");
            exit;
        }
    ?>
    <?php if (isset($_SESSION['mail'])): ?>
    <div class="sidebar">
        <div class="toggle-sidebar" style="display: none;">&#9776;</div>
        <img src="images/logo.jpg" alt="Logo" class="logo">
        <br><br><br>
        <div class="dropdown">
            <a class="onglets" href="#" onmouseover="toggleDropdown()">Calcul Maths</a>
            <ul class="dropdown-content">
                <li><a class="onglets" href="#" onclick="showAddition()">Addition</a></li>
                <li><a class="onglets" href="#" onclick="showSoustraction()">Soustraction</a></li>
                <li><a class="onglets" href="#" onclick="showMultiplication()">Multiplication</a></li>
                <li><a class="onglets" href="#" onclick="showDivision()">Division</a></li>
            </ul>
        </div>
        <br>
        <a class="onglets" href="#" onclick="showSimulationDeNotes()">Simulation De Notes</a>
        <br><br>
        <a class="onglets" href="#" onclick="showJeu()">Jeu</a>
        <a href="session.php?logout=true" class="deconnexion" style="position: absolute; bottom: 100px;">Deconnexion</a>
    </div>
    <div class="content" id="content">
        <!-- Default content can go here -->
    </div>
    <?php else:
        echo '<div class="message" style="background-color: #f44336;">Vous n\'êtes pas connecté.</div>';
        setcookie(session_name(), '', 0, '/');
        header("Refresh: 2; URL=index.php");
    endif;
    ?>
</body>
<footer>
    <div class="footer">
        <p>&copy; 2025 DBS4MATHS - Tous droits reservé</p>
    </div>
</footer>
</html>