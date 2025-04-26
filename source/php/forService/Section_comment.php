<?php
// Inclure la configuration de la base de données et la fonction de service
require_once __DIR__ . '/../../config/init.php';
require_once CONFIG_PATH . '/db_config.php';
require_once CONFIG_PATH . '/For_watch/fetch_services.php';


// Appeler la fonction pour récupérer les services
$services = getServices($pdo);
?>

<!-- Code HTML pour afficher les données -->
<main id="servicesMain">
    <?php if (!empty($services)): ?>
        <?php foreach ($services as $item): ?>
            <section class="principal">
                <div class="service">
                    <h2><?php echo htmlspecialchars($item['nom']); ?></h2>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun service disponible pour le moment.</p>
    <?php endif; ?>
</main>
