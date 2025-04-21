<?php
function validateFoodForm($data) {
    $errors = [];

    if (empty($data['animal_id'])) {
        $errors[] = "L'animal doit être sélectionné.";
    }
    if (empty($data['date'])) {
        $errors[] = "La date est obligatoire.";
    }
    if (empty($data['nourriture'])) {
        $errors[] = "Le type de nourriture est obligatoire.";
    }
    if (empty($data['quantite']) || $data['quantite'] <= 0) {
        $errors[] = "La quantité doit être un nombre positif.";
    }
    return $errors;
}
