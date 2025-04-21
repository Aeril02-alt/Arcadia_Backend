<?php
require '../vendor/autoload.php';

use MongoDB\Client;

$mongoClient = new Client("mongodb://localhost:27017");
$mongoCollection = $mongoClient->Arcadia->consultations;

return $mongoCollection;
