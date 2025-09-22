<!DOCTYPE html>

<head lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
        <link rel="icon" href="images/logo.jpg">
        <link rel="stylesheet" href="css/change_password.css">
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
    <?php if (isset($_SESSION['mail'])): ?>
        <img src="images/logo.jpg" alt="Logo">
        <form method="POST" class="form">
            <label for="new_password">Nouveau mot de passe</label>
            <div class="password-container">
                <input type="password" name="new_password" id="new_password" required>
                <span class="toggle-password1" onclick="togglePassword1()">👁️</span>
            </div>
            <label for="confirm_password">Confirmez le mot de passe</label>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password2" onclick="togglePassword2()">👁️</span>
            </div>
            <button type="submit" name="change_password">Changer le mot de passe</button>
        </form>
    <?php else:
        echo '<div class="message" style="background-color: #f44336;">Vous n\'êtes pas connecté.</div>';
        setcookie(session_name(), '', 0, '/');
        header("Refresh: 2; URL=index.php");
    endif;
    ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_mail = $_SESSION['mail'];

        // Vérification de la complexité du mot de passe
        $errors = [];
        if (strlen($new_password) < 12) {
            $errors[] = "Le mot de passe doit contenir au moins 12 caractères.";
        }
        if (!preg_match('/[A-Z]/', $new_password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match('/\d/', $new_password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
        }
        if (!preg_match('/[\W_]/', $new_password)) {
            $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="message error">' . htmlspecialchars($error) . '</div>';
            }
        } elseif ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = $db->prepare("UPDATE utilisateurs SET password = :password, password_changed = TRUE WHERE mail = :mail");
            $sql->bindParam(':password', $hashed_password);
            $sql->bindParam(':mail', $user_mail);

            if ($sql->execute()) {
                echo '<div class="message success">Mot de passe changé avec succès !</div>';
                header("Refresh: 2; URL=session.php?page=accueil");
                exit;
            } else {
                echo '<div class="message error">Erreur lors du changement de mot de passe.</div>';
            }
        } else {
            echo '<div class="message error">Les deux mots de passe ne sont pas identiques.</div>';
        }
    }
    ?>
    <script>
        function togglePassword1() {
            var password = document.querySelector("input[name='new_password']");
            var eyeIcon = document.querySelector(".toggle-password1");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.textContent = "🙈";
            } else {
                password.type = "password";
                eyeIcon.textContent = "👁️";
            }
        }

        function togglePassword2() {
            var password = document.querySelector("input[name='confirm_password']");
            var eyeIcon = document.querySelector(".toggle-password2");
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