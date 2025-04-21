<?php
 include_once '../config/db_config.php';

// === UTILISATEURS ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un utilisateur
    if (isset($_POST['add_user'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['prenom'],
                $_POST['nom'],
                $_POST['email'],
                password_hash($_POST['password'], PASSWORD_DEFAULT),
                $_POST['role_id']
            ]);
            echo "Utilisateur ajouté avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }

    // Suppression d'un utilisateur
    if (isset($_POST['delete_user'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE user_id = ?");
            $stmt->execute([$_POST['user_id']]);
            echo "Utilisateur supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
        }
    }
}
