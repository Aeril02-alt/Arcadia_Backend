<?php
// Démarre la session utilisateur
session_start();

// Inclusion de la base de données
include_once '../config/db_config.php';

// === AUTHENTIFICATION EMPLOYÉ ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nettoyage et validation des entrées utilisateur
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        try {
            // Requête préparée pour vérifier les identifiants
            $stmt = $pdo->prepare("SELECT employe_id, password, nom, prenom FROM employe WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comparaison du mot de passe haché
            if ($user && password_verify($password, $user['password'])) {
                // Initialisation des variables de session
                $_SESSION['employe_id'] = $user['employe_id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                echo "Connexion employé réussie.";
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            // Log de l'erreur sans retour d'information sensible à l'utilisateur
            error_log($e->getMessage());
            echo "Erreur lors de la tentative de connexion.";
        }
    } else {
        echo "Email et mot de passe requis.";
    }
}
?>
