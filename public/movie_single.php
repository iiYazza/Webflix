<?php

// Inclure le header
require __DIR__ . '/../partials/header.php';

/**
 * Récupèrer les informations du film
 * (<a href="./index.php?id=10">Lien</a>)
 * 1/ Récupèrer l'id dans l'url
 * 2/ Vérifier que l'id est correct
 * 3/ Exécuter la requête pour récupèrer le film en BDD grâce à l'ID
 * 4/ Si le film existe, on affiche les informations
 * (Une colonne de 6 avec l'image et une colonne de 6 avec le titre et la desc)
 * 5/ Si le film n'existe pas, on affiche un message.
 */

$id = intval($_GET['id'] ?? 0); // Je récupère l'id du film dans l'url et je vérifie

// Exécuter la requête
$movie = $db->query('SELECT * FROM movie WHERE id = '.$id)->fetch();

// Si le film existe
if ($movie) { 
?>

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <img class="img-fluid" src="uploads/<?= $movie['cover']; ?>" alt="<?= $movie['title']; ?>">
        </div>
        <div class="col-lg-6">
            <h1><?= $movie['title']; ?></h1>
            <!--
                Dans la BDD, on stocke la durée sous forme de minutes : 120
                Sur la fiche du film, il faut afficher 2h00.
                On va créer une fonction convertToHours(300) -> 5h00
            -->
            <p>Durée: <?= convertToHours($movie['duration']); ?></p>

            <?php
                // L'objet DateTime
                $date = new DateTime($movie['released_at']); // Générer la date du film
                // echo $date->format('d F Y');
            ?>

            <p>Sorti le <?= $date->format('d F Y'); ?></p>
            <div>
                <?= $movie['description']; ?>
            </div>

            <?php
                /**
                 * Pour les acteurs
                 * 1/ On va devoir ajouter des acteurs dans la BDD
                 * 2/ Lier des acteurs à leurs films (table movie_has_actor)
                 * 3/ Modifier le ul ci dessous pour afficher en dynamique les
                 * acteurs de ce film
                 * 4/ BONUS : On pourra cliquer sur un acteur et voir tous les films
                 * dans lesquels il a joué
                 */
                $actors = $db->query(
                   "SELECT * FROM movie_has_actor
                    INNER JOIN actor ON actor_id = id
                    WHERE movie_id = $id
                ")->fetchAll();
            ?>
            <div class="mt-5">
                <h5>Avec :</h5>
                <ul class="list-unstyled">
                    <?php foreach ($actors as $actor) { 
                        $fullname = $actor['firstname'].''.$actor['lastname'];
                        ?>
                        <li>
                            
                            <a href="https://fr.wikipedia.org/wiki/<?= $fullname; ?>"target=" target=</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
} else { // Si le film n'existe pas
    echo '<div class="alert alert-danger">Ce film n\'existe pas</div>';
}

// Inclure le footer
require __DIR__ . '/../partials/footer.php';