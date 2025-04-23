<?php

$timeout = 30*60; // 30 minutes
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 1) {
    // Redirection vers la page de connexion ou index
    echo "🔒 Accès refusé : vous n'êtes pas connecté ou vous n'avez pas les droits.";
    exit;
}

// admin.php
include '../config/For_User/Animals_Control.php';
include '../config/For_User/Habitat_Control.php';
include '../config/For_User/Service_Control.php';
include '../config/For_User/User_Control.php';
include '../config/For_User/Rapport_Control.php';
require_once '../config/For_Watch/Auth_User/auth_Admin.php';
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
        <!-- Formulaire d'ajout utilisateur -->
        <form action="admin.php" method="POST">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>
            <br>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <br>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="role_id">Rôle :</label>
            <select id="role_id" name="role_id" required>
                <option value="1">Administrateur</option>
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
            try {
                // Requête sécurisée pour éviter les injections
                $stmt = $pdo->query("SELECT user_id, prenom, nom, email, role_id FROM utilisateur");
                while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($u['prenom']) . "</td>
                        <td>" . htmlspecialchars($u['nom']) . "</td>
                        <td>" . htmlspecialchars($u['email']) . "</td>
                        <td>" . htmlspecialchars($u['role_id']) . "</td>
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='user_id' value='" . htmlspecialchars($u['user_id']) . "'>
                                <button type='submit' name='delete_user'>Supprimer</button>
                            </form>
                        </td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='5'>Erreur lors de la récupération des utilisateurs.</td></tr>";
                error_log("Erreur chargement utilisateurs : " . $e->getMessage());
            }
            ?>
        </table>
    </div>
</section>

<!-- GESTION ANIMAUX -->
<section class="gestion_animaux">
<h2>Gestion des Animaux</h2>
<!-- Formulaire pour ajouter un nouvel animal -->
<form action="admin.php" method="POST" enctype="multipart/form-data">
    <label for="animal_prenom">Prénom :</label>
    <input type="text" id="animal_prenom" name="prenom" required>
    <br>
    <label for="etat">État :</label>
    <select id="etat" name="etat">
        <option value="Sain">Sain</option>
        <option value="Malade">Malade</option>
        <option value="Blessé">Blessé</option>
    </select>
    <br>
    <label for="race_id">Race :</label>
    <select id="race_id" name="race_id" required>
        <?php
        try {
            // Liste déroulante des races (chargées dynamiquement)
            $races = $pdo->query("SELECT race_id, label FROM race")->fetchAll();
            foreach ($races as $race) {
                echo "<option value='" . htmlspecialchars($race['race_id']) . "'>" . htmlspecialchars($race['label']) . "</option>";
            }
        } catch (PDOException $e) {
            echo "<option disabled>Erreur chargement races</option>";
            error_log("Erreur chargement races : " . $e->getMessage());
        }
        ?>
    </select>
    <br>
    <label for="habitat_id">Habitat :</label>
    <select id="habitat_id" name="habitat_id" required>
        <?php
        try {
            // Liste déroulante des habitats (chargés dynamiquement)
            $habitats = $pdo->query("SELECT habitat_id, nom FROM habitat")->fetchAll();
            foreach ($habitats as $habitat) {
                echo "<option value='" . htmlspecialchars($habitat['habitat_id']) . "'>" . htmlspecialchars($habitat['nom']) . "</option>";
            }
        } catch (PDOException $e) {
            echo "<option disabled>Erreur chargement habitats</option>";
            error_log("Erreur chargement habitats : " . $e->getMessage());
        }
        ?>
    </select>
    <br>
    <label for="animal_image">Image :</label>
    <input type="file" id="animal_image" name="animal_image">

    <button type="submit" name="add_animal">Ajouter l'animal</button>
</form>

