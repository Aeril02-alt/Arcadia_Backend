<?php

header('Content-Type: application/json');

require '../../../vendor/autoload.php';// le lien fonctionne car il prend en source de depart comment_Zoo.js

try{

    $mongo = new MongoDB\Client("mongodb://localhost:27017");

    $collection = $mongo->Arcadia->commentairesZoo;

// Récupérer les commentaire selection (les 3 plus recent et valider)
$commentairesZoo = $collection->find(
    ['valide' => true],
    [
        'sort' => ['date' => -1],
        'limit' => 3
    ]);

    $commentaires = []; //creation en tableau pour l'envoie en JS
    foreach ($commentairesZoo as $commentaire) {
        $commentaires[] = [
            'nom' => $commentaire['pseudo'],
            'texte' => $commentaire['avis'],
        ];
    }
    // envoyer les commentaires en JS
    echo json_encode($commentaires);
}
catch(Exception $e){
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
