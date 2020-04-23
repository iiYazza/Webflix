<?php
  session_start();

  require_once __DIR__ . '/../config/config.php';
  require_once __DIR__ . '/../config/database.php';
  require_once __DIR__ . '/../config/functions.php';
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" type="Webflix_favicone.png" href="../public/assets/img/Webflix_favicone.png"/>

    <title>Webflix</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container">
        <a class="navbar-brand text-danger font-weight-bold" href="index.php"><img src="../public/assets/img/Webflix_logo.png" alt="Webflix logo"/></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="movie_list.php">Nos films</a>
            </li>
            <li class="nav-item">
              <div class="dropdown pl-3">
                <button class="btn btn-outline-danger dropdown-toggle" type="button" data-toggle="dropdown">
                  Nos catégories
                </button>
                <?php
                  // 1/ Ecrire la requête pour récupèrer les catégories du site
                  // 2/ Parcourir le tableau de catégorie et "remplir" le menu dropdown
                  // On peut s'inspirer de l'exercice sur le formulaire d'ajout de film
                  // BONUS/ Ranger le code précédent dans une fonction getCategories()
                  // $categories = getCategories();
                  $categories = $db->query('SELECT * FROM category')->fetchAll();
                  $categories = getCategories();
                ?>
                <div class="dropdown-menu">
                  <?php foreach ($categories as $category) { ?>
                    <a class="dropdown-item" href="movie_list.php?idCategory=<?= $category['id'] ?>">
                      <?= $category['name']; ?>
                    </a>
                  <?php } ?>
                </div>
              </div>
            </li>
          </ul>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Recherche...">
            <button class="btn btn-outline-danger my-2 my-sm-0">Go</button>
          </form>
          <?php
            /**
             * Ici, on va afficher l'email de l'utilisateur s'il est connecté (Se baser sur $_SESSION)
             * Sinon, on affiche les liens vers Connexion et inscription.
             * On va afficher l'avatar de l'utilisateur, on utilise gravatar
             * La doc : https://fr.gravatar.com/site/implement/images/
             * Vous créez un compte avec votre email, vous uploadez un avatar
             * On affiche une balise img dans le header et on affiche un lien vers le gravatar
             * https://www.gravatar.com/avatar/MD5 DE VOTRE EMAIL
             */
          ?>
          <?php if (isset($_SESSION['user'])) { ?>
            <ul class="navbar-nav ml-0 ml-lg-4">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                  <img src="https://www.gravatar.com/avatar/<?= md5($_SESSION['user']['email']); ?>"
                       width="40"
                       class="rounded-circle mr-3"
                  >
                  <?= $_SESSION['user']['username']; ?>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="account.php">Mon compte</a>
                  <a class="dropdown-item" href="logout.php">Déconnexion</a>
                </div>
              </li>
            </ul>
          <?php } else { ?>
            <ul class="navbar-nav ml-0 ml-lg-4">
              <li class="nav-item">
                <a class="btn btn-danger" href="login.php">Connexion</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="register.php">Inscription</a>
              </li>
            </ul>
          <?php } ?>
        </div>
      </div>
    </nav>