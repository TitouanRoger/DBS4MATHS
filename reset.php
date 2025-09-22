<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/reset.css">
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
    <img src="images/logo.jpg" alt="Logo" class="logo">
    <form method="post">
        <label for="new_password">Nouveau mot de passe</label>
        <div class="password-container">
            <input type="password" name="new_password" id="new_password" required>
            <span class="toggle-password1" onclick="togglePassword1()">👁️</span>
        </div>
        <label for="confirm_password">Confirmer le mot de passe</label>
        <div class="password-container">
            <input type="password" name="confirm_password" id="confirm_password" required>
            <span class="toggle-password2" onclick="togglePassword2()">👁️</span>
        </div>
        <button type="submit" name="reset_password">Réinitialiser le mot de passe</button>
    </form>
    <?php
    if (isset($_GET['token'])) {
        $token = htmlspecialchars($_GET['token']);

        $sql = $db->prepare("SELECT * FROM utilisateurs WHERE reset_token = :token AND reset_expiry > :now");
        $sql->bindValue(':token', $token, PDO::PARAM_STR);
        $sql->bindValue(':now', time(), PDO::PARAM_INT);
        $sql->execute();

        if ($sql->rowCount() == 1) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
                $new_password = htmlspecialchars($_POST['new_password']);
                $confirm_password = htmlspecialchars($_POST['confirm_password']);

                // Vérification de la complexité du mot de passe
                $errors = [];
                if (strlen($new_password) < 12) {
                    $errors[] = "Le mot de passe doit contenir au moins 12 caractères.";
                }
                if (!preg_match('/[A-Z]/', $new_password)) {
                    $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule.";
                }
                if (!preg_match('/[0-9]/', $new_password)) {
                    $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
                }
                if (!preg_match('/[\W_]/', $new_password)) {
                    $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
                }

                if ($new_password !== $confirm_password) {
                    $errors[] = "Les mots de passe ne correspondent pas.";
                }

                if (empty($errors)) {
                    $user = $sql->fetch();
                    $email = $user['mail'];

                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update = $db->prepare("UPDATE utilisateurs SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE mail = :mail");
                    $update->bindParam(':password', $hashed_password);
                    $update->bindParam(':mail', $email);

                    if ($update->execute()) {
                        echo '<div class="message success">Votre mot de passe a été réinitialisé avec succès.</div>';
                        header("Refresh: 2; URL=index.php");
                        exit;
                    } else {
                        echo '<div class="message error">Erreur lors de la réinitialisation du mot de passe.</div>';
                    }
                } else {
                    foreach ($errors as $error) {
                        echo '<div class="message error">' . htmlspecialchars($error) . '</div>';
                    }
                }
            }
        } else {
            echo '<div class="message error">Le lien de réinitialisation est invalide ou a expiré.</div>';
        }
    } else {
        echo '<div class="message error">Token manquant.</div>';
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
    $application = $sql->fetch(PDO::FETCH_ASSOC);
    ?>
    <p>&copy; <?php echo date('Y'); ?> DBS4MATHS -
        V<?php echo htmlspecialchars($application['version'], ENT_QUOTES, 'UTF-8'); ?> - Tous droits réservés</p>
</footer>

</html>