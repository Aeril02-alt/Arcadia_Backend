<?php
// ============================
// Fichier : fetch_services.php (version full PHP sans JSON)
// Objectif : Fournir une fonction pour récupérer tous les services
// ============================

function getServices(PDO $pdo): array {
    try {
        // Requête SQL pour récupérer tous les services
        $sql = "SELECT * FROM service ORDER BY service_id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Journalisation de l’erreur pour le serveur
        error_log("Erreur lors de la récupération des services : " . $e->getMessage());

        // Retour d’un tableau vide en cas d’erreur
        return [];
    }
}
