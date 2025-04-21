<?php

function handleImageUpload($inputName, $pdo, $habitat_id) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
        $stmt = $pdo->prepare("SELECT nom FROM habitat WHERE habitat_id = ?");
        $stmt->execute([$habitat_id]);
        $habitat = $stmt->fetch();

        if (!$habitat) {
            return null;
        }

        $habitat_name = strtolower(trim($habitat['nom']));
        $uploadDir = "../doc/photo/" . $habitat_name . "/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
            return "doc/photo/" . $habitat_name . "/" . $filename;
        }
    }
    return null;
}
