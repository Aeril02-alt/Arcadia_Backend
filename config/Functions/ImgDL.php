<?php
// ===============================
// Fichier : ImgDL.php
// Objectif : Gérer l'upload d'une image liée à un habitat
// ===============================

function handleImageUpload($inputName, $pdo, $habitat_id) {
    // Vérifie que le fichier a bien été envoyé sans erreur
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {

        // Vérifie que le fichier est bien une image autorisée (type et extension)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        $fileType = mime_content_type($_FILES[$inputName]['tmp_name']);
        $extension = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes) || !in_array($extension, $allowedExtensions)) {
            error_log("Type de fichier non autorisé : $fileType");
            return null;
        }

        // Optionnel : vérifier la taille max (ex : 5 Mo)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES[$inputName]['size'] > $maxSize) {
            error_log("Fichier trop volumineux");
            return null;
        }

        // Récupérer le nom de l'habitat
        $stmt = $pdo->prepare("SELECT nom FROM habitat WHERE habitat_id = ?");
        $stmt->execute([$habitat_id]);
        $habitat = $stmt->fetch();

        if (!$habitat) {
            error_log("Habitat introuvable pour ID: $habitat_id");
            return null;
        }

        $habitat_name = strtolower(trim($habitat['nom']));
        $uploadDir = "../doc/photo/" . $habitat_name . "/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Impossible de créer le dossier : $uploadDir");
                return null;
            }
        }

        // Générer un nom de fichier unique et enregistrer l'image
        $filename = uniqid() . "." . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
            return "doc/photo/" . $habitat_name . "/" . $filename;
        } else {
            error_log("Échec de l'enregistrement du fichier vers $targetPath");
        }
    }

    // Retour par défaut si quelque chose a échoué
    return null;
}
