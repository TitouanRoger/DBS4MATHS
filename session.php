<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBS4MATHS</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/session.css">

    <?php
    function loadEnv($file)
    {
        if (!file_exists($file)) {
            throw new Exception("Le fichier .env est introuvable");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            putenv(trim($key) . '=' . trim($value));
        }
    }

    loadEnv(__DIR__ . '/.env');

    $dbhost = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $dbuser = getenv('DB_USER');
    $dbpass = getenv('DB_PASS');

    try {
        $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
    ob_start();
    session_start();
    ?>
    <script>
        let inactivityTimer;

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.delete('page');
                window.history.pushState({}, '', url.toString());

                window.location.href = '?page=accueil';
            }, 600000);
        }



        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.querySelector('.session-toggle-sidebar');
            const sidebar = document.querySelector('.session-sidebar');

            if (window.innerWidth <= 435) {
                toggleButton.style.display = 'block';
            } else {
                toggleButton.style.display = 'none';
            }

            toggleButton.addEventListener('click', function () {
                if (sidebar.style.transform === 'translateX(-20%)') {
                    sidebar.style.transform = 'translateX(-90%)';
                } else {
                    sidebar.style.transform = 'translateX(-20%)';
                }
            });

            document.addEventListener('click', function (e) {
                if (sidebar.style.transform === 'translateX(-20%)' && !sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                    sidebar.style.transform = 'translateX(-90%)';
                }
            });



            window.addEventListener('resize', function () {
                const toggleButton = document.querySelector('.session-toggle-sidebar');
                const sidebar = document.querySelector('.session-sidebar');

                if (window.innerWidth <= 435) {
                    toggleButton.style.display = 'block';
                    sidebar.style.transform = 'translateX(-90%)';
                } else {
                    toggleButton.style.display = 'none';
                }
            });
    </script>
</head>

<body class="session-body">
    <?php
    if (isset($_SESSION['mail'])) {
        $mail = $_SESSION['mail'];
        $sql = $db->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
        $sql->bindParam(':mail', $mail);
        $sql->execute();

        if ($sql->rowCount() == 0) {
            session_unset();
            session_destroy();
            echo '<div class="session-message" style="background-color: #f44336;">Votre compte a été supprimé. Vous allez être déconnecté.</div>';
            setcookie(session_name(), '', 0, '/');
            header("Refresh: 2; URL=index.php");
            exit;
        }

        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

        $valid_pages = ['accueil', 'calculatrice', 'simulationdenotes', 'marelle'];
        if (!in_array($page, $valid_pages)) {
            $page = 'accueil';
        }

        $inactive_time = 900;
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
        <div class="session-sidebar">
            <div class="session-toggle-sidebar" style="display: none;">&#9776;</div>
            <img src="images/logor.jpg" alt="Logo">
            <br><br><br>
            <a class="onglets" href="?page=maths">Maths</a>
            <br>
            <a class="onglets" href="?page=simulateurs">Simulateurs</a>
            <br>
            <a class="onglets" href="?page=jeux">Jeux</a>
            <a href="?page=contact" class="session-contact" style="position: absolute; bottom: 100px;">Contact</a>
            <a href="session.php?logout=true" class="session-deconnexion"
                style="position: absolute; bottom: 100px;">Deconnexion</a>
        </div>
        <!-- Default content can go here -->
        <?php
        // Fonction pour enregistrer les statistiques
        function enregistrerStatistique($fonctionnalite)
        {
            $fichier = __DIR__ . '/stats.txt';
            $stats = [];

            // Lire les statistiques existantes
            if (file_exists($fichier)) {
                $lines = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    list($key, $value) = explode(':', $line, 2);
                    $stats[$key] = (int) $value;
                }
            }

            // Incrémenter la fonctionnalité utilisée
            if (isset($stats[$fonctionnalite])) {
                $stats[$fonctionnalite]++;
            } else {
                $stats[$fonctionnalite] = 1;
            }

            // Sauvegarder les statistiques
            $fp = fopen($fichier, 'w');
            foreach ($stats as $key => $value) {
                fwrite($fp, "$key:$value\n");
            }
            fclose($fp);
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            enregistrerStatistique($page); // Enregistre chaque utilisation
    
            ?>
            <div class="session-content-<?php echo $page ?>" id="content">
                <?php
                switch ($page) {
                    case 'contact':
                        include 'contact.php';
                        break;
                    case 'maths':
                        include 'maths.php';
                        break;
                    case 'calculatrice':
                        include 'calculatrice.php';
                        break;
                    case 'convertisseur':
                        include 'convertisseur.php';
                        break;
                    case 'simulateurs':
                        include 'simulateurs.php';
                        break;
                    case 'simulation_notes':
                        include 'simulation_notes.php';
                        break;
                    case 'simulation_code':
                        ?>
                        <div class="session-import-container">
                            <div style="position: relative;">
                                <div id="ajax-content" style="max-height: 550px; overflow-y: auto;">
                                    <?php
                                    include 'simulation_code.php';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;
                    case 'simulation_tirages':
                        include 'simulation_tirages.php';
                        break;
                    case 'jeux':
                        include 'jeux.php';
                        break;
                    case 'marelle':
                        include 'marelle.php';
                        break;
                    case 'marelle2joueurslocal':
                        include 'marelle2joueurslocal.php';
                        break;
                    case 'marelleia':
                        include 'marelleia.php';
                        break;
                    case 'puissance4':
                        include 'puissance4.php';
                        break;
                    case 'dames':
                        include 'dames.php';
                        break;
                    case 'sudoku':
                        include 'sudoku.php';
                        break;
                    case 'calculmental':
                        ?>
                    <div class="session-import-container">
                        <div style="position: relative;">
                            <div id="ajax-content" style="max-height: 550px; overflow-y: auto;">
                                <?php
                                include 'calculmental.php';
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                    <?php
                    break;
                    default:
                        ?>
                    <div class="session-content-accueil" id="content">
                        <?php
                        $mail = $_SESSION['mail'];
                        $sql = $db->prepare("SELECT prenom, nom FROM utilisateurs WHERE mail = :mail");
                        $sql->bindParam(':mail', $mail);
                        $sql->execute();
                        $user = $sql->fetch(PDO::FETCH_ASSOC);
                        $prenom = $user['prenom'];
                        $nom = $user['nom'];
                        echo "<h1>Bienvenue, $prenom $nom.</h1>";
                }
        }
        ?>
        </div>
    <?php else:
        echo '<div class="session-message" style="background-color: #f44336;">Vous n\'êtes pas connecté.</div>';
        setcookie(session_name(), '', 0, '/');
        header("Refresh: 2; URL=index.php");
    endif;
    ?>
</body>

<?php
if ($page != 'calculatrice' && $page != 'convertisseur' && $page != 'simulation_notes' && $page != 'simulation_code' && $page != 'simulateur_tirage' && $page != 'marelle' && $page != 'marelle2joueurslocal' && $page != 'marelleia' && $page != 'puissance4' && $page != 'dames' && $page != 'sudoku' && $page != 'calculmental') {
    $sql = $db->prepare("SELECT version FROM applications WHERE nom = 'DBS4MATHS'");
    $sql->execute();
    $app = $sql->fetch(PDO::FETCH_ASSOC);
    echo "<footer><p>&copy; " . date('Y') . " DBS4MATHS - V" . $app['version'] . " - Tous droits réservés</p></footer>";
}
?>

</html>