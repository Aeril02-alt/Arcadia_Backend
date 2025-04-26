<?php

require_once __DIR__ . '/../config/init.php';

$timeout = 30*60; // 30 minutes
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 3) {
    // Redirection vers la page de connexion ou index
    echo "🔒 Accès refusé : vous n'êtes pas connecté ou vous n'avez pas les droits.";
    exit;
}

// Dépendances backend
require_once CONFIG_PATH . '/Mongo.php';
require_once CONFIG_PATH . '/Functions/ValideFoodForm.php';

// Authentification
require_once CONFIG_PATH . '/For_Watch/Auth_User/auth_emp.php';

// Contrôleurs spécifiques
require_once CONFIG_PATH . '/For_User/Animals_Control.php';
require_once CONFIG_PATH . '/For_User/Service_Control.php';
require_once CONFIG_PATH . '/For_User/Food_control.php';

require_once CONFIG_PATH . '/views/messages.php';
require_once CONTROLLER_PATH . '/employer_Controller.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer</title>
    <link rel="stylesheet" href="/source/css/style.css">
    <link rel="stylesheet" href="/source/css/header_footer.css">
    <link rel="stylesheet" href="/source/css/forPage.css">

    <script src="/source/java/Header_Footer.js" defer></script>

</head>

<body>
<header>
    <nav aria-label="Main Navigation"><ul id="header"></ul></nav>
</header>

<?php displayMessages($errors, 'error'); ?>
<?php displayMessages($success, 'success'); ?>

        <section id="formPresentationUpdate">
            <h2>Modifier la présentation du Zoo</h2>
            <form method="POST" action="/page/api/update_presentation.php">
                <textarea name="texte" rows="8" cols="80" placeholder="Entrez la nouvelle présentation..."><?php
                    // Pré-remplissage
                    $mongo = new Mongo();
                    $collection = $mongo->getCollection('Arcadia', 'presentationsZoo');
                    $presentation = $collection->findOne(['presentationId' => '1']);
                    echo isset($presentation['texte']) ? htmlspecialchars($presentation['texte']) : '';
                ?></textarea>
                <br><br>
                <button type="submit">Mettre à jour</button>
            </form>
            <?php
                    if (isset($_GET['update']) && $_GET['update'] === 'success') {
                        echo '<p style="color: green;">Présentation mise à jour avec succès !</p>';
                    } elseif (isset($_GET['update']) && $_GET['update'] === 'fail') {
                        echo '<p style="color: red;">Erreur lors de la mise à jour.</p>';
                    }
                    ?>

        </section>


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

<!-- Formulaire pour valider les commentaires -->
<?php
$mongo = new Mongo();
$commentaires = $mongo->getCollection('Arcadia', 'commentairesZoo')->find()->toArray();
?>
<div style="max-height: 400px; overflow-y: auto;">

    <h1 class="mb-4">Commentaires enregistrés</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Avis</th>
                <th>Date</th>
                <th>Valide</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commentaires as $commentaire): ?>
                <tr>
                    <td><?= htmlspecialchars($commentaire['pseudo']) ?></td>
                    <td><?= htmlspecialchars($commentaire['avis']) ?></td>
                    <td><?= isset($commentaire['date']) ? $commentaire['date']->toDateTime()->format('d/m/Y H:i') : '—' ?></td>
                    <td>
                        <input type="checkbox" <?= $commentaire['valide'] ? 'checked' : '' ?>
                            onchange="toggleValidation('<?= $commentaire['_id'] ?>', this.checked)">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Fonction pour valider ou invalider un commentaire
function toggleValidation(id, checked) {
    fetch('/page/api/valider_commentaire.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, valide: checked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Validation mise à jour");
        } else {
            console.error("Erreur serveur :", data.error);
        }
    })
    .catch(error => {
        console.error("Erreur AJAX :", error);
    });
}
</script>
</body>
</html>
