<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/forgot.css">
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
    ?>
</head>

<body>
    <img src="images/logo.jpg" alt="Logo">
    <form method="post">
        <label>Mail</label>
        <input type="email" name="mail" placeholder="Votre email" required>
        <button type="submit" name="reset">Réinitialiser le mot de passe</button>
        <button type="button" onclick="window.location.href='index.php';">Retour</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
        $mail = htmlspecialchars($_POST['mail']);

        $sql = $db->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
        $sql->bindParam(':mail', $mail);
        $sql->execute();

        if ($sql->rowCount() == 1) {
            if (sendPasswordResetEmail($mail)) {
                echo '<div class="message success">Un email de réinitialisation a été envoyé.</div>';
            } else {
                echo '<div class="message error">Erreur lors de l\'envoi de l\'email.</div>';
            }
        } else {
            echo '<div class="message error">Le mail n\'existe pas.</div>';
        }
    }

    function sendPasswordResetEmail($email)
    {
        $token = bin2hex(random_bytes(16));
        $expiry = time() + 3600;

        try {
            $db = new PDO('mysql:host=localhost;dbname=dbs4maths', 'dbs4maths', 'dbs4maths');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

        $sql = $db->prepare("UPDATE utilisateurs SET reset_token = :token, reset_expiry = :expiry WHERE mail = :mail");
        $sql->bindParam(':token', $token);
        $sql->bindParam(':expiry', $expiry);
        $sql->bindParam(':mail', $email);

        if ($sql->execute()) {
            $to = $email;
            $subject = 'Réinitialisation du mot de passe - DBS4MATHS';
            $message = '<p>Bonjour,</p><p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href="https://www.dbs4maths.adkynet.eu/reset.php?token=' . $token . '">Réinitialiser le mot de passe</a></p><p>Cordialement,<br>DBS4MATHS</p>';
            $headers = array(
                'MIME-Version' => '1.0',
                'Content-Type' => 'text/html; charset=UTF-8',
                'From' => 'ne-pas-repondre@dbs4maths.adkynet.eu',
                'Reply-To' => 'ne-pas-repondre@dbs4maths.adkynet.eu'
            );

            return mail($to, $subject, $message, $headers);
        }
        return false;
    }
    ?>
</body>
<footer>
    <?php
    $sql = $db->prepare("SELECT version FROM applications WHERE nom = 'DBS4MATHS'");
    $sql->execute();
    $app = $sql->fetch(PDO::FETCH_ASSOC);
    ?>
    <p>&copy; <?php echo date('Y'); ?> DBS4MATHS -
        V<?php echo htmlspecialchars($app['version'], ENT_QUOTES, 'UTF-8'); ?> - Tous droits réservés</p>
</footer>

</html>