<?php
// Food_Control.php
include_once '../config/db_config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification que tous les champs nécessaires sont envoyés
    $animal_id = filter_input(INPUT_POST, 'animal_id', FILTER_VALIDATE_INT);
    $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_FLOAT);
    $type_nourriture = htmlspecialchars(trim($_POST['type_nourriture'] ?? ''));
    $date_consumption = isset($_POST['date_consumption']) ? $_POST['date_consumption'] : null;
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));

    // Vérification des champs nécessaires
    if ($animal_id && $quantite && $type_nourriture && $date_consumption) {
        try {
            // Requête préparée pour insérer la consommation de nourriture
            $stmt = $pdo->prepare("INSERT INTO consommation_nourriture (animal_id, quantite, type_nourriture, date_consumption, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$animal_id, $quantite, $type_nourriture, $date_consumption, $description]);

            echo "Consommation de nourriture ajoutée avec succès.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Erreur lors de l'ajout de la consommation de nourriture.";
        }
    } else {
        echo "Tous les champs sont requis pour ajouter une consommation.";
    }
}
    // --- MODIFICATION D'UNE CONSOMMATION DE NOURRITURE ---
    if (isset($_POST['update_food'])) {
        $consommation_id = filter_input(INPUT_POST, 'consommation_id', FILTER_VALIDATE_INT);

        // Vérification que tous les champs sont remplis
        if ($consommation_id && $animal_id && $quantite && $type_nourriture && $date_consumption) {
            try {
                // Requête préparée pour mettre à jour la consommation de nourriture
                $stmt = $pdo->prepare("UPDATE consommation_nourriture SET animal_id = ?, quantite = ?, type_nourriture = ?, date_consumption = ?, description = ? WHERE consommation_id = ?");
                $stmt->execute([$animal_id, $quantite, $type_nourriture, $date_consumption, $description, $consommation_id]);

                echo "Consommation de nourriture mise à jour avec succès.";
            } catch (PDOException $e) {
                // Journalisation de l'erreur sans l'afficher au public
                error_log($e->getMessage());
                echo "Erreur lors de la mise à jour de la consommation de nourriture.";
            }
        } else {
            echo "Tous les champs sont requis pour mettre à jour la consommation de nourriture.";
        }
    }

    // --- SUPPRESSION D'UNE CONSOMMATION DE NOURRITURE ---
    if (isset($_POST['delete_food'])) {
        // Validation de l'ID de la consommation à supprimer
        $consommation_id = filter_input(INPUT_POST, 'consommation_id', FILTER_VALIDATE_INT);

        if ($consommation_id) {
            try {
                // Requête préparée pour supprimer une consommation de nourriture
                $stmt = $pdo->prepare("DELETE FROM consommation_nourriture WHERE consommation_id = ?");
                $stmt->execute([$consommation_id]);

                echo "Consommation de nourriture supprimée avec succès.";
            } catch (PDOException $e) {
                // Journalisation de l'erreur sans l'afficher au public
                error_log($e->getMessage());
                echo "Erreur lors de la suppression de la consommation de nourriture.";
            }
        } else {
            echo "ID de la consommation de nourriture invalide.";
        }
    }
