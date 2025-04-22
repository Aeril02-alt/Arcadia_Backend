<?php
// ==================================================
// Fichier : Animal_Repository.php
// Objectif : Fournir des fonctions d'accès aux données animales
// ==================================================

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../Functions/ValideFoodForm.php';

// Récupère tous les animaux avec leurs rapports vétérinaires (dernier en date)
function getAnimalsWithReports(PDO $pdo): array {
    try {
        $sql = "
            SELECT
                a.animal_id,
                a.prenom,
                a.etat,
                r.label AS race_label,
                rv.date AS rapport_date,
                rv.detail
            FROM animal a
            LEFT JOIN race r ON a.race_id = r.race_id
            LEFT JOIN rapport_veterinaire rv ON a.animal_id = rv.animal_id
            ORDER BY a.animal_id, rv.date DESC
        ";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getAnimalsWithReports : " . $e->getMessage());
        return [];
    }
}

// Récupère uniquement l'ID et le prénom de tous les animaux (pour liste déroulante, etc.)
function getAnimalList(PDO $pdo): array {
    try {
        $sql = "SELECT animal_id, prenom FROM animal";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getAnimalList : " . $e->getMessage());
        return [];
    }
}
?>
