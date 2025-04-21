<?php
// Page employer.php
require_once '../config/db_config.php';
//require_once '../config/For_Watch/Auth_User/auth_emp.php';
require_once '../config/For_User/Animals_Control.php';
require_once '../config/For_User/Service_Control.php';
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

<h1>Ajouter la Consommation de Nourriture pour un Animal</h1>
<form method="post">
    <fieldset>
        <legend>Ajouter la Consommation</legend>
        <label for="animal_id">Animal :</label>
        <select name="animal_id" id="animal_id" required>
            <?php foreach ($animalList as $animal): ?>
                <option value="<?= $animal['animal_id'] ?>"><?= htmlspecialchars($animal['prenom']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="date">Date :</label>
        <input type="date" name="date" id="date" required><br>

        <label for="nourriture">Nourriture :</label>
        <input type="text" name="nourriture" id="nourriture" required><br>

        <label for="quantite">Quantité (g) :</label>
        <input type="number" name="quantite" id="quantite" required step="0.01" min="0"><br>

        <button type="submit" name="ajouter_nourriture">Ajouter</button>
    </fieldset>
</form>

<h1>Modifier les Services du Zoo</h1>
<?php foreach ($services as $service): ?>
    <form method="post" class="service-form">
        <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
        <label for="nom_service_<?= $service['service_id'] ?>">Nom :</label>
        <input type="text" id="nom_service_<?= $service['service_id'] ?>" name="nom_service" value="<?= htmlspecialchars($service['nom']) ?>" required><br>
        <label for="description_service_<?= $service['service_id'] ?>">Description :</label>
        <textarea id="description_service_<?= $service['service_id'] ?>" name="description_service" required><?= htmlspecialchars($service['description']) ?></textarea><br>
        <button type="submit" name="modifier_service">Modifier</button>
    </form>
<?php endforeach; ?>

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
