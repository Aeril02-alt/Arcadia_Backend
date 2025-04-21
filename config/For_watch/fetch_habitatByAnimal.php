<?php
// Inclure la configuration de la base de données
include "../config/db_config.php";

try {
    // Créer une connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=arcadia", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialiser la variable $habitats comme un tableau vide
    $habitats = [];

    // Récupérer les informations des habitats et des animaux associés
    $stmt = $conn->prepare("
            SELECT
                h.habitat_id,
                h.nom AS habitat_nom,
                h.description,
                h.commentaire_habitat,
                h.img_path AS habitat_img_path,
                a.animal_id,
                a.prenom AS animal_prenom,
                a.etat AS animal_etat,
                a.img_path AS animal_img_path,
                r.label AS race_label
            FROM habitat h
            LEFT JOIN animal a ON h.habitat_id = a.habitat_id
            LEFT JOIN race r ON a.race_id = r.race_id
    ");

    $stmt->execute();

    // Parcourir les résultats pour structurer les données pour l'affichage
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $habitat_id = $row['habitat_id'];
        
        // Grouper les animaux par habitat
        if (!isset($habitats[$habitat_id])) {
            $habitats[$habitat_id] = [
                'nom' => $row['habitat_nom'],
                'description' => $row['description'],
                'commentaire_habitat' => $row['commentaire_habitat'],
                'img_path' => $row['habitat_img_path'],
                'animaux' => []
            ];
        }

        
        // Ajouter les informations de chaque animal dans l'habitat correspondant
        if ($row['animal_id']) {
            $habitats[$habitat_id]['animaux'][] = [
                'animal_id' => $row['animal_id'],
                'prenom' => $row['animal_prenom'],
                'etat' => $row['animal_etat'],
                'race' => $row['race_label'],
                'img_path' => $row['animal_img_path']
            ];
        }
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
