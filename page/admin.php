<?php
// admin.php
include '../config/For_User/Animals_Control.php';
include '../config/For_User/Habitat_Control.php';
include '../config/For_User/Service_Control.php';
include '../config/For_User/User_Control.php';
include '../config/For_User/Rapport_Control.php';
//require_once '../config/For_Watch/Auth_User/auth_Admin.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration du Zoo</title>
    <link rel="stylesheet" href="../source/css/style.css">
    <link rel="stylesheet" href="../source/css/header_footer.css">
    <link rel="stylesheet" href="../source/css/forPage.css">
    <script src="../source/java/Header_Footer.js" defer></script>
</head>
<body>
<header>
    <nav>
        <ul id="header"></ul>
    </nav>
</header>
<main>
<h1>Administration du Zoo</h1>

<!-- GESTION UTILISATEURS -->
<section class="gestion_utilisateurs">
<div>
    <h2>Gestion des Utilisateurs</h2>
    <form action="admin.php" method="POST">
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        </br>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        </br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        </br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        </br>
        <label for="role">Rôle :</label>
        <select id="role" name="role" required>
            <option value="2">Vétérinaire</option>
            <option value="3">Employé</option>
        </select>
        <button type="submit" name="add_user">Créer le compte</button>
    </form>
</div>

<div>
    <h3>Liste des Utilisateurs</h3>
    <table>
        <tr><th>Prénom</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Action</th></tr>
        <?php
        $stmt = $pdo->query("SELECT user_id, prenom, nom, email, role_id FROM utilisateur");
        while ($u = $stmt->fetch()) {
            echo "<tr>
                <td>{$u['prenom']}</td>
                <td>{$u['nom']}</td>
                <td>{$u['email']}</td>
                <td>{$u['role_id']}</td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='user_id' value='{$u['user_id']}'>
                        <button type='submit' name='delete_user'>Supprimer</button>
                    </form>
                </td></tr>";
        }
        ?>
    </table>
</div>
</section>

<!-- GESTION ANIMAUX -->
<section class="gestion_animaux">
<h2>Gestion des Animaux</h2>
<form action="admin.php" method="POST" enctype="multipart/form-data">
    <label for="animal_prenom">Prénom :</label>
    <input type="text" id="animal_prenom" name="prenom" required>
    </br>
    <label for="etat">État :</label>
    <select id="etat" name="etat">
        <option value="Sain">Sain</option>
        <option value="Malade">Malade</option>
        <option value="Blessé">Blessé</option>
    </select>
    </br>
    <label for="race_id">Race :</label>
    <select id="race_id" name="race_id" required>
        <?php
        $races = $pdo->query("SELECT race_id, label FROM race")->fetchAll();
        foreach ($races as $race) {
            echo "<option value='{$race['race_id']}'>{$race['label']}</option>";
        }
        ?>
    </select>
    </br>
    <label for="habitat_id">Habitat :</label>
    <select id="habitat_id" name="habitat_id" required>
        <?php
        $habitats = $pdo->query("SELECT habitat_id, nom FROM habitat")->fetchAll();
        foreach ($habitats as $habitat) {
            echo "<option value='{$habitat['habitat_id']}'>{$habitat['nom']}</option>";
        }
        ?>
    </select>

    <label for="animal_image">Image :</label>
    <input type="file" id="animal_image" name="animal_image">

    <button type="submit" name="add_animal">Ajouter l'animal</button>
</form>

<h3>Liste des Animaux</h3>
<table><tr><th>Prénom</th><th>État</th><th>Race</th><th>Habitat</th><th>Image</th><th>Action</th></tr>
<?php
$stmt = $pdo->query("SELECT * FROM animal");
while ($a = $stmt->fetch()) {
    echo "<tr>
        <td>{$a['prenom']}</td>
        <td>{$a['etat']}</td>
        <td>{$a['race_id']}</td>
        <td>{$a['habitat_id']}</td>
        <td><img src='../{$a['img_path']}' height='40'></td>
        <td>
            <form method='POST'><input type='hidden' name='animal_id' value='{$a['animal_id']}'>
            <button type='submit' name='delete_animal'>Supprimer</button></form>
        </td>
    </tr>";
}
?>
</table>
</section>

<!-- GESTION RACES -->
<section class="gestion_races">
<h2>Gestion des Races</h2>
<form action="admin.php" method="POST">
    <label for="race_label">Nom de la race :</label><input type="text" id="race_label" name="label" required>
    <button type="submit" name="add_race">Ajouter la race</button>
</form>
<h3>Liste des Races</h3>
<table><tr><th>ID</th><th>Nom</th><th>Action</th></tr>
<?php
$stmt = $pdo->query("SELECT * FROM race");
while ($r = $stmt->fetch()) {
    echo "<tr>
        <td>{$r['race_id']}</td>
        <td>{$r['label']}</td>
        <td>
            <form method='POST'><input type='hidden' name='race_id' value='{$r['race_id']}'>
            <button type='submit' name='delete_race'>Supprimer</button></form>
        </td>
    </tr>";
}
?>
</table>
</section>

