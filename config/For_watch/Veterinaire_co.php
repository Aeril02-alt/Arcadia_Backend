<?php
// ============================================
// Fichier : Veterinaire_co.php
// Objectif : Fonctions de récupération des données pour les vétérinaires
// ============================================

// Récupère la liste des habitats depuis la base de données
function getHabitats(PDO $pdo): array {
    try {
        $sql = "SELECT * FROM habitat";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getHabitats : " . $e->getMessage());
        return [];
    }
}

// Récupère la liste des états possibles (ex : santé des animaux)
function getEtats(PDO $pdo): array {
    try {
        $sql = "SELECT * FROM etat";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getEtats : " . $e->getMessage());
        return [];
    }
}

// Récupère tous les rapports d'un animal donné (par son ID)
function getRapportsByAnimal(PDO $pdo, int $animal_id): array {
    try {
        $stmt = $pdo->prepare("SELECT * FROM rapport_veterinaire WHERE animal_id = ?");
        $stmt->execute([$animal_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getRapportsByAnimal : " . $e->getMessage());
        return [];
    }
}
