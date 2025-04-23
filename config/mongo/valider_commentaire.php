<?php
require_once __DIR__ . '/../Mongo.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['valide'])) {
    try {
        $mongo = new Mongo();
        $collection = $mongo->getCollection('Arcadia', 'commentairesZoo');

        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($data['id'])],
            ['$set' => ['valide' => (bool)$data['valide']]]
        );

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}
