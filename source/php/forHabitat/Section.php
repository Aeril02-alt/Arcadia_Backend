<?php
include('../config/For_watch/fetch_habitatByAnimal.php');
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
                        <details class="boutonAnimal" <?php echo isset($animal['animal_id']) ? 'data-animal-id="' . htmlspecialchars($animal['animal_id']) . '"' : ''; ?>>
                            <summary><strong>Nos animaux</strong></summary>
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
</main>
