<?php
// arcadia_backend/config/Mongo.php

use MongoDB\Client;
use MongoDB\Exception\Exception;

class Mongo {
    private Client $client;

    public function __construct() {
        try {
            // Utilise la variable d'environnement MONGODB_URI
            $this->client = new Client(
                getenv('MONGODB_URI'),
                [],
                ['connectTimeoutMS' => 3000] // timeout rapide en cas d'erreur
            );
        } catch (\Exception $e) {
            error_log('Erreur connexion MongoDB : ' . $e->getMessage());
            die('Erreur critique : Impossible de se connecter à la base de données.');
        }
    }

    public function getCollection(string $db, string $collectionName) {
        return $this->client->$db->$collectionName;
    }
}
