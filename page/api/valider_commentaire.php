<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ============================================
require_once __DIR__ . '/../../config/init.php';
//require_once CONFIG_PATH . '/mongo/valider_commentaire.php';
require_once CONFIG_PATH . '/Mongo.php';

use MongoDB\BSON\ObjectId;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['valide'])) {
    try {
        $mongo = new Mongo();
        $collection = $mongo->getCollection('Arcadia', 'commentairesZoo');

        file_put_contents('/tmp/update_debug.txt', print_r($data, true));

        $result = $collection->updateOne(
            ['_id' => new ObjectId($data['id'])],
            ['$set' => ['valide' => (bool)$data['valide']]]
        );

        echo json_encode([
            'success' => true,
            'matched' => $result->getMatchedCount(),
            'modified' => $result->getModifiedCount()
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}