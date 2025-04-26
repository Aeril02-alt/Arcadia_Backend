<?php
// arcadia_backend/config/Mongo.php

use MongoDB\Client;
use MongoDB\Exception\Exception;

class Mongo {
    private Client $client;

    public function __construct() {
        $this->client = new Client("mongodb://mongo:27017");
    }

    public function getCollection(string $db, string $collectionName) {
        return $this->client->$db->$collectionName;
    }
}
