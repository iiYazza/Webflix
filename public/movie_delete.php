<?php

// Je veux supprimer un film

// Connexion à la bdd
session_start();
require __DIR__.'/../config/config.php';
require __DIR__.'/../config/database.php';
require __DIR__.'/../config/functions.php';

// On vérifie si l'utilisateur est un admin
if (!isAdmin()) {
    echo 'Interdit';
    require __DIR__ . '/../partials/footer.php';
    die();
}

// Je récupère l'id du film
$id = intval($_GET['id'] ?? 0);

// Requête pour supprimer le film
$db->query('DELETE FROM movie WHERE id = '.$id);

header('Location: movie_list.php');