<?php
// test_connection.php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/Mongo.php';

try {
    $m = new Mongo();
    echo "✅ Connexion OK !\n";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : ", $e->getMessage(), "\n";
}
