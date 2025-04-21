<?php

require '../vendor/autoload.php'; // le lien fonctionne car il prend en source de depart index.php

$mongo = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongo->Arcadia->presentationsZoo;

// Récupérer le texte de présentation
$presentation = $collection->findOne(['presentationId' => '1']);

if ($presentation) {
    echo '<div id="presentationZooIndex">';
    echo '<p>' . $presentation['texte'] . '</p>';
    echo '</div>';
} else {
    echo '<div id="presentationZooIndex">';
    echo '<p>Pas de présentation disponible.</p>';
    echo '</div>';
}
?>
