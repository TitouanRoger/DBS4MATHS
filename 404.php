<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 404 - Page non trouvée</title>
    <link rel="icon" href="images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/404.css">
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

<body class="bg-[var(--background-color)] text-[var(--text-color)] flex items-center justify-center min-h-screen">
    <div class="text-center">
        <img alt="Logo" class="mx-auto mb-8" width="400" src="images/logo.jpg" />
        <h1 class="text-6xl font-bold text-[var(--primary-color)] mb-4">404</h1>
        <p class="text-2xl mb-8">Oups! La page que vous cherchez n'existe pas.</p>
        <a class="text-white bg-[var(--primary-color)] hover:bg-[var(--secondary-color)] font-bold py-2 px-4 rounded"
            href="index.php">Retour à l'accueil</a>
    </div>
</body>

</html>