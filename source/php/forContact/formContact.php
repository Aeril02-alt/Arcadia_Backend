<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';

try {
    // Connexion MongoDB
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongo->Arcadia->ContactForms;

    // Sécurisation contre les injections et validation des entrées
    $pseudo = htmlspecialchars(trim($_POST['pseudo']), ENT_NOQUOTES, 'UTF-8');
    $avis = htmlspecialchars(trim($_POST['avis']), ENT_NOQUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
             ? htmlspecialchars($_POST['email'], ENT_NOQUOTES, 'UTF-8')
             : null;

    // Vérification des champs non vides et email valide
    if (!empty($pseudo) && !empty($avis) && !empty($email)) {
        $commentaire = [
            'pseudo' => $pseudo,
            'avis' => $avis,
            'email' => $email,
            'date' => (new DateTimeImmutable())->format(DATE_ATOM),
            'valide' => false
        ];

        // Insertion dans MongoDB
        $collection->insertOne($commentaire);

        echo json_encode(['success' => true]);
    } else {
        // Erreur : champ manquant
        echo json_encode(['error' => 'Veuillez remplir tous les champs avec un email valide.']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());  // Log de l'erreur pour le suivi
    echo json_encode(['error' => 'Une erreur est survenue.']);  // Message d'erreur générique
}
