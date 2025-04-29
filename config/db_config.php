<?php
// ===============================================
// Fichier : db_config.php
// Objectif : Etablir une connexion PDO sécurisée
// ===============================================

$host = 'db';
$dbname = 'arcadia';
$username = 'arcadia-user';
$password = 'azerty123';

try {
    // Connexion PDO avec encodage utf8mb4 et gestion des erreurs par exception
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
        ]
    );
} catch (PDOException $e) {
    // Enregistre l'erreur dans les logs serveur sans l'afficher à l'écran
    error_log("Erreur PDO : " . $e->getMessage());
    // Message utilisateur générique
    echo "Impossible de se connecter à la base de données.";
    exit;
}
?>
