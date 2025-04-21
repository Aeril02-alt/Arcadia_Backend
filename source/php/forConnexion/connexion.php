<?php
// Inclut la configuration de la base de données
include('../config/db_config.php');

// Démarre la session pour gérer l'état de connexion
session_start();

// Définit l'en-tête de réponse pour JSON
header('Content-Type: application/json');

// Vérifie que la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère et assainit les données de l'utilisateur
    $username = filter_input(INPUT_POST, 'mailConnexion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'motDePasse', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // Vérifie que les champs ne sont pas vides
    if (!empty($username) && !empty($password)) {
        // Prépare la requête SQL pour vérifier l'utilisateur et récupérer son rôle
        $stmt = $conn->prepare("SELECT password, role_id FROM utilisateur WHERE username = ?");
        
        // Vérifie que la requête est bien préparée
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            // Vérifie si l'utilisateur existe
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password, $role_id);
                $stmt->fetch();

                // Vérifie le mot de passe
                if (password_verify($password, $hashed_password)) {
                    // Regénère l'ID de session pour sécuriser la connexion
                    session_regenerate_id(true);

                    // Stocke les informations de l'utilisateur dans la session
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role_id;

                    // Détermine la page de redirection en fonction du rôle
                    $redirect_url = match ($role_id) {
                        1 => "admin.php",         // page pour admin
                        2 => "veterinaire.php",   // page pour vétérinaire
                        3 => "employe.php",       // page pour employé
                        default => "index.php",     // page par défaut pour rôle inconnu
                    };

                    // Renvoie une réponse JSON avec le statut de succès et la page de redirection
                    echo json_encode([
                        "status" => "success",
                        "message" => "Connexion réussie.",
                        "redirect" => $redirect_url
                    ]);
                } else {
                    // Message d'erreur générique pour mot de passe incorrect
                    echo json_encode(["status" => "error", "message" => "Nom d'utilisateur ou mot de passe incorrect."]);
                }
            } else {
                // Message d'erreur générique si l'utilisateur n'existe pas
                echo json_encode(["status" => "error", "message" => "Nom d'utilisateur ou mot de passe incorrect."]);
            }
            $stmt->close();
        } else {
            // Erreur si la requête SQL ne peut pas être préparée
            echo json_encode(["status" => "error", "message" => "Erreur de connexion. Réessayez plus tard."]);
        }
    } else {
        // Message d'erreur si les champs sont vides
        echo json_encode(["status" => "error", "message" => "Champs requis manquants."]);
    }
} else {
    // Message d'erreur si la requête n'est pas de type POST
    echo json_encode(["status" => "error", "message" => "Requête non valide."]);
}

// Ferme la connexion à la base de données
$conn->close();
