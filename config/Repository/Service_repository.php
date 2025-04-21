<?php
function getServices($pdo) {
    $sql = "SELECT * FROM service";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
