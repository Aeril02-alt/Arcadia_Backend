<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../init.php';

require_once CONFIG_PATH . '/db_config.php';
require_once CONFIG_PATH . '/Functions/ImgDL.php';

// === HABITATS ===
// S'assure que la requête est une requête POST
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- AJOUT D'UN HABITAT ---
    if (isset($_POST['add_habitat'])) {

        // Nettoyage et validation des champs requis
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $description = htmlspecialchars(trim($_POST['description'] ?? ''));
        $commentaire = htmlspecialchars(trim($_POST['commentaire_habitat'] ?? ''));

        // On suppose ici qu'on souhaite lier l'image à un habitat ID en base, si existant
        $habitat_id_image = filter_input(INPUT_POST, 'habitat_id', FILTER_VALIDATE_INT);

        // Contrôle de la présence des données requises
        if ($nom && $description && $commentaire) {

            // Gestion de l'upload de l'image (avec sécurité dans la fonction externe)
            $imgPath = handleImageUpload('animal_image', $pdo, $habitat_id_image);

            try {
                // Insertion d'un nouvel habitat dans la base
                $stmt = $pdo->prepare("INSERT INTO habitat (nom, description, commentaire_habitat, img_path) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $description, $commentaire, $imgPath]);
                echo "Habitat ajouté avec succès.";
            } catch (PDOException $e) {
                // En cas d'erreur SQL, on log sans afficher publiquement
                error_log($e->getMessage());
                echo "Erreur lors de l'ajout de l'habitat.";
            }
        } else {
            echo "Tous les champs sont requis pour ajouter un habitat.";
        }
    }

    // --- SUPPRESSION D'UN HABITAT ---
    if (isset($_POST['delete_habitat'])) {
        // Validation de l'identifiant de l'habitat à supprimer
        $habitat_id = filter_input(INPUT_POST, 'habitat_id', FILTER_VALIDATE_INT);

        if ($habitat_id) {
            try {
                // Suppression de l'habitat de la base
                $stmt = $pdo->prepare("DELETE FROM habitat WHERE habitat_id = ?");
                $stmt->execute([$habitat_id]);
                echo "Habitat supprimé avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                echo "Erreur lors de la suppression de l'habitat.";
            }
        } else {
            echo "ID de l'habitat invalide.";
        }
    }
}
