<?php
 include_once '../config/db_config.php';
 include_once '../config/Functions/ImgDL.php';


// === HABITATS ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un habitat
    if (isset($_POST['add_habitat'])) {
        $imgPath = handleImageUpload('animal_image', $pdo, $_POST['habitat_id']);
        try {
            $stmt = $pdo->prepare("INSERT INTO habitat (nom, description, commentaire_habitat, img_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nom'],
                $_POST['description'],
                $_POST['commentaire_habitat'],
                $imgPath
            ]);
            echo "Habitat ajouté avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'habitat : " . $e->getMessage();
        }
    }

    // Suppression d'un habitat
    if (isset($_POST['delete_habitat'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM habitat WHERE habitat_id = ?");
            $stmt->execute([$_POST['habitat_id']]);
            echo "Habitat supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'habitat : " . $e->getMessage();
        }
    }
}
