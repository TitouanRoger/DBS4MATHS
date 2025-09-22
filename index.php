<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/index.css">
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
</head>

<body>
    <img src="images/logo.jpg" alt="Logo">
    <form method="post">
        <label>Mail</label>
        <input type="email" name="mail" placeholder="Mail" required>
        <label>Mot de passe</label>
        <div class="password-container">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <a href="forgot.php" class="menu">Mot de passe oublié ?</a>
        <br>
        <a href="inscription.php" class="menu">Pas encore inscrit ?</a>
        <button type="submit" name="connexion">Connexion</button>
    </form>
    <?php
    function connexion()
    {
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

        if (isset($_POST['connexion'])) {
            $mail = htmlspecialchars($_POST['mail']);
            $password = htmlspecialchars($_POST['password']);
            $sql = $db->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
            $sql->bindValue(':mail', htmlspecialchars($mail), PDO::PARAM_STR);
            $sql->execute();

            if ($sql->rowCount() == 1) {
                $sql = $db->prepare("SELECT password FROM utilisateurs WHERE mail = :mail");
                $sql->bindParam(':mail', $mail);
                $sql->execute();
                $row = $sql->fetch();
                $hashed_password = $row['password'];

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['mail'] = $mail;
                    $sql = $db->prepare("SELECT password_changed FROM utilisateurs WHERE mail = :mail");
                    $sql->bindParam(':mail', $mail);
                    $sql->execute();
                    $user = $sql->fetch();
                    if ($user['password_changed'] === 0) {
                        echo '<div class="message success">Connexion réussie !</div>';
                        header("Refresh: 2; URL=change_password.php");
                        exit;
                    }

                    echo '<div class="message success">Connexion réussie !</div>';
                    header("Refresh: 2; URL=session.php?page=accueil");
                    exit;
                } else {
                    session_unset();
                    session_destroy();
                    echo '<div class="message error">Identifiant ou Mot de passe incorrect.</div>';
                }
            } else {
                echo '<div class="message error">Identifiant ou Mot de passe incorrect.</div>';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        connexion();
    }
    ?>
    <script>
        function togglePassword() {
            var password = document.querySelector("input[name='password']");
            var eyeIcon = document.querySelector(".toggle-password");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.textContent = "🙈";
            } else {
                password.type = "password";
                eyeIcon.textContent = "👁️";
            }
        }
    </script>
</body>
<footer>
    <?php
    $sql = $db->prepare("SELECT version FROM applications WHERE nom = 'DBS4MATHS'");
    $sql->execute();
    $app = $sql->fetch(PDO::FETCH_ASSOC);
    ?>
    <p>&copy; <?php echo date('Y') ?> DBS4MATHS - V<?php echo $app['version'] ?> - Tous droits réservés</p>
</footer>

</html>