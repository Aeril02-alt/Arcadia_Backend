<?php
// arcadia_backend/config/Mongo.php

use MongoDB\Client;

class Mongo {
    private Client $client;

    public function __construct() {
        // Charge l'autoloader si nécessaire
        require_once __DIR__ . '/../vendor/autoload.php';

        // Récupère l'URI depuis les variables d'environnement
         $uri = 'mongodb+srv://moerkerkeaxelprog:jB2xdVOzackAC3Im@cluster0.zx9bdsi.mongodb.net/arcadia?retryWrites=true&w=majority';

        // Vérifie que l'URI est définie
        if (!$uri) {
            die("Erreur critique : MONGODB_URI manquante dans les variables d'environnement.\n");
        }

        // Ajoute les options TLS nécessaires
        try {
            $this->client = new Client(
                $uri,
                ['tls' => true], // Force l'utilisation de TLS
                ['connectTimeoutMS' => 3000]
            );
   //         echo "Connexion réussie à MongoDB\n";
        } catch (\Exception $e) {
            error_log("Erreur connexion MongoDB : " . $e->getMessage());
            die("Erreur critique : Impossible de se connecter à la base de données.\n");
        }
    }

    public function getCollection(string $db, string $collectionName) {
        return $this->client
                    ->selectDatabase($db)
                    ->selectCollection($collectionName);
    }
}
