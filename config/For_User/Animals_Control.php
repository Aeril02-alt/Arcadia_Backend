<?php
// Inclusion des chemins globaux
require_once __DIR__ . '/../init.php';

// Inclusion mutualisée des fichiers de configuration et de fonction
require_once CONFIG_PATH . '/db_config.php';
require_once CONFIG_PATH . '/Functions/ImgDL.php';

// === GESTION DES ANIMAUX & RACES ===
// Vérifie que la méthode HTTP est bien POST
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- AJOUT D'UN ANIMAL ---
    if (isset($_POST['add_animal'])) {
        $imgPath = handleImageUpload('animal_image', $pdo, $_POST['habitat_id']); // Retourne le chemin de l'image
    
        try {
            // Insertion de l'animal dans la base de données sans nettoyage excessif
            $stmt = $pdo->prepare("INSERT INTO animal (prenom, etat, race_id, habitat_id, img_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['prenom'],
                $_POST['etat'],
                $_POST['race_id'],
                $_POST['habitat_id'],
                $imgPath
            ]);
            echo "Animal ajouté avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'animal : " . $e->getMessage();
        }
    }
    

    // --- SUPPRESSION D'UN ANIMAL ---
    if (isset($_POST['delete_animal'])) {
        $animal_id = filter_input(INPUT_POST, 'animal_id', FILTER_VALIDATE_INT);

        if ($animal_id) {
            try {
                // Suppression de l'animal identifié par son ID
                $stmt = $pdo->prepare("DELETE FROM animal WHERE id = ?");
                $stmt->execute([$animal_id]);
                echo "Animal supprimé avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                echo "Erreur lors de la suppression de l'animal.";
            }
        } else {
            echo "ID de l'animal invalide.";
        }
    }

    // --- AJOUT D'UNE RACE ---
    if (isset($_POST['add_race'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO race (label) VALUES (?)");
            $stmt->execute([$_POST['label']]); // Utilisation directe de $_POST['label']
            echo "Race ajoutée avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la race : " . $e->getMessage();
        }
    }
    
    // --- SUPPRESSION D'UNE RACE ---
    if (isset($_POST['delete_race'])) {
        $race_id = filter_input(INPUT_POST, 'race_id', FILTER_VALIDATE_INT);

        if ($race_id) {
            try {
                // Suppression d'une race par son ID
                $stmt = $pdo->prepare("DELETE FROM race WHERE id = ?");
                $stmt->execute([$race_id]);
                echo "Race supprimée avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                echo "Erreur lors de la suppression de la race.";
            }
        } else {
            echo "ID de la race invalide.";
        }
    }
}
