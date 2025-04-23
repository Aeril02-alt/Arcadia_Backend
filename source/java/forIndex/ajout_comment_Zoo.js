// Ajout d'un commentaire sur la page d'accueil du zoo
// On écoute l'événement de soumission du formulaire
document.getElementById('ajoutComZooIndex').addEventListener('submit', function(event) {
    event.preventDefault();

    const pseudo = document.getElementById('pseudoComZooIndex').value;
    const avis = document.getElementById('commentaireComZooIndex').value;
    const messageZone = document.getElementById('responseMessage');

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
    .then(response => response.json())
    .then(data => {
        console.log("Réponse du serveur :", data); // pour tester
        if (data.success) {
            messageZone.textContent = "Commentaire bien envoyé !";
            messageZone.style.color = "green";
        } else {
            messageZone.textContent = data.message || "Erreur inconnue.";
            messageZone.style.color = "red";
        }
    });
});

