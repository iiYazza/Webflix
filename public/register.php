<?php

/**
 * Inscription de l'utilisateur
 * 
 * 1/ Ajouter le header et le footer dans ce fichier.
 * 2/ Ajouter un formulaire sur la page
 *    On aura 4 champs : email, username, password, confirm-password.
 *    L'email devra être valide, le mot de passe devra correspondre au champ confirm-password
 *    Le mot de passe devra faire au minimum 8 caractères
 *    BONUS : Le mot de passe doit contenir au minimum un chiffre et un caractère spécial
 * 3/ On ajoute l'utilisateur dans la BDD avec les données du formulaire.
 */

// Inclure le header
require __DIR__ . '/../partials/header.php';

// Traitement du formulaire d'inscription
$email = $_POST['email'] ?? null; // On vérifie s'il y a un email dans $_POST sinon on ne met rien.
$username = htmlspecialchars($_POST['username'] ?? null);

if (!empty($_POST)) { // Si le formulaire a été saisi
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $errors = [];

    // Ici, on doit faire une requête SQL pour vérifier si le pseudo ou l'email n'est pas déjà utilisé...
    $query = $db->prepare('SELECT * FROM user WHERE email = :email OR username = :username');
    $query->bindValue(':email', $email);
    $query->bindValue(':username', $username);
    $query->execute();
    $user = $query->fetch(); // Renvoie un tableau ou false

    if ($user) { // Si l'utilisateur est déjà présent dans la BDD.
        $errors['user'] = 'Email ou pseudo déjà pris';
    }

    // On vérifie les données du formulaire
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'email n\'est pas valide';
    }

    if (empty($username)) {
        $errors['username'] = 'Le pseudo est obligatoire';
    }

    // Vérification de la force du mot de passe
    if (strlen($password) < 8) {
        $errors['password'] = 'Le mot de passe doit faire au minimum 8 caractères';
    }

    // ^0[1-9]([.\- ]?[0-9]{2}){4}$ -> Vérifier un téléphone
    // [^a-zA-Z0-9]+ -> Vérifier qu'une chaine contient un caractère spécial
    // [0-9]+ -> Vérifier qu'une chaine contient un nombre au moins une fois

    if (!preg_match('/[^a-zA-Z0-9]+/', $password)) {
        $errors['password'] = 'Le mot de passe doit contenir un caractère spécial';
    }

    if (!preg_match('/[0-9]+/', $password)) {
        $errors['password'] = 'Le mot de passe doit contenir un chiffre';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm-password'] = 'Les mots de passe doivent correspondre';
    }

    // On peut faire la requête SQL
    if (empty($errors)) {
        // Ajouter l'utilisateur en BDD

        // Sans le prepare
        // 'INSERT INTO user (email) VALUES("matthieumota@gmail.com"); DROP DATABASE movies; ")';
        // Avec le prepare
        // 'INSERT INTO user (email) VALUES("matthieumota@gmail.com\"); DROP DATABASE movies;")';

        $query = $db->prepare('INSERT INTO user(`email`, `username`, `password`) VALUES(:email, :username, :password)');

        $query->bindValue(':email', $email);
        $query->bindValue(':username', $username);
        $query->bindValue(':password', password_hash($password, PASSWORD_DEFAULT)); // On stocke le hash dans la BDD.

        $query->execute();
        echo '<div class="container alert alert-success">Vous êtes inscrit sur le site.</div>';

        // Mail de bienvenue
        $headers = "From: schricke.louis@gmail.com\r\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
        mail($email, 'Inscription', "Bonjour $username, votre mot de passe est $password", $headers);
    } else {
        echo '<div class="container alert alert-danger">';
        foreach ($errors as $error) {
            echo '<p class="text-danger m-0">'.$error.'</p>';
        }
        echo '</div>';
    }
}

?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
                </div>
                <div class="form-group">
                    <label for="username">Pseudo</label>
                    <input type="username" name="username" id="username" class="form-control" value="<?= $username; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmer le mot de passe</label>
                    <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                </div>

                <button class="btn btn-danger btn-block">S'inscrire</button>
            </form>
        </div>
    </div>
</div>

<?php
// Inclure le footer
require __DIR__ . '/../partials/footer.php';