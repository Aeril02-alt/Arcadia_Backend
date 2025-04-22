<?php
// ==============================================
// Fichier : ValideFoodForm.php
// Objectif : Valider les champs du formulaire de repas
// ==============================================

function validateFoodForm(array $data): array {
    $errors = [];

    // Validation de l'ID de l'animal (doit être un entier positif)
    $animal_id = filter_var($data['animal_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$animal_id) {
        $errors[] = "L'animal doit être sélectionné.";
    }

    // Validation de la date (non vide et au bon format YYYY-MM-DD)
    $date = trim($data['date'] ?? '');
    if (empty($date)) {
        $errors[] = "La date est obligatoire.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "Le format de la date est invalide (attendu : AAAA-MM-JJ).";
    }

    // Validation du type de nourriture
    $nourriture = trim($data['nourriture'] ?? '');
    if (empty($nourriture)) {
        $errors[] = "Le type de nourriture est obligatoire.";
    }

    // Validation de la quantité (doit être un nombre positif)
    $quantite = filter_var($data['quantite'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($quantite === false || $quantite <= 0) {
        $errors[] = "La quantité doit être un nombre positif.";
    }

    return $errors;
}
