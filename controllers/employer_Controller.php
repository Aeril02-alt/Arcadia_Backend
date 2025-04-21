<?php
require_once '../config/db_config.php';
require_once '../config/For_User/Animals_Control.php';
require_once '../config/For_User/Service_Control.php';
require_once '../config/Functions/ValideFoodForm.php';
require_once '../source/php/views/messages.php';
require_once '../config/repository/Animal_Repository.php';
require_once '../config/repository/Service_Repository.php';

$errors = [];
$success = [];

if (isset($_POST['ajouter_nourriture'])) {
    $errors = validateFoodForm($_POST);
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO alimentation (animal_id, date_passage, nourriture, grammage_nourriture) VALUES (:animal_id, :date_passage, :nourriture, :quantite)");
            $stmt->execute([
                'animal_id' => $_POST['animal_id'],
                'date_passage' => $_POST['date'],
                'nourriture' => $_POST['nourriture'],
                'quantite' => $_POST['quantite']
            ]);
            $success[] = "Consommation ajoutée avec succès.";
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'ajout : " . htmlspecialchars($e->getMessage());
        }
    }
}

$animaux_data_raw = getAnimalsWithReports($pdo);
$animaux_data = [];

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

// Tri décroissant des rapports pour chaque animal
foreach ($animaux_data as &$animal) {
    usort($animal['rapports'], function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}
unset($animal);

$services = getServices($pdo);
$animalList = getAnimalList($pdo);
