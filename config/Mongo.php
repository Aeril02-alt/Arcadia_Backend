<?php
// arcadia_backend/config/Mongo.php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Exception\Exception;

class Mongo {
    private Client $client;

    public function __construct() {
        $this->client = new Client("mongodb://localhost:27017");
    }

    public function getCollection(string $db, string $collectionName) {
        return $this->client->$db->$collectionName;
    }
}
