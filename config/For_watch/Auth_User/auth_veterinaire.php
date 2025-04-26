<?php
// Lancement de la session PHP
session_start();

// Connexion à la base de données
require_once __DIR__ . '/../../init.php';
require_once CONFIG_PATH . '/db_config.php';


// === AUTHENTIFICATION VETERINAIRE ===
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nettoyage et validation des données
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        try {
            // Requête SQL préparée pour retrouver le vétérinaire
            $stmt = $pdo->prepare("SELECT veterinaire_id, password, nom, prenom FROM veterinaire WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe via hash
            if ($user && password_verify($password, $user['password'])) {
                // Stocke les informations de session du vétérinaire
                $_SESSION['veterinaire_id'] = $user['veterinaire_id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                echo "Connexion vétérinaire réussie.";
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            // Journalisation sans retour d'erreur à l'utilisateur
            error_log($e->getMessage());
            echo "Erreur lors de la tentative de connexion.";
        }
    } else {
        echo "Champs email et mot de passe requis.";
    }
}
