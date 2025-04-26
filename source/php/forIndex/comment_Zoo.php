<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';

try {
    $mongo = new Mongo();
    $collection = $mongo->getCollection('Arcadia', 'commentairesZoo');

    // Récupérer les commentaires sélectionnés (3 plus récents et validés)
    $commentairesZoo = $collection->find(
        ['valide' => true],
        [
            'sort' => ['date' => -1],
            'limit' => 3
        ]
    );

    $commentaires = []; // création d'un tableau pour l'envoi en JS
    foreach ($commentairesZoo as $commentaire) {
        $commentaires[] = [
            'nom' => $commentaire['pseudo'],
            'texte' => $commentaire['avis']
        ];
    }

    // Envoyer les commentaires en JS
    echo json_encode($commentaires);

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
