<?php

header('Content-Type: application/json');

require '../../vendor/autoload.php';// le lien fonctionne car il prend en source de depart ajout_comment_Zoo.js ( theorie a valider)

try{
$mongo = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongo->Arcadia->commentairesZoo;

// securite contre les injection
    $pseudo = htmlspecialchars($_POST['pseudo'], ENT_NOQUOTES, 'UTF-8');
    $avis = htmlspecialchars($_POST['avis'], ENT_NOQUOTES, 'UTF-8');

$commentaire = [
    'pseudo' => $pseudo,
    'avis' => $avis,
    'date' => (new DateTimeImmutable())->format(DATE_ATOM),
    'valide' => false
];
// si champ non vide => ok
    if(!empty($pseudo) && !empty($avis)){
          $collection->insertOne($commentaire);
            echo json_encode([
                'success' => true
    ]);
}else { // sinon erreur
    echo json_encode([
        'error' => 'Veuillez remplir tous les champs'
    ]);
}
}catch(Exception $e) {
    error_log($e->getMessage());  // Afficher le message d'erreur dans le fichier error.log
    echo json_encode([
        'error' => 'Une erreur est survenue.' // Afficher un message d'erreur
    ]);
}
