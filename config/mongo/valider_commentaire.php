<?php
ini_set('display_errors', 0); // Pour ne pas afficher d'erreurs HTML
error_reporting(E_ALL);

header('Content-Type: application/json');

// ============================================
require_once __DIR__ . '/../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['valide'])) {
    try {
        $mongo = new Mongo();
        $collection = $mongo->getCollection('Arcadia', 'commentairesZoo');

        $collection->updateOne(
            ['_id' => $data['id']],
            ['$set' => ['valide' => (bool)$data['valide']]]
        );

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}
