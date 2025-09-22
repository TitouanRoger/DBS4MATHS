<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $sujet = $_POST['sujet'];
    $commentaire = $_POST['commentaire'];

    $to = 'contact@dbs4maths.adkynet.eu';
    $subject = $sujet;
    $message = '<p>' . $nom . ' ' . $prenom . '</p><p>' . $commentaire . '</p>';
    $headers = 'From: ' . $mail . "\r\n" .
        'Reply-To: ' . $mail . "\r\n" .
        'Content-type: text/html; charset=utf-8';

    if (mail($to, $subject, $message, $headers)) {
        echo '<div class="success">Le message a bien été envoyé !</div>';
    } else {
        echo '<div class="error">Erreur lors de l\'envoi du message !</div>';
    }
}
?>