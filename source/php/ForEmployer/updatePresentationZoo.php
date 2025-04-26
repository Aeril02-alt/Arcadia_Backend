<?php
// updatePresentationZoo.php

// Autoriser uniquement les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Charger l'initialisation et la connexion MongoDB
require_once __DIR__ . '/../../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';

try {
    // Connexion à la base de données MongoDB
    $collection = (new Mongo())->connect()->presentationZoo;

    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier que 'contenu' est présent
    if (!isset($data['contenu']) || empty(trim($data['contenu']))) {
        http_response_code(400); // Mauvaise requête
        echo json_encode(['error' => 'Le contenu est obligatoire.']);
        exit;
    }

    // Définir le nouveau contenu
    $nouveauContenu = trim($data['contenu']);

    // Mettre à jour ou insérer (upsert) le contenu
    $result = $collection->updateOne(
        ['_id' => 'presentation'], // Utiliser un identifiant fixe
        ['$set' => ['contenu' => $nouveauContenu]],
        ['upsert' => true]
    );

    // Vérification du succès
    if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
        echo json_encode(['success' => 'Présentation mise à jour avec succès.']);
    } else {
        echo json_encode(['message' => 'Aucun changement détecté.']);
    }

} catch (Exception $e) {
    http_response_code(500); // Erreur serveur
    echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>
