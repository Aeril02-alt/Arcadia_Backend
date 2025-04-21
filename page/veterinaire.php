<?php
require_once '../config/db_config.php';
// require_once '../config/For_watch/Auth_User/auth_veterinaire.php';
require_once '../config/For_watch/Veterinaire_co.php';
require_once '../config/For_User/Rapport_Control.php';

$animaux_data = []; // Sécurité minimale

try {
    $stmt = $pdo->query("SELECT a.animal_id, a.prenom, r.label AS race_label
    FROM animal a
    JOIN race r ON a.race_id = r.race_id");

    $animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($animaux as $animal) {
        $stmt_rapports = $pdo->prepare("SELECT * FROM rapport_veterinaire WHERE animal_id = ?");
        $stmt_rapports->execute([$animal['animal_id']]);
        $animal['rapports'] = $stmt_rapports->fetchAll(PDO::FETCH_ASSOC);
        $animaux_data[] = $animal;
    }
} catch (PDOException $e) {
    echo "Erreur chargement des animaux : " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapports et Consommation par Animal</title>
    <link rel="stylesheet" href="../source/css/style.css">
</head>
<body>
    <main>
        <h1>Rapports et Consommation par Animal</h1>

        <?php foreach ($animaux_data as $animal): ?>
            <section class="animal-section">
                <h2><?= htmlspecialchars($animal['prenom']) ?> (<?= htmlspecialchars($animal['race_label']) ?>)</h2>

                <?php if (!empty($animal['rapports'])): ?>
                    <details>
                        <summary>Rapports vétérinaires (<?= count($animal['rapports']) ?>)</summary>
                        <?php foreach ($animal['rapports'] as $rapport): ?>
                            <div class="rapport-block">
                                <p>
                                    <strong>Date :</strong> <?= htmlspecialchars($rapport['date']) ?><br>
                                    <strong>Détail :</strong><br>
                                    <?= nl2br(htmlspecialchars($rapport['detail'])) ?>
                                </p>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_rapport" value="1">
                                    <input type="hidden" name="rapport_veterinaire_id" value="<?= $rapport['rapport_veterinaire_id'] ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </details>
                <?php else: ?>
                    <p><em>Aucun rapport vétérinaire pour cet animal.</em></p>
                <?php endif; ?>

                <form method="POST" action="">
                    <h4>Ajouter un rapport :</h4>
                    <input type="hidden" name="add_rapport" value="1">
                    <input type="hidden" name="animal_id" value="<?= $animal['animal_id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">

                    <label>Date :</label>
                    <input type="date" name="date" required><br>

                    <label>Détail :</label><br>
                    <textarea name="detail" required></textarea><br>

                    <button type="submit">Ajouter</button>
                </form>
            </section>
        <?php endforeach; ?>
    </main>
</body>
</html>
