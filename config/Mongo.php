<?php
// ===============================================
// Fichier : Mongo.php
// Objectif : Initialiser la connexion MongoDB et retourner la collection 'consultations'
// ===============================================

require_once '../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Exception\Exception;

try {
    // Connexion à l'instance MongoDB locale
    $mongoClient = new Client("mongodb://localhost:27017");

    // Sélection de la base 'Arcadia' et de la collection 'consultations'
    $mongoCollection = $mongoClient->Arcadia->consultations;

    return $mongoCollection;
    
} catch (\Throwable $e) {
    // Enregistre l'erreur dans les logs serveur sans l'afficher à l'écran
    error_log("Erreur MongoDB : " . $e->getMessage());
    // Message utilisateur générique
    echo "Impossible de se connecter à la base de données.";
    exit;
}
