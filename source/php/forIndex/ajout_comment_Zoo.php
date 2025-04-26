<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';
require_once SOURCE_PATH . '/php/forIndex/ajout_comment_Zoo.php';

use MongoDB\BSON\UTCDateTime; 
try {
    $mongo = new Mongo();
    $collection = $mongo->getCollection('Arcadia', 'commentairesZoo');

    // Sécurisation des champs
    $pseudo = htmlspecialchars($_POST['pseudo'] ?? '', ENT_QUOTES, 'UTF-8');
    $avis = htmlspecialchars($_POST['avis'] ?? '', ENT_NOQUOTES, 'UTF-8');

    if (!empty($pseudo) && !empty($avis)) {
        $commentaire = [
            'pseudo' => $pseudo,
            'avis' => $avis,
            'date' => new UTCDateTime(),  
            'valide' => false
        ];

        $collection->insertOne($commentaire);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue.']);
}
