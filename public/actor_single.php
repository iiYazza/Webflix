<?php

// Inclure le header
require __DIR__ . '/../partials/header.php';

$id = intval($_GET['id'] ?? 0); // Je récupère l'id de l'acteur dans l'url et je vérifie

// Exécuter la requête
$actor = $db->query('SELECT * FROM actor WHERE id = '.$id)->fetch();

// Si l'acteur existe
if ($actor) { 
?>

<div class="container">
    <h1>Les films de <?= $actor['firstname'].' '.$actor['lastname']; ?></h1>
    <?php
        // On va chercher les films de l'acteur
        $movies = $db->query(
            "SELECT * FROM movie_has_actor
             INNER JOIN movie ON movie_id = id
             WHERE actor_id = $id
        ")->fetchAll();
    ?>
    <div class="row">
        <?php foreach ($movies as $movie) { ?>
            <div class="col-lg-3">
                <div class="card shadow mb-4">
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
} else { // Si l'acteur n'existe pas
    echo '<div class="alert alert-danger">Cet acteur n\'existe pas</div>';
}
// Inclure le footer
require __DIR__ . '/../partials/footer.php';