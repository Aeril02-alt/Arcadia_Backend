<?php

require_once __DIR__ . '/config/init.php';

$timeout = 30*60; // 30 minutes
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 2) {
    // Redirection vers la page de connexion ou index
    echo "🔒 Accès refusé : vous n'êtes pas connecté ou vous n'avez pas les droits.";
    exit;
}

require_once CONFIG_PATH . '/db_config.php';
// require_once CONFIG_PATH . '/For_watch/auth_veterinaire.php';
require_once CONFIG_PATH . '/For_watch/Veterinaire_co.php';
require_once CONFIG_PATH . '/For_User/Rapport_Control.php';
require_once CONFIG_PATH . '/For_User/Animals_Control.php';
require_once CONFIG_PATH . '/For_User/Food_control.php';


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
        
        // 🔥 Récupérer les consommations de nourriture de cet animal
        $stmt_consommations = $pdo->prepare("SELECT * FROM consommation_nourriture WHERE animal_id = ?");
        $stmt_consommations->execute([$animal['animal_id']]);
        $animal['consommations'] = $stmt_consommations->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter l'animal complet dans $animaux_data
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
    <!-- <link rel="stylesheet" href="../source/css/style.css"> -->
</head>
<body>
    <main>
        <h1>Rapports et Consommation par Animal</h1>

        <?php foreach ($animaux_data as $animal): ?>
    <section class="animal-section">
        <h2><?= htmlspecialchars($animal['prenom']) ?> (<?= htmlspecialchars($animal['race_label']) ?>)</h2>

        <!-- Rapports vétérinaires -->
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

        <!-- Consommations de nourriture -->
        <?php if (!empty($animal['consommations'])): ?>
            <details>
                <summary>Consommations de nourriture (<?= count($animal['consommations']) ?>)</summary>
                <?php foreach ($animal['consommations'] as $consommation): ?>
                    <div class="conso-block">
                        <p>
                            <strong>Date :</strong> <?= htmlspecialchars($consommation['date_consumption']) ?><br>
                            <strong>Quantité :</strong> <?= htmlspecialchars($consommation['quantite']) ?><br>
                            <strong>Type :</strong> <?= htmlspecialchars($consommation['type_nourriture']) ?><br>
                            <strong>Description :</strong><br>
                            <?= nl2br(htmlspecialchars($consommation['description'])) ?>
                        </p>
                        <form method="POST" action="">
                            <input type="hidden" name="delete_food" value="1">
                            <input type="hidden" name="consommation_id" value="<?= $consommation['consommation_id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </details>
        <?php else: ?>
            <p><em>Aucune consommation de nourriture pour cet animal.</em></p>
        <?php endif; ?>
    </section>
<?php endforeach; ?>

        <!-- AJOUT RAPPORT VÉTÉRINAIRE -->
        <section class="ajout_rapport">
            <h2>Ajouter un Rapport Vétérinaire</h2>

            <form action="veterinaire.php" method="POST">
                <label for="animal_id">Animal concerné :</label>
                <select id="animal_id" name="animal_id" required>
                    <option value="">-- Sélectionner un animal --</option>
                    <?php
                    try {
                        $animaux = $pdo->query("SELECT animal_id, prenom FROM animal ORDER BY prenom")->fetchAll();
                        foreach ($animaux as $animal) {
                            echo "<option value='" . htmlspecialchars($animal['animal_id']) . "'>" . htmlspecialchars($animal['prenom']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option disabled>Erreur chargement animaux</option>";
                        error_log("Erreur chargement animaux : " . $e->getMessage());
                    }
                    ?>
                </select>
                <br>

                <label for="date">Date du rapport :</label>
                <input type="date" id="date" name="date" required>
                <br>

                <label for="detail">Détails :</label>
                <textarea id="detail" name="detail" rows="4" required></textarea>
                <br>

                <label for="user_id">Vétérinaire ayant rédigé :</label>
                <select id="user_id" name="user_id" required>
                    <option value="">-- Sélectionner un vétérinaire --</option>
                    <?php
                    try {
                        $veterinaires = $pdo->query("SELECT user_id, nom, prenom FROM utilisateur WHERE role_id = 2 ORDER BY nom")->fetchAll();
                        foreach ($veterinaires as $user) {
                            $fullName = htmlspecialchars($user['prenom'] . ' ' . $user['nom']);
                            echo "<option value='" . (int) $user['user_id'] . "'>$fullName</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option disabled>Erreur chargement vétérinaires</option>";
                        error_log("Erreur chargement utilisateurs vétérinaires : " . $e->getMessage());
                    }
                    ?>
                </select>
                <br>

                <button type="submit" name="add_rapport">Ajouter le rapport</button>
            </form>
        </section>
    </main>
</body>
</html>
