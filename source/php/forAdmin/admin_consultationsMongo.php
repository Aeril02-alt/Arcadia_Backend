<?php
require_once __DIR__ . '/../../../config/Mongo.php';

$mongo = new Mongo();
$collection = $mongo->getCollection('Arcadia', 'consultations');

// Récupération des consultations triées par le plus consulté
$cursor = $collection->find([], ['sort' => ['consultations' => -1]]);
?>

<h2>📊 Consultations par animal</h2>
<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID Animal</th>
            <th>Prénom</th>
            <th>Consultations</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cursor as $animal): ?>
            <tr>
                <td><?= htmlspecialchars($animal['animal_id']) ?></td>
                <td><?= htmlspecialchars($animal['prenom']) ?></td>
                <td><?= $animal['consultations'] ?? 0 ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
