<script>
    function envoyerMail(event) {
        event.preventDefault();
        var formData = new FormData(document.querySelector('form'));
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('.message').innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "php/send_contact.php", true);
        xhttp.send(formData);
    }
</script>

<link rel="stylesheet" href="css/contact.css">

<form method="post" onsubmit="envoyerMail(event);">
    <label>Nom</label>
    <input type="text" name="nom" required>
    <label>Prénom</label>
    <input type="text" name="prenom" required>
    <label>Adresse e-mail</label>
    <input type="email" name="mail" required>
    <label>Sujet</label>
    <input type="text" name="sujet" required>
    <label>Commentaire</label>
    <textarea name="commentaire" required></textarea>
    <button type="submit">Envoyer</button>
    <div class="message"></div>
</form>