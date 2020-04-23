<?php

/**
 * Formulaire d'ajout de film
 * 
 * Ici, on va créer un formulaire permettant d'ajouter un film.
 * Le champ title devra faire 2 caractères minimum.
 * Le champ description devra faire 15 caractères minimum.
 * On pourra uploader une jaquette. Le nom du fichier uploadé doit être le nom du film "transformé", "Le Parrain" -> "le-parrain.jpg"
 * Le champ durée devra être un nombre entre 1 et 999.
 * Le champ released_at devra être une date valide.
 * Le champ category devra être un select généré dynamiquement avec les catégories de la BDD
 * On doit afficher les messages d'erreurs et s'il n'y a pas d'erreurs on ajoute le film et on redirige sur la page movie_list.php
 * BONUS : Il faudrait afficher un message de succès après la redirection. Il faudra utiliser soit la session, soit un paramètre dans l'URL
 */

// Inclure le header
require __DIR__ . '/../partials/header.php';

// On autorise que les admin à aller sur cette page
if (!isAdmin()) {
    echo 'Interdit';
    require __DIR__ . '/../partials/footer.php';
    die();
}

$categories = $db->query('SELECT * FROM category')->fetchAll();

$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$cover = $_FILES['cover'] ?? null;
$duration = $_POST['duration'] ?? null;
$released_at = $_POST['released_at'] ?? null;
$categorySelected = $_POST['category'] ?? null;

// Traitement formulaire
if (!empty($_POST)) {
    $errors = [];

    if (strlen($title) < 2) {
        $errors['title'] = 'Le titre est trop court';
    }

    if (strlen($description) < 15) {
        $errors['description'] = 'La description est trop courte';
    }

    if ($duration < 1 || $duration > 999) {
        $errors['duration'] = 'La durée n\'est pas bonne';
    }

    $released_at = empty($released_at) ? '0000-00-00' : $released_at;
    $date = explode('-', $released_at);

    if (!checkdate($date[1], $date[2], $date[0])) {
        $errors['released_at'] = 'La date n\'est pas bonne';
    }

    //if (!$categorySelected) {
    //    $errors['category'] = 'La categorie n\'existe pas';
    //}

    if ($cover['error'] === 0) {
        // On fait l'upload
        // Récupèrer l'emplacement temporaire du fichier
        $file = $cover['tmp_name'];
        // Renommer le fichier (optionnel)
        $originalName = $cover['name'];
        // Récupère l'extension du fichier
        $extension = pathinfo($originalName)['extension']; // jpg, pdf, png...
        $filename = str_replace([' ', ','], '-', strtolower($title)).'.'.$extension;
        // Le, Parrain => le-parrain.jpg
        // Déplacer le fichier vers un répertoire
        move_uploaded_file($file, __DIR__.'/uploads/'.$filename);
    } else {
        $errors['cover'] = 'Il faut une image';
    }

    // On fait la requête
    if (empty($errors)) {
        $query = $db->prepare(
            'INSERT INTO movie (title, description, cover, duration, released_at, category_id)
             VALUES (:title, :description, :cover, :duration, :released_at, :category_id)'
        );
        $query->bindValue(':title', $title);
        $query->bindValue(':description', $description);
        $query->bindValue(':cover', $filename);
        $query->bindValue(':duration', $duration);
        $query->bindValue(':released_at', $released_at);
        $query->bindValue(':category_id', $categorySelected);
        $query->execute();

        // header('Location: movie_list.php?success=1');
        echo '<meta http-equiv="refresh" content="0;URL=\'movie_list.php?success=1\'">';
    } else {
        /**
         * Afficher les erreurs
         */
        echo '<div class="container">';
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
        echo '</div>';
    }

}

?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= $title; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"><?= $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="cover">Jaquette</label>
                    <input type="file" name="cover" id="cover" class="form-control">
                </div>
                <div class="form-group">
                    <label for="duration">Durée</label>
                    <input type="text" name="duration" id="duration" class="form-control" value="<?= $duration; ?>">
                </div>
                <div class="form-group">
                    <label for="released_at">Sortie</label>
                    <input type="date" name="released_at" id="released_at" class="form-control" value="<?= $released_at; ?>">
                </div>
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select class="form-control" name="category">
                        <?php foreach ($categories as $category) { ?>
    <option <?= ($category['id'] == $categorySelected) ? 'selected' : ''; ?> value="<?= $category['id']; ?>">
                                <?= $category['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button class="btn btn-danger btn-block">Ajouter</button>
            </form>
        </div>
    </div>
</div>

<?php
// Inclure le footer
require __DIR__ . '/../partials/footer.php';