<?php
require_once __DIR__ . '/../init.php';
require_once CONFIG_PATH . '/db_config.php';


// === UTILISATEURS ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un utilisateur
    if (isset($_POST['add_user'])) {
        // Nettoyage et validation des champs
        $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : 3; // Par défaut, Employé (role_id = 3)
    
        // Vérification que tous les champs sont remplis et valides
        if ($prenom && $nom && $email && $password && $role_id) {
            // Hachage du mot de passe pour la sécurité
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
            try {
                // Insertion sécurisée de l'utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$prenom, $nom, $email, $passwordHash, $role_id]);
    
                echo "Utilisateur ajouté avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());  // Journalisation de l'erreur pour débogage
                echo "Erreur lors de l'ajout de l'utilisateur.";
            }
        } else {
            echo "Tous les champs sont requis et l'email doit être valide.";
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