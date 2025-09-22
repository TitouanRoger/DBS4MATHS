<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/inscription.css">
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
        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <label>Nom</label>
        <input type="text" name="nom" placeholder="Nom" required>
        <label>Mot de passe</label>
        <div class="password-container">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <a href="index.php" class="menu">Déjà inscrit ?</a>
        <br>
        <button type="submit" name="inscription">Inscription</button>
    </form>
    <?php
    function inscription()
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

        if (isset($_POST['inscription'])) {
            $mail = htmlspecialchars($_POST['mail']);
            $prenom = ucfirst(strtolower(htmlspecialchars($_POST['prenom'])));
            $nom = strtoupper(htmlspecialchars($_POST['nom']));
            $password = htmlspecialchars($_POST['password']);

            // Vérification du mot de passe
            if (
            strlen($password) < 12 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
            ) {
            echo '<div class="message error">Le mot de passe doit faire au moins 12 caractères, contenir une majuscule, un chiffre et un caractère spécial.</div>';
            exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE mail = :mail");
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            $count = $sql->fetchColumn();

            if ($count > 0) {
            echo '<div class="message error">Le mail existe déjà !</div>';
            exit;
            }

            $sql = $db->prepare("INSERT INTO utilisateurs (mail, prenom, nom, password, password_changed) VALUES (:mail, :prenom, :nom, :password, 0)");
            $sql->bindParam(':mail', $mail);
            $sql->bindParam(':prenom', $prenom);
            $sql->bindParam(':nom', $nom);
            $sql->bindParam(':password', $hashed_password);
            $sql->execute();

            echo '<div class="message success">Inscription réussie !</div>';
            header("Refresh: 2; URL=index.php");
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        inscription();
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