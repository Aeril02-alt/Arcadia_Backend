<?php
// Démarre la session utilisateur
session_start();

// Inclusion de la base de données
include_once '../config/db_config.php';

// === AUTHENTIFICATION ADMIN ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et nettoyage des identifiants fournis
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        try {
            // Récupération de l'utilisateur par email
            $stmt = $pdo->prepare("SELECT user_id, password, nom, prenom FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if ($user && password_verify($password, $user['password'])) {
                // Stockage des informations en session pour l'utilisateur connecté
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                echo "Connexion réussie.";
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            // Ne jamais exposer les erreurs directement à l'utilisateur
            error_log($e->getMessage());
            echo "Erreur lors de la tentative de connexion.";
        }
    } else {
        echo "Champs email et mot de passe requis.";
    }
}
