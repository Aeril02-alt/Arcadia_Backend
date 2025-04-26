<?php

require_once __DIR__ . '/../../config/init.php';
require_once CONFIG_PATH . '/For_watch/fetch_habitatByAnimal.php';

?>
<!-- Code HTML pour afficher les données -->
<main>
    <?php foreach ($habitats as $habitat_id => $habitat): ?>
        <section class="principalHabitat">
            <aside class="sectionHabitat">
                <h2><?php echo htmlspecialchars($habitat['nom']); ?></h2>
                <figure>
                    <!-- ➤ Appel à image_habitat.php -->
                    <img src="../<?php echo htmlspecialchars($habitat['img_path']); ?>"
                        alt="Image de <?php echo htmlspecialchars($habitat['nom']); ?>"
                        onerror="handleHabitatFallback(this)">
                    <figcaption><?php echo htmlspecialchars($habitat['description']); ?></figcaption>
                </figure>

                <aside class="boutonAnimaux">
                    <?php foreach ($habitat['animaux'] as $animal): ?>
                        <details
                                    class="boutonAnimal"
                                    data-animal-id="<?= (int)$animal['animal_id'] ?>"
                                    data-prenom="<?= htmlspecialchars($animal['prenom']) ?>"
                                >
                                <summary onclick="handleConsultation(this, event)">
                                    <strong>Nos animaux</strong>
                                </summary>
                            <p><strong> Prénom : <?php echo htmlspecialchars($animal['prenom']); ?></strong></p>
                            <p>État: <?php echo htmlspecialchars($animal['etat']); ?></p>
                            <p><?php echo htmlspecialchars($animal['race']); ?></strong></p>

                            <?php if (!empty($animal['animal_id'])): ?>
                                <!-- ➤ Appel à image_animal.php -->
                                <img src="../<?php echo htmlspecialchars($animal['img_path']); ?>"
                                    alt="Image de <?php echo htmlspecialchars($animal['prenom']); ?>"
                                    onerror="handleImageFallback(this)">
                            <?php endif; ?>
                        </details>
                    <?php endforeach; ?>
                </aside>
            </aside>

            <section class="commentaireHabitat">
                <p><?php echo htmlspecialchars($habitat['commentaire_habitat']); ?></p>
            </section>
        </section>
    <?php endforeach; ?>
    
    <script>
            function handleConsultation(summaryElement, event) {
            if (event) event.stopPropagation(); // empêche les effets du toggle <details> (si ça interfère)

            const details = summaryElement.closest('details');
            const animalId = details.dataset.animalId;
            const prenom = details.dataset.prenom;

            if (!animalId || !prenom) {
                console.warn("ID ou prénom manquant.");
                return;
            }

            fetch('/page/api/increment_consultation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `animal_id=${animalId}&prenom=${encodeURIComponent(prenom)}`
            })
            .then(res => res.text())
            .then(text => {
                console.log("Réponse brute du serveur :", text);
                try {
                const data = JSON.parse(text);
                if (data.success) {
                    console.log(`✅ Consultation enregistrée pour ${prenom} (ID ${animalId})`);
                } else {
                    console.error('❌ Erreur :', data.error || data.message);
                }
                } catch (e) {
                console.error("❌ Erreur JSON :", e.message);
                }
            })
            .catch(err => {
                console.error('❌ Erreur fetch :', err);
            });
            }
</script>
</main>