<h3>Liste des Animaux</h3>
<!-- Tableau d'affichage des animaux existants -->
<table><tr><th>Prénom</th><th>État</th><th>Race</th><th>Habitat</th><th>Image</th><th>Action</th></tr>
<?php
try {
    // Récupération des animaux enregistrés
    $stmt = $pdo->query("SELECT * FROM animal");
    while ($a = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>" . htmlspecialchars($a['prenom']) . "</td>
            <td>" . htmlspecialchars($a['etat']) . "</td>
            <td>" . htmlspecialchars($a['race_id']) . "</td>
            <td>" . htmlspecialchars($a['habitat_id']) . "</td>
            <td><img src='../" . htmlspecialchars($a['img_path']) . "' height='40'></td>
            <td>
                <form method='POST'>
                    <input type='hidden' name='animal_id' value='" . htmlspecialchars($a['animal_id']) . "'>
                    <button type='submit' name='delete_animal'>Supprimer</button>
                </form>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='6'>Erreur lors de la récupération des animaux.</td></tr>";
    error_log("Erreur chargement animaux : " . $e->getMessage());
}
?>
</table>
</section>

<!-- GESTION RACES -->
<section class="gestion_races">
<h2>Gestion des Races</h2>
<!-- Formulaire pour ajouter une nouvelle race -->
<form action="admin.php" method="POST">
    <label for="race_label">Nom de la race :</label>
    <input type="text" id="race_label" name="label" required>
    <button type="submit" name="add_race">Ajouter la race</button>
</form>

<h3>Liste des Races</h3>
<!-- Tableau listant toutes les races existantes avec option de suppression -->
<table><tr><th>ID</th><th>Nom</th><th>Action</th></tr>
<?php
try {
    // Récupération des races existantes
    $stmt = $pdo->query("SELECT * FROM race");
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>" . htmlspecialchars($r['race_id']) . "</td>
            <td>" . htmlspecialchars($r['label']) . "</td>
            <td>
                <form method='POST'>
                    <input type='hidden' name='race_id' value='" . htmlspecialchars($r['race_id']) . "'>
                    <button type='submit' name='delete_race'>Supprimer</button>
                </form>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='3'>Erreur lors de la récupération des races.</td></tr>";
    error_log("Erreur chargement races : " . $e->getMessage());
}
?>
</table>
</section>


<!-- GESTION HABITATS -->
<section class="gestion_habitats">
<h2>Gestion des Habitats</h2>
<!-- Formulaire d'ajout d'un habitat -->
<form action="admin.php" method="POST" enctype="multipart/form-data">
    <label for="habitat_nom">Nom :</label>
    <input type="text" id="habitat_nom" name="nom" required><br>

    <label for="habitat_description">Description :</label>
    <textarea id="habitat_description" name="description"></textarea><br>

    <label for="habitat_commentaire">Commentaire :</label>
    <textarea id="habitat_commentaire" name="commentaire_habitat"></textarea><br>

    <label for="habitat_image">Image :</label>
    <input type="file" id="habitat_image" name="habitat_image"><br>

    <button type="submit" name="add_habitat">Ajouter l'habitat</button>
</form>

<h3>Liste des Habitats</h3>
<!-- Tableau affichant les habitats existants avec leurs informations -->
<table><tr><th>Nom</th><th>Description</th><th>Commentaire</th><th>Image</th><th>Action</th></tr>
<?php
try {
    $stmt = $pdo->query("SELECT * FROM habitat");
    while ($h = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>" . htmlspecialchars($h['nom']) . "</td>
            <td>" . htmlspecialchars($h['description']) . "</td>
            <td>" . htmlspecialchars($h['commentaire_habitat']) . "</td>
            <td><img src='../" . htmlspecialchars($h['img_path']) . "' height='40'></td>
            <td>
                <form method='POST'>
                    <input type='hidden' name='habitat_id' value='" . htmlspecialchars($h['habitat_id']) . "'>
                    <button type='submit' name='delete_habitat'>Supprimer</button>
                </form>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='5'>Erreur lors de la récupération des habitats.</td></tr>";
    error_log("Erreur chargement habitats : " . $e->getMessage());
}
?>
</table>
</section>

<!-- GESTION SERVICES -->
<section class="gestion_services">
<h2>Gestion des Services</h2>
<!-- Formulaire d'ajout d'un service -->
<form action="admin.php" method="POST">
    <label for="service_nom">Nom :</label>
    <input type="text" id="service_nom" name="nom" required>

    <label for="service_description">Description :</label>
    <textarea id="service_description" name="description"></textarea>

    <button type="submit" name="add_service">Ajouter le service</button>
</form>

<h3>Liste des Services</h3>
<!-- Tableau des services disponibles -->
<table><tr><th>Nom</th><th>Description</th><th>Action</th></tr>
<?php
try {
    $stmt = $pdo->query("SELECT * FROM service");
    while ($s = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>" . htmlspecialchars($s['nom']) . "</td>
            <td>" . htmlspecialchars($s['description']) . "</td>
            <td>
                <form method='POST'>
                    <input type='hidden' name='service_id' value='" . htmlspecialchars($s['service_id']) . "'>
                    <button type='submit' name='delete_service'>Supprimer</button>
                </form>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='3'>Erreur lors de la récupération des services.</td></tr>";
    error_log("Erreur chargement services : " . $e->getMessage());
}
?>
</table>
</section>

<!-- GESTION RAPPORTS VÉTÉRINAIRES -->
<section class="gestion_rapports">
    <h2>Rapports Vétérinaires</h2>

    <!-- Formulaire d'ajout d'un nouveau rapport vétérinaire -->
    <form action="admin.php" method="POST">
        <label for="date_rapport">Date :</label>
        <input type="date" id="date_rapport" name="date" required>

        <label for="detail_rapport">Détail :</label>
        <textarea id="detail_rapport" name="detail" required></textarea>

        <label for="animal_id">Animal :</label>
        <select id="animal_id" name="animal_id" required>
            <?php
            try {
                // Récupération des animaux disponibles pour associer au rapport
                $animaux = $pdo->query("SELECT animal_id, prenom FROM animal")->fetchAll();
                foreach ($animaux as $a) {
                    echo "<option value='" . htmlspecialchars($a['animal_id']) . "'>" . htmlspecialchars($a['prenom']) . "</option>";
                }
            } catch (PDOException $e) {
                echo "<option disabled>Erreur chargement animaux</option>";
                error_log("Erreur chargement animaux (formulaire rapport) : " . $e->getMessage());
            }
            ?>
        </select>

        <label for="user_id">Vétérinaire :</label>
        <select id="user_id" name="user_id" required>
            <?php
            try {
                // Récupération des utilisateurs vétérinaires uniquement (role_id = 2)
                $veterinaires = $pdo->query("SELECT user_id, prenom, nom FROM utilisateur WHERE role_id = 2")->fetchAll();
                foreach ($veterinaires as $v) {
                    echo "<option value='" . htmlspecialchars($v['user_id']) . "'>" . htmlspecialchars($v['prenom']) . " " . htmlspecialchars($v['nom']) . "</option>";
                }
            } catch (PDOException $e) {
                echo "<option disabled>Erreur chargement vétérinaires</option>";
                error_log("Erreur chargement vétérinaires (formulaire rapport) : " . $e->getMessage());
            }
            ?>
        </select>

        <button type="submit" name="add_rapport">Ajouter le rapport</button>
    </form>
    <!-- Tableau récapitulatif des rapports existants -->
    <h3>Liste des Rapports</h3>
    <table>
        <tr><th>Date</th><th>Détail</th><th>Animal</th><th>Vétérinaire</th><th>Action</th></tr>
        <?php
        try {
            // Récupération des rapports avec jointures sur animal et utilisateur
            $stmt = $pdo->query("
                SELECT r.rapport_veterinaire_id, r.date, r.detail, a.prenom AS animal_nom, u.prenom AS user_prenom, u.nom AS user_nom
                FROM rapport_veterinaire r
                JOIN animal a ON r.animal_id = a.animal_id
                JOIN utilisateur u ON r.user_id = u.user_id
            ");
            while ($rp = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>" . htmlspecialchars($rp['date']) . "</td>
                    <td>" . htmlspecialchars($rp['detail']) . "</td>
                    <td>" . htmlspecialchars($rp['animal_nom']) . "</td>
                    <td>" . htmlspecialchars($rp['user_prenom']) . " " . htmlspecialchars($rp['user_nom']) . "</td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='rapport_veterinaire_id' value='" . htmlspecialchars($rp['rapport_veterinaire_id']) . "'>
                            <button type='submit' name='delete_rapport'>Supprimer</button>
                        </form>
                    </td>
                </tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='5'>Erreur lors de la récupération des rapports.</td></tr>";
            error_log("Erreur chargement rapports vétérinaires : " . $e->getMessage());
        }
        ?>
    </table>
</section>
<section>
    <?php include '../source/php/forAdmin/admin_consultationsMongo.php';?>
</section>

</main>

</body>
</html>
