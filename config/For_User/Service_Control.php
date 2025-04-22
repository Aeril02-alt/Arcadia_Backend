<?php
// Inclusion du fichier de configuration de la base de données
include_once '../config/db_config.php';

// === SERVICES ===
// Vérifie que la requête est bien de type POST
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- AJOUT D'UN SERVICE ---
    if (isset($_POST['add_service'])) {

        // Nettoyage des données pour éviter les attaques XSS
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $description = htmlspecialchars(trim($_POST['description'] ?? ''));

        // Vérifie que les champs sont bien remplis
        if ($nom && $description) {
            try {
                // Requête préparée pour insérer les données
                $stmt = $pdo->prepare("INSERT INTO service (nom, description) VALUES (?, ?)");
                $stmt->execute([$nom, $description]);
                echo "Service ajouté avec succès.";
            } catch (PDOException $e) {
                // Log de l'erreur sans l'affichage public
                error_log($e->getMessage());
                echo "Erreur lors de l'ajout du service.";
            }
        } else {
            echo "Veuillez remplir tous les champs requis pour ajouter un service.";
        }
    }

    // --- SUPPRESSION D'UN SERVICE ---
    if (isset($_POST['delete_service'])) {

        // Validation de l'identifiant du service à supprimer
        $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);

        if ($service_id) {
            try {
                // Suppression du service depuis la base de données
                $stmt = $pdo->prepare("DELETE FROM service WHERE service_id = ?");
                $stmt->execute([$service_id]);
                echo "Service supprimé avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                echo "Erreur lors de la suppression du service.";
            }
        } else {
            echo "ID du service invalide.";
        }
    }

    // --- MODIFICATION D'UN SERVICE ---
    if (isset($_POST['modifier_service'])) {
        // Récupération de l'ID du service et des nouvelles données
        $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
        $nom_service = htmlspecialchars(trim($_POST['nom_service'] ?? ''));
        $description_service = htmlspecialchars(trim($_POST['description_service'] ?? ''));

        // Vérifie que les données sont valides
        if ($service_id && $nom_service && $description_service) {
            try {
                // Requête préparée pour mettre à jour le service
                $stmt = $pdo->prepare("UPDATE service SET nom = ?, description = ? WHERE service_id = ?");
                $stmt->execute([$nom_service, $description_service, $service_id]);
                echo "Service modifié avec succès.";
            } catch (PDOException $e) {
                // Log de l'erreur sans l'affichage public
                error_log($e->getMessage());
                echo "Erreur lors de la modification du service.";
            }
        } else {
            echo "Tous les champs sont requis pour modifier un service.";
        }
    }
}