<?php
 include_once '../config/db_config.php';
 
// === RAPPORTS VÉTÉRINAIRES ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un rapport vétérinaire
    if (isset($_POST['add_rapport'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rapport_veterinaire (date, detail, animal_id, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['date'],
                $_POST['detail'],
                $_POST['animal_id'],
                $_POST['user_id']
            ]);
            echo "Rapport vétérinaire ajouté avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du rapport : " . $e->getMessage();
        }
    }

    // Suppression d'un rapport vétérinaire
    if (isset($_POST['delete_rapport'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM rapport_veterinaire WHERE rapport_veterinaire_id = ?");
            $stmt->execute([$_POST['rapport_veterinaire_id']]);
            echo "Rapport vétérinaire supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du rapport : " . $e->getMessage();
        }
    }
}
