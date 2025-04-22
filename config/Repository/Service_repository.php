<?php
// ================================================
// Fichier : Service_repository.php
// Objectif : Récupérer tous les services depuis la base
// ================================================

function getServices(PDO $pdo): array {
    try {
        $sql = "SELECT * FROM service";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Journalise discrètement l'erreur en cas d'échec de requête
        error_log("Erreur getServices : " . $e->getMessage());
        return [];
    }
}
