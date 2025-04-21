<?php
include_once '../config/db_config.php';

// === SERVICES ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un service
    if (isset($_POST['add_service'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO service (nom, description) VALUES (?, ?)");
            $stmt->execute([
                $_POST['nom'],
                $_POST['description']
            ]);
            echo "Service ajouté avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du service : " . $e->getMessage();
        }
    }

    // Suppression d'un service
    if (isset($_POST['delete_service'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM service WHERE service_id = ?");
            $stmt->execute([$_POST['service_id']]);
            echo "Service supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du service : " . $e->getMessage();
        }
    }
}
