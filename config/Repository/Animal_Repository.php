<?php
require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../Functions/ValideFoodForm.php';

function getAnimalsWithReports($pdo) {
    $sql = "SELECT a.animal_id, a.prenom, a.etat, r.label AS race_label, rv.date AS rapport_date, rv.detail
            FROM animal a
            LEFT JOIN race r ON a.race_id = r.race_id
            LEFT JOIN rapport_veterinaire rv ON a.animal_id = rv.animal_id
            ORDER BY a.animal_id, rv.date DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getAnimalList($pdo) {
    $sql = "SELECT animal_id, prenom FROM animal";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