<!-- GESTION HABITATS -->
<section class="gestion_habitats">
<h2>Gestion des Habitats</h2>
<form action="admin.php" method="POST" enctype="multipart/form-data">
    <label for="habitat_nom">Nom :</label><input type="text" id="habitat_nom" name="nom" required></br>
    <label for="habitat_description">Description :</label><textarea id="habitat_description" name="description"></textarea></br>
    <label for="habitat_commentaire">Commentaire :</label><textarea id="habitat_commentaire" name="commentaire_habitat"></textarea></br>
    <label for="habitat_image">Image :</label><input type="file" id="habitat_image" name="habitat_image"></br>
    <button type="submit" name="add_habitat">Ajouter l'habitat</button>
</form>
<h3>Liste des Habitats</h3>
<table><tr><th>Nom</th><th>Description</th><th>Commentaire</th><th>Image</th><th>Action</th></tr>
<?php
$stmt = $pdo->query("SELECT * FROM habitat");
while ($h = $stmt->fetch()) {
    echo "<tr>
        <td>{$h['nom']}</td>
        <td>{$h['description']}</td>
        <td>{$h['commentaire_habitat']}</td>
        <td><img src='../{$h['img_path']}' height='40'></td>
        <td>
            <form method='POST'><input type='hidden' name='habitat_id' value='{$h['habitat_id']}'>
            <button type='submit' name='delete_habitat'>Supprimer</button></form>
        </td>
    </tr>";
}
?>
</table>
</section>

<!-- GESTION SERVICES -->
<section class="gestion_services">
<h2>Gestion des Services</h2>
<form action="admin.php" method="POST">
    <label for="service_nom">Nom :</label><input type="text" id="service_nom" name="nom" required>
    <label for="service_description">Description :</label><textarea id="service_description" name="description"></textarea>
    <button type="submit" name="add_service">Ajouter le service</button>
</form>
<h3>Liste des Services</h3>
<table><tr><th>Nom</th><th>Description</th><th>Action</th></tr>
<?php
$stmt = $pdo->query("SELECT * FROM service");
while ($s = $stmt->fetch()) {
    echo "<tr>
        <td>{$s['nom']}</td>
        <td>{$s['description']}</td>
        <td>
            <form method='POST'><input type='hidden' name='service_id' value='{$s['service_id']}'>
            <button type='submit' name='delete_service'>Supprimer</button></form>
        </td>
    </tr>";
}
?>
</table>
</section>

<!-- GESTION RAPPORTS VÉTÉRINAIRES -->
<section class="gestion_rapports">
    <h2>Rapports Vétérinaires</h2>

    <!-- Formulaire d'ajout -->
    <form action="admin.php" method="POST">
        <label for="date_rapport">Date :</label>
        <input type="date" id="date_rapport" name="date" required>

        <label for="detail_rapport">Détail :</label>
        <textarea id="detail_rapport" name="detail" required></textarea>

        <label for="animal_id">Animal :</label>
        <select id="animal_id" name="animal_id" required>
            <?php
            $animaux = $pdo->query("SELECT animal_id, prenom FROM animal")->fetchAll();
            foreach ($animaux as $a) {
                echo "<option value='{$a['animal_id']}'>{$a['prenom']}</option>";
            }
            ?>
        </select>

        <label for="user_id">Vétérinaire :</label>
        <select id="user_id" name="user_id" required>
            <?php
            $veterinaires = $pdo->query("SELECT user_id, prenom, nom FROM utilisateur WHERE role_id = 2")->fetchAll();
            foreach ($veterinaires as $v) {
                echo "<option value='{$v['user_id']}'>{$v['prenom']} {$v['nom']}</option>";
            }
            ?>
        </select>

        <button type="submit" name="add_rapport">Ajouter le rapport</button>
    </form>

    <!-- Tableau de rapports -->
    <h3>Liste des Rapports</h3>
    <table>
        <tr><th>Date</th><th>Détail</th><th>Animal</th><th>Vétérinaire</th><th>Action</th></tr>
        <?php
        $stmt = $pdo->query("
            SELECT r.rapport_veterinaire_id, r.date, r.detail, a.prenom AS animal_nom, u.prenom AS user_prenom, u.nom AS user_nom
            FROM rapport_veterinaire r
            JOIN animal a ON r.animal_id = a.animal_id
            JOIN utilisateur u ON r.user_id = u.user_id
        ");
        while ($rp = $stmt->fetch()) {
            echo "<tr>
                <td>{$rp['date']}</td>
                <td>{$rp['detail']}</td>
                <td>{$rp['animal_nom']}</td>
                <td>{$rp['user_prenom']} {$rp['user_nom']}</td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='rapport_veterinaire_id' value='{$rp['rapport_veterinaire_id']}'>
                        <button type='submit' name='delete_rapport'>Supprimer</button>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </table>
</section>

</main>
</body>
</html>
