<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitats du Zoo Arcadia</title>
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
    <?php include '../source/php/forHabitat/Section.php'; ?>
    
    <!--
    <main>  Création d'un menu pour les différents habitats 
        <section class="principal"> Encart principal contenant tous les éléments de l'habitat 
            
             Zone pour afficher la photo de l'habitat 
            <div class="photoHabitat">
                 éléments pour interagir avec les animaux de cet habitat 
                <section class="boutonAnimaux">
                     Ajoutez ici des boutons ou des liens vers les animaux spécifiques de cet habitat 
                </section>
            </div>
            
             Section pour afficher les commentaires relatifs à l'habitat 
            <section class="commentaireHabitat">
                 Contenu des commentaires ou espace pour affichage dynamique 
            </section>
            
        </section> 
    </main>
    -->

    <footer>
        <nav>
            <ul id="footer"></ul>
        </nav>
    </footer>
<script>
    document.querySelectorAll('.boutonAnimal').forEach((element) => {
    element.addEventListener('click', () => {
        const animalId = element.getAttribute('data-animal-id'); // appel la classe animal specifique de <detail> 
        fetch('../config/update_compteur.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ animalId: animalId }),
        });
    });
});
</script>
</body>
</html>