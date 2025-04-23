<?php
// Utilise __DIR__ pour remonter de 2 niveaux et inclure mongo.php
require_once __DIR__ . '/../Mongo.php';

$mongo = new Mongo();
$collection = $mongo->getCollection('arcadia_zoo', 'commentaires');

// Récupérer les 3 derniers commentaires validés
$comments = $collection->find(
    ['valide' => true], // Filtrer par commentaires validés
    [
        'sort' => ['date' => -1], // Trier par date décroissante
        'limit' => 3 // Limiter à 3 commentaires
    ]
)->toArray();

echo json_encode($comments);
