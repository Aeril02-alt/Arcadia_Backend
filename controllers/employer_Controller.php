<?php
// ===========================================================
// Fichier : employer_Controller.php
// Objectif : Contrôleur pour la gestion de l'alimentation des animaux
// ===========================================================

require_once '../config/db_config.php';
require_once '../config/For_User/Animals_Control.php';
require_once '../config/For_User/Service_Control.php';
require_once '../config/Functions/ValideFoodForm.php';
require_once '../source/php/views/messages.php';
require_once '../config/repository/Animal_Repository.php';
require_once '../config/repository/Service_Repository.php';

$errors = [];
$success = [];

// Traitement de l'ajout de nourriture
if (isset($_POST['ajouter_nourriture'])) {
    // Validation des champs du formulaire via une fonction dédiée
    $errors = validateFoodForm($_POST);

    if (empty($errors)) {
        try {
            // Requête préparée pour insérer la consommation d'aliment
            $stmt = $pdo->prepare(
                "INSERT INTO alimentation (animal_id, date_passage, nourriture, grammage_nourriture) 
                 VALUES (:animal_id, :date_passage, :nourriture, :quantite)"
            );

            $stmt->execute([
                'animal_id' => $_POST['animal_id'],
                'date_passage' => $_POST['date'],
                'nourriture' => $_POST['nourriture'],
                'quantite' => $_POST['quantite']
            ]);

            $success[] = "Consommation ajoutée avec succès.";
        } catch (PDOException $e) {
            // Message d'erreur protégé contre XSS via htmlspecialchars
            $errors[] = "Erreur lors de l'ajout : " . htmlspecialchars($e->getMessage());
        }
    }
}

// Récupération des données animales enrichies de leurs rapports
$animaux_data_raw = getAnimalsWithReports($pdo);
$animaux_data = [];

// Transformation en structure par animal avec liste de rapports
foreach ($animaux_data_raw as $row) {
    $id = $row['animal_id'];

    if (!isset($animaux_data[$id])) {
        $animaux_data[$id] = [
            'prenom' => $row['prenom'],
            'etat' => $row['etat'],
            'race_label' => $row['race_label'],
            'rapports' => []
        ];
    }

    if (!empty($row['rapport_date'])) {
        $animaux_data[$id]['rapports'][] = [
            'date' => $row['rapport_date'],
            'detail' => $row['detail']
        ];
    }
}

// Tri décroissant des rapports par date pour chaque animal
foreach ($animaux_data as &$animal) {
    usort($animal['rapports'], function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}
unset($animal); // Nettoyage référence

// Récupération des services et de la liste des animaux pour affichage ou formulaire
$services = getServices($pdo);
$animalList = getAnimalList($pdo);
?>
