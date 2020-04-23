<?php

/**
 * 1/ Dans ce fichier, on doit afficher TOUS les films par ordre de sortie.
 * 2/ Dans le dropdown des catégories, si on clique sur une catégorie,
 * on arrive aussi sur ce fichier mais avec un paramètre dans l'url
 * (movie_list.php?idCategory=5). Dans ce cas, on affiche UNIQUEMENT les films
 * de cette catégorie.
 * 3/ BONUS: On ajoute un dropdown qui permet de choisir le tri par nom,
 * par date, par durée...
 */

// Inclure le header
require __DIR__ . '/../partials/header.php';

// Si on a cliqué sur le dropdown des catégories
if (isset($_GET['idCategory'])) {
    // On récupère les films de la catégorie
    $id = intval($_GET['idCategory']);
    $sort = $_GET['sort'] ?? 'released_at';
    $url = 'movie_list.php?idCategory='.$id.'&';
    $movies = $db->query("SELECT * FROM movie WHERE category_id = $id ORDER BY $sort DESC");
} else {
    // Récupèrer tous les films
    $sort = $_GET['sort'] ?? 'released_at'; // isset($_GET['sort']) ? $_GET['sort'] : 'released_at';
    $url = 'movie_list.php?'; // On se sert de cette variable pour générer le lien du tri
    $movies = $db->query("SELECT * FROM movie ORDER BY $sort DESC");
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="container"><div class="alert alert-success">Le film a bien été ajouté.</div></div>';
} 

// J'affiche les films ?>
<div class="container">
    <div class="dropdown mb-4">
        <a href="#" class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">Trier par</a>
        <div class="dropdown-menu">
            <a href="<?= $url; ?>sort=title" class="dropdown-item">Nom</a>
            <a href="<?= $url; ?>sort=duration" class="dropdown-item">Durée</a>
            <a href="<?= $url; ?>sort=released_at" class="dropdown-item">Date</a>
        </div>
    </div>
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
                        <?php if (isAdmin()) { ?>
                            <a href="movie_update.php?id=<?= $movie['id']; ?>" class="btn btn-secondary btn-block">Modifier</a>
                            <a href="movie_delete.php?id=<?= $movie['id']; ?>"
                               onclick="return confirm('Voulez-vous supprimer le film ?');"
                               class="btn btn-secondary btn-block">
                               Supprimer</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php

// Inclure le footer
require __DIR__ . '/../partials/footer.php';