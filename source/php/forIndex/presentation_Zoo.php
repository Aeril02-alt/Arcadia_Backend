<?php
// Presentation_Zoo.php
require_once __DIR__ . '/../../../config/init.php';
require_once CONFIG_PATH . '/Mongo.php'; // Connexion via classe Mongo

try {
    $mongo = new Mongo();
    $collection = $mongo->getCollection('Arcadia', 'presentationsZoo');

    // Récupérer le texte de présentation
    $presentation = $collection->findOne(['presentationId' => '1']);

    echo '<div id="presentationZooIndex">';
    if ($presentation && isset($presentation['texte'])) {
        echo '<p>' . nl2br(htmlspecialchars($presentation['texte'])) . '</p>';
    } else {
        echo '<p>Pas de présentation disponible.</p>';
    }
    echo '</div>';
    
} catch (Exception $e) {
    echo '<div id="presentationZooIndex">';
    echo '<p>Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
