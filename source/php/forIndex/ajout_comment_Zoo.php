<?php

header('Content-Type: application/json');
require '../../../vendor/autoload.php';
require '../../../config/Mongo.php';


try {
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongo->Arcadia->commentairesZoo;

    // Sécurisation des champs
    $pseudo = htmlspecialchars($_POST['pseudo'] ?? '', ENT_QUOTES, 'UTF-8');
    $avis = htmlspecialchars($_POST['avis'] ?? '', ENT_NOQUOTES, 'UTF-8');

    if (!empty($pseudo) && !empty($avis)) {
        $commentaire = [
            'pseudo' => $pseudo,
            'avis' => $avis,
            'date' => new MongoDB\BSON\UTCDateTime(),
            'valide' => false
        ];

        $collection->insertOne($commentaire);

        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
        exit;
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue.']);
    exit;
}
