<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Notes</title>
    <link rel="stylesheet" href="css/simulation_notes.css">
</head>
<body class="calculmental-body">
    <div class="calculmental-container">
        
        <div class="form-group">
            <input type="text" id="matiere" placeholder="Matière">
            <input type="number" id="note" placeholder="Note" min="0" max="20" step="0.01">
            <input type="number" id="coefficient" placeholder="Coefficient" min="0.5" step="0.5" value="1">
            <button id="ajouter">Ajouter</button>
        </div>
        
        <table id="tableau-notes">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note</th>
                    <th>Coefficient</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="liste-notes">
                <!-- Les notes seront ajoutées ici dynamiquement -->
            </tbody>
        </table>
        
        <div class="moyenne-container">
            <h3>Moyenne Générale: <span id="moyenne-generale">0.00</span>/20</h3>
        </div>

        <button id="effacer-tout">Effacer toutes les données</button>
    </div>
    
    <script>// Variables globales
let notes = [];
const STORAGE_KEY = 'notesData';

// Éléments du DOM
const matiereInput = document.getElementById('matiere');
const noteInput = document.getElementById('note');
const coefficientInput = document.getElementById('coefficient');
const ajouterBtn = document.getElementById('ajouter');
const listeNotes = document.getElementById('liste-notes');
const moyenneGenerale = document.getElementById('moyenne-generale');
const effacerToutBtn = document.getElementById('effacer-tout');

// Chargement des données depuis localStorage au démarrage
function chargerDonnees() {
    const donneesEnregistrees = localStorage.getItem(STORAGE_KEY);
    if (donneesEnregistrees) {
        notes = JSON.parse(donneesEnregistrees);
        rafraichirAffichage();
    }
}

// Sauvegarde des données dans localStorage
function sauvegarderDonnees() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(notes));
}

// Ajouter une nouvelle note
function ajouterNote() {
    const matiere = matiereInput.value.trim();
    const note = parseFloat(noteInput.value);
    const coefficient = parseFloat(coefficientInput.value);
    
    // Validation des entrées
    if (matiere === '' || isNaN(note) || isNaN(coefficient)) {
        alert('Veuillez remplir tous les champs correctement');
        return;
    }
    
    if (note < 0 || note > 20) {
        alert('La note doit être comprise entre 0 et 20');
        return;
    }
    
    if (coefficient <= 0) {
        alert('Le coefficient doit être supérieur à 0');
        return;
    }
    
    // Ajout de la note à la liste
    const nouvelleNote = {
        id: Date.now(), // ID unique basé sur le timestamp
        matiere,
        note,
        coefficient
    };
    
    notes.push(nouvelleNote);
    
    // Réinitialisation des champs
    matiereInput.value = '';
    noteInput.value = '';
    coefficientInput.value = '1';
    
    // Mise à jour de l'affichage et sauvegarde
    rafraichirAffichage();
    sauvegarderDonnees();
    
    // Focus sur le champ matière pour le prochain ajout
    matiereInput.focus();
}

// Supprimer une note
function supprimerNote(id) {
    notes = notes.filter(note => note.id !== id);
    rafraichirAffichage();
    sauvegarderDonnees();
}

// Calculer la moyenne générale
function calculerMoyenne() {
    if (notes.length === 0) return 0;
    
    let sommeNotesPonderees = 0;
    let sommeCoefficients = 0;
    
    notes.forEach(note => {
        sommeNotesPonderees += note.note * note.coefficient;
        sommeCoefficients += note.coefficient;
    });
    
    return sommeCoefficients > 0 ? sommeNotesPonderees / sommeCoefficients : 0;
}

// Rafraîchir l'affichage du tableau et de la moyenne
function rafraichirAffichage() {
    // Vider le tableau actuel
    listeNotes.innerHTML = '';
    
    // Ajouter chaque note au tableau
    notes.forEach(note => {
        const ligne = document.createElement('tr');
        ligne.innerHTML = `
            <td>${note.matiere}</td>
            <td>${note.note.toFixed(2)}</td>
            <td>${note.coefficient}</td>
            <td class="actions-cell">
                <button class="supprimer-btn" data-id="${note.id}">Supprimer</button>
            </td>
        `;
        listeNotes.appendChild(ligne);
        
        // Ajouter un effet de surbrillance sur les nouvelles entrées
        ligne.classList.add('highlighted');
        setTimeout(() => {
            ligne.classList.remove('highlighted');
        }, 1000);
    });
    
    // Calculer et afficher la moyenne générale
    const moyenne = calculerMoyenne();
    moyenneGenerale.textContent = moyenne.toFixed(2);
    
    // Attacher les événements aux boutons de suppression
    document.querySelectorAll('.supprimer-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.getAttribute('data-id'));
            supprimerNote(id);
        });
    });
}

// Effacer toutes les données
function effacerTout() {
    if (confirm('Êtes-vous sûr de vouloir effacer toutes les notes ? Cette action est irréversible.')) {
        notes = [];
        rafraichirAffichage();
        sauvegarderDonnees();
    }
}

// Événements
ajouterBtn.addEventListener('click', ajouterNote);
effacerToutBtn.addEventListener('click', effacerTout);

// Support de la touche Entrée pour ajouter une note
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            ajouterNote();
        }
    });
});

// Charger les données au démarrage
document.addEventListener('DOMContentLoaded', chargerDonnees);
</script>
</body>
</html>
