<?php
// arcadia_backend/config/Mongo.php

use MongoDB\Client;

class Mongo {
    private Client $client;

    public function __construct() {
        // Charge l’autoloader si vous n’utilisez pas Composer/PSR-4
        require_once __DIR__ . '/../vendor/autoload.php';

        $uri = getenv('MONGODB_URI') ?: die("MONGODB_URI manquante\n");

        try {
            // On assigne bien à la propriété $this->client
            $this->client = new Client(
                $uri,
                [],
                ['connectTimeoutMS' => 3000]
            );
            echo "";
        } catch (\Exception $e) {
            die("Erreur connexion MongoDB : " . $e->getMessage() . "\n");
        }
    }

    public function getCollection(string $db, string $collectionName) {
        return $this->client
                    ->selectDatabase($db)
                    ->selectCollection($collectionName);
    }
}




// arcadia_backend/config/Mongo.php

/*use MongoDB\Client;
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
*/