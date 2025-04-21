<?php
function getHabitats(PDO $pdo): array {
    $sql = "SELECT * FROM habitat";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getEtats(PDO $pdo): array {
    $sql = "SELECT * FROM etat";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getRapportsByAnimal(PDO $pdo, int $animal_id): array {
    $stmt = $pdo->prepare("SELECT * FROM rapport_veterinaire WHERE animal_id = ?");
    $stmt->execute([$animal_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
