<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
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
            border: none;
            border-radius: 20px;
            background-color: #FA193B;
            color: white;
            font-size: 1em;
            cursor: pointer;
        }

        .button:hover {
            background-color: rgb(230, 23, 54);
            color: white;
            border: 1px solid white;
        }

        .connexion {
            background: transparent;
            border: 1px solid white;
        }

        .forgot {
            text-decoration: none;
            color: white;
            font-size: 0.8em;
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
                <input type="email" name="mail" placeholder="Mail" required>
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <a href="forgot.php" class="forgot">Mot de passe oublié ?</a>
                <button type="submit" class="button connexion" name="connexion">Connexion</button>
            </form>
        </div>
        <?php
        function connexion() {
            try {
                $db = new PDO('mysql:host=localhost;dbname=dbs4maths', 'root', '');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }

            if (isset($_POST['connexion'])) {
                $mail = htmlspecialchars($_POST['mail']);
                $password = htmlspecialchars($_POST['password']);
                $sql = $db->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
                $sql->bindParam(':mail', $mail);
                $sql->execute();

                if ($sql->rowCount() == 1) {
                    $sql = $db->prepare("SELECT password FROM utilisateurs WHERE mail = :mail");
                    $sql->bindParam(':mail', $mail);
                    $sql->execute();
                    $row = $sql->fetch();
                    $hashed_password = $row['password'];
                    session_start();

                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['mail'] = $mail;
                        echo '<div class="message success">Connexion réussie !</div>';
                        header("Refresh: 2; URL=session.php");
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
    </body>
    <footer>
    <div class="footer">
        <p>&copy; 2025 DBS4MATHS - Tous droits reservé</p>
    </div>
    </footer>
</html>
