<?php
// updatePresentationZoo.php

// Autoriser uniquement les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /employer.php?update=fail');
    exit;
}

// Charger l'initialisation et la connexion MongoDB
require_once __DIR__ . '/../../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php';

try {
    $mongo = new Mongo();
    $collection = $mongo->getCollection('Arcadia', 'presentationsZoo');

    if (!isset($_POST['texte']) || empty(trim($_POST['texte']))) {
        header('Location: /employer.php?update=fail');
        exit;
    }

    $nouveauContenu = trim($_POST['texte']);

    $result = $collection->updateOne(
        ['presentationId' => '1'],
        ['$set' => ['texte' => $nouveauContenu]],
        ['upsert' => true]
    );

    if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
        header('Location: /employer.php?update=success');
        exit;
    } else {
        header('Location: /employer.php?update=fail');
        exit;
    }

} catch (Exception $e) {
    header('Location: /employer.php?update=fail');
    exit;
}