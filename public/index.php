<?php

// Inclure le header
require __DIR__ . '/../partials/header.php';

/**
 * Page d'accueil :
 * 
 * 1: Remplir la BDD avec des films
 * 2: Ecrire la requête SQL qui permet de récupèrer 4 films aléatoire dans la BDD.
 * 3: On récupère un tableau de films
 * 4: On parcours ce tableau de films et pour chaque film, on affiche une card Bootstrap. On doit avoir 4 cards sur une ligne.
 */

$movies = $db->query('SELECT * FROM movie ORDER BY RAND() LIMIT 4')->fetchAll();
?>

<div class="container">
    <div class="row">
        <?php foreach ($movies as $movie) { ?>
            <div class="col-lg-3">
                <div class="card shadow">
                    <img src="uploads/<?= $movie['cover']; ?>" class="card-img-top" alt="<?= $movie['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $movie['title']; ?></h5>
                        <h6>Sorti en <?= substr($movie['released_at'], 0, 4); ?></h6>
                        <p class="card-text"><?= truncate($movie['description']); ?></p>
                        <a href="movie_single.php?id=<?= $movie['id']; ?>" class="btn btn-danger btn-block">Voir le film</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
// Inclure le footer
require __DIR__ . '/../partials/footer.php';