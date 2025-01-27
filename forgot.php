<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mot de Passe Oublié</title>
        <link rel="icon" href="images/logo.jpg">
    </head>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .logo {
            margin-bottom: 20px;
            width: 150px;
            height: auto;
        }

        .container {
            background: #FA193B;
            text-align: center;
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: white;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid white;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 20px;
            background-color: #FA193B;
            color: white;
            font-size: 1em;
            cursor: pointer;
            background: transparent;
            border: 1px solid white;
        }

        .button:hover {
            background-color: rgb(230, 23, 54);
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
            margin-top: 20px;
        }

        .message.success {
            background-color: #4CAF50;
        }

        .message.error {
            background-color: #FA193B;
        }

        @media (max-width: 435px) {
            .container {
                width: 90%;
            }

            .message {
                width: 90%;
            }

            .logo {
                width: 120px;
            }
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
    <body>
        <img src="images/logo.jpg" alt="Logo" class="logo">
        <div class="container">
            <form method="post" class="form">
                <label>Mail</label>
                <input type="email" name="mail" placeholder="Votre email" required>
                <button type="submit" class="button" name="reset">Réinitialiser le mot de passe</button>
                <button type="button" class="button" onclick="window.location.href='index.php';">Retour</button>
            </form>
        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
            $mail = htmlspecialchars($_POST['mail']);
            try {
                $db = new PDO('mysql:host=localhost;dbname=dbs4maths', 'root', '');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
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

        function sendPasswordResetEmail($email) {
            $to = $email;
            $subject = 'Réinitialisation du mot de passe - DBS4MATHS';
            $message = '<p>Bonjour,</p><p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href="http://localhost/dbs4maths/reset.php?email=' . $to . '">Réinitialiser le mot de passe</a></p><p>Cordialement,<br>DBS4MATHS</p>';
            $headers = array(
                'MIME-Version' => '1.0',
                'Content-Type' => 'text/html; charset=UTF-8',
                'From' => 'no-reply@dbs4maths.fr',
                'Reply-To' => 'no-reply@dbs4maths.fr'
            );
        
            return mail($to, $subject, $message, $headers);
        }
        ?>
    </body>
    <footer>
    <div class="footer">
        <p>&copy; 2025 DBS4MATHS - Tous droits reservé</p>
    </div>
    </footer>
</html>
