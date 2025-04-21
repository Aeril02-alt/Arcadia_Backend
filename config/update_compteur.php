<?php
// Connexion à MongoDB
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->zoo->consultations;

// Récupérer les données envoyées en POST
$data = json_decode(file_get_contents('php://input'), true);
$animalId = $data['animalId'];

// Incrémenter le compteur pour cet animal
$result = $collection->updateOne(
    ['animal_id' => $animalId],
    ['$inc' => ['consultations' => 1]]
);

if ($result->getModifiedCount() === 1) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
