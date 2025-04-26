<?php
// =============================================
// Fichier : update_compteur.php
// Objectif : Incrémenter le compteur de consultations pour un animal donné
// =============================================

require_once __DIR__ . '/init.php';

use MongoDB\Client;

// Spécifie que la réponse sera au format JSON
header('Content-Type: application/json');

try {
    // Connexion à MongoDB
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->zoo->consultations;

    // Lecture et décodage des données JSON envoyées en POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifie que l'animalId est bien fourni et non vide
    if (!isset($data['animalId']) || empty($data['animalId'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Paramètre animalId manquant.']);
        exit;
    }

    $animalId = $data['animalId'];

    // Mise à jour (incrément du compteur de consultations)
    $result = $collection->updateOne(
        ['animal_id' => $animalId],
        ['$inc' => ['consultations' => 1]]
    );

    // Retourne une réponse en fonction du résultat
    if ($result->getModifiedCount() === 1) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Animal non trouvé ou compteur inchangé.']);
    }

// On capture ici \Throwable plutôt que Exception pour couvrir tous les types d'erreurs,
// y compris les erreurs fatales qui ne sont pas des instances d'Exception (comme TypeError)
} catch (\Throwable $e) {
    error_log("Erreur MongoDB : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Erreur serveur lors de la mise à jour."]);
}
