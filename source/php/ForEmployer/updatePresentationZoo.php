<?php
require_once __DIR__ . '/../../../config/Mongo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['texte'])) {
    $mongo = new Mongo();
    $collection = $mongo->getCollection('Arcadia', 'presentationsZoo');

    $result = $collection->updateOne(
        ['presentationId' => '1'],
        ['$set' => ['texte' => $_POST['texte']]]
    );

    header("Location: ../../../page/employer.php?update=success");
    exit;
} else {
    header("Location: ../../../page/employer.php?update=fail");
    exit;
}
