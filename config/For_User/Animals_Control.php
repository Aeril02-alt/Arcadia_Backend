<?php
 include_once '../config/db_config.php';
 include_once '../config/Functions/ImgDL.php';


// === GESTION DES ANIMAUX & RACES ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- ANIMAL ---
    // Ajout d'un animal
    if (isset($_POST['add_animal'])) {
        $imgPath = handleImageUpload('animal_image', $pdo, $_POST['habitat_id']);
        try {
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

    // Suppression d'un animal
    if (isset($_POST['delete_animal'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM animal WHERE animal_id = ?");
            $stmt->execute([$_POST['animal_id']]);
            echo "Animal supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'animal : " . $e->getMessage();
        }
    }

    // --- RACE ---
    // Ajout d'une race
    if (isset($_POST['add_race'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO race (label) VALUES (?)");
            $stmt->execute([$_POST['label']]);
            echo "Race ajoutée avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la race : " . $e->getMessage();
        }
    }

    // Suppression d'une race
    if (isset($_POST['delete_race'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM race WHERE race_id = ?");
            $stmt->execute([$_POST['race_id']]);
            echo "Race supprimée avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de la race : " . $e->getMessage();
        }
    }
}
