<?php
header('Content-Type: application/json');

// Inclusion MongoDB
require_once __DIR__ . '/../init.php';
require_once CONFIG_PATH . '/Mongo.php';
require_once CONFIG_PATH . '/db_config.php';

$mongo = new Mongo();
$collection = $mongo->getCollection('Arcadia', 'consultations');
try {
    // Vérifie la méthode et les données
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['animal_id'], $_POST['prenom'])) {
        throw new Exception("Requête invalide");
    }

    $animal_id = (int) $_POST['animal_id'];
    $prenom = trim($_POST['prenom']);

    if (!$animal_id || $prenom === '') {
        throw new Exception("animal_id ou prénom manquant");
    }

    // Vérifie si l'animal existe dans la base SQL
    $stmt = $pdo->prepare("SELECT * FROM animal WHERE animal_id = :id AND prenom = :prenom");
    $stmt->execute(['id' => $animal_id, 'prenom' => $prenom]);
    $animal = $stmt->fetch();

    if (!$animal) {
        throw new Exception("Animal non trouvé en base SQL");
    }

    // Incrément dans MongoDB
    $result = $collection->updateOne(
        ['animal_id' => $animal_id, 'prenom' => $prenom],
        ['$inc' => ['consultations' => 1]],
        ['upsert' => true] // 👈 crée si inexistant
    );
    

    if ($result->getModifiedCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Consultation enregistrée']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune mise à jour MongoDB']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
