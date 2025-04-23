<?php
// connexion.php
// ===============================================
// Active l'affichage des erreurs pendant le développement (à retirer en prod)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclut la configuration PDO
include('../../../config/db_config.php');

// Démarre la session
session_start();

// En-tête JSON
header('Content-Type: application/json');

// Vérifie que la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Requête non autorisée."]);
    exit;
}

// Récupération et validation des données
$email = filter_input(INPUT_POST, 'mailConnexion', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'motDePasse', FILTER_UNSAFE_RAW);

if (!$email || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email ou mot de passe invalide."]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT password, role_id FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("User récupéré : " . json_encode($user));

    if ($user) {
        error_log("Mot de passe reçu : " . $password);
        error_log("Hash stocké en base : " . $user['password']);
        $verif = password_verify($password, $user['password']);
        error_log("Résultat vérif : " . ($verif ? "OK" : "NON OK"));

        if ($verif) {
            error_log("Mot de passe correct");
            session_regenerate_id(true);
            $_SESSION['username'] = $email;
            $_SESSION['role'] = $user['role_id'];

            $redirect_url = match ($user['role_id']) {
                1 => "admin.php",
                2 => "veterinaire.php",
                3 => "employer.php",
                default => "index.php",
            };

            echo json_encode([
                "status" => "success",
                "message" => "Connexion réussie.",
                "redirect" => $redirect_url
            ]);
            exit;
        }
    }

    // Ici : si user non trouvé ou password faux
    echo json_encode(["status" => "error", "message" => "Nom d'utilisateur ou mot de passe incorrect."]);

} catch (PDOException $e) {
    error_log("Erreur PDO connexion : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erreur serveur. Veuillez réessayer."]);
}
