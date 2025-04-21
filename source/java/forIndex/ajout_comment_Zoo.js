 // Fonction pour gérer l'envoi du formulaire
 document.getElementById('ajoutComZooIndex').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission classique du formulaire

    // Récupération des valeurs du formulaire
    const pseudo = document.getElementById('pseudoComZooIndex').value;
    const avis = document.getElementById('commentaireComZooIndex').value;

    // Envoi des données via fetch en POST
    fetch('../source/php/forIndex/ajout_comment_Zoo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'pseudo': pseudo,
            'avis': avis
        })
    })
    .then(response => response.json())  // Convertir la réponse en JSON
    .catch(error => {
        document.getElementById('responseMessage').textContent = "Erreur lors de l'envoi du commentaire.";
    });
});
