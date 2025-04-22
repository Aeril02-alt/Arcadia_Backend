<?php
// Page employer.php
require_once '../config/db_config.php';
//require_once '../config/For_Watch/Auth_User/auth_emp.php';
require_once '../config/For_User/Animals_Control.php';
require_once '../config/For_User/Service_Control.php';
require_once '../config/For_User/Food_control.php';

require_once '../config/Functions/ValideFoodForm.php';

require_once '../source/php/views/messages.php';

require_once '../controllers/employer_Controller.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services et de la Nourriture des Animaux</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <link rel="stylesheet" href="../source/css/header_footer.css">
    <link rel="stylesheet" href="../source/css/forPage.css">
    <script src="../source/java/Header_Footer.js" defer></script>
</head>
<body>
<header>
    <nav aria-label="Main Navigation"><ul id="header"></ul></nav>
</header>

<?php displayMessages($errors, 'error'); ?>
<?php displayMessages($success, 'success'); ?>

    <!-- Formulaire pour ajouter une consommation de nourriture -->
    <h1>Ajouter une Consommation de Nourriture</h1>
    <form action="employer.php" method="post" >
        <label for="animal_id">Animal :</label>
        <select id="animal_id" name="animal_id" required>
            <?php
            // Récupérer les animaux disponibles dans la base
            $stmt = $pdo->query("SELECT animal_id, prenom FROM animal");
            while ($animal = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($animal['animal_id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($animal['prenom'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            ?>
     </select>
        <br>

        <label for="quantite">Quantité (kg) :</label>
        <input type="number" id="quantite" name="quantite" step="0.01" required>
        <br>

        <label for="type_nourriture">Type de nourriture :</label>
        <input type="text" id="type_nourriture" name="type_nourriture" required>
        <br>

        <label for="date_consumption">Date de consommation :</label>
        <input type="date" id="date_consumption" name="date_consumption" required>
        <br>

        <label for="description">Description :</label>
        <textarea id="description" name="description"></textarea>
        <br>

        <button type="submit" name="add_food">Ajouter la consommation</button>
    </form>

<h1>Modifier les Services du Zoo</h1>
<?php foreach ($services as $service): ?>
    <form method="post" class="service-form">
        <!-- Champ caché pour l'ID du service -->
        <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['service_id'], ENT_QUOTES, 'UTF-8') ?>">

        <label for="nom_service_<?= htmlspecialchars($service['service_id'], ENT_QUOTES, 'UTF-8') ?>">Nom :</label>
        <input type="text" id="nom_service_<?= htmlspecialchars($service['service_id'], ENT_QUOTES, 'UTF-8') ?>" name="nom_service" value="<?= htmlspecialchars($service['nom'], ENT_QUOTES, 'UTF-8') ?>" required><br>

        <label for="description_service_<?= htmlspecialchars($service['service_id'], ENT_QUOTES, 'UTF-8') ?>">Description :</label>
        <textarea id="description_service_<?= htmlspecialchars($service['service_id'], ENT_QUOTES, 'UTF-8') ?>" name="description_service" required><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></textarea><br>

        <!-- Bouton pour modifier le service -->
        <button type="submit" name="modifier_service">Modifier</button>
    </form>
<?php endforeach; ?>


<!-- Formulaire pour visualisation rapports vétérinaires -->
<h1>Rapports et Consommation par Animal</h1>
<?php foreach ($animaux_data as $animal): ?>
    <section class="animal-section">
        <h2><?= htmlspecialchars($animal['prenom']) ?> (<?= htmlspecialchars($animal['race_label']) ?>)</h2>
        <p><strong>État général :</strong> <?= htmlspecialchars($animal['etat']) ?></p>

        <?php if (!empty($animal['rapports'])): ?>
            <details>
                <summary>Rapports vétérinaires (<?= count($animal['rapports']) ?>)</summary>
                <?php foreach ($animal['rapports'] as $rapport): ?>
                    <p>
                        <strong>Date :</strong> <?= htmlspecialchars($rapport['date']) ?><br>
                        <strong>Détail :</strong><br>
                        <?= nl2br(htmlspecialchars($rapport['detail'])) ?>
                    </p>
                <?php endforeach; ?>
            </details>
        <?php else: ?>
            <br><p><em>         Aucun rapport vétérinaire pour cet animal.</em></p>
        <?php endif; ?>
    </section>
<?php endforeach; ?>

<footer>
    <nav aria-label="Footer Navigation"><ul id="footer"></ul></nav>
</footer>
</body>
</html>
