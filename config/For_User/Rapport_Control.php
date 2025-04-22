<?php
// Inclusion de la configuration de la base de données
include_once '../config/db_config.php';

// === RAPPORTS VÉTÉRINAIRES ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rapport'])) {
    $animal_id = isset($_POST['animal_id']) ? (int) $_POST['animal_id'] : 0;
    $date = $_POST['date'] ?? '';
    $detail = trim($_POST['detail'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if ($animal_id > 0 && !empty($date) && !empty($detail) && !empty($user_id)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rapport_veterinaire (date, detail, animal_id, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$date, htmlspecialchars($detail), $animal_id, $user_id]);
            $message = "✅ Rapport vétérinaire ajouté avec succès.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = "❌ Erreur lors de l'ajout du rapport.";
        }
    } else {
        $message = "⚠️ Tous les champs sont requis.";
    }

    // --- SUPPRESSION D'UN RAPPORT ---
    if (isset($_POST['delete_rapport'])) {
        $rapport_id = filter_input(INPUT_POST, 'rapport_veterinaire_id', FILTER_VALIDATE_INT);

        if ($rapport_id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM rapport_veterinaire WHERE rapport_veterinaire_id = ?");
                $stmt->execute([$rapport_id]);
                echo "Rapport vétérinaire supprimé avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                echo "Erreur lors de la suppression du rapport.";
            }
        } else {
            echo "ID de rapport invalide.";
        }
    }
}
