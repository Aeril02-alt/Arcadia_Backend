<?php
// fetch_services.php

function getServices($pdo) {
    $sql = "SELECT * FROM service ORDER BY service_id";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
