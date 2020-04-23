<?php

// Connexion utilisateur
require_once __DIR__ . '/../partials/header.php';

// Traitement du login
$email = $_POST['email'] ?? null;

if (!empty($_POST)) {
    $password = $_POST['password']; // Le mot de passe saisi

    $query = $db->prepare(
        'SELECT u.id, u.username, u.email, u.password, u.subscription_id, s.ends_at
        FROM user u LEFT JOIN subscription s ON u.subscription_id = s.id WHERE email = :email'
    );
    $query->bindValue(':email', $email);
    $query->execute();
    // On récupère l'utilisateur qui se connecte
    $user = $query->fetch();

    // Si l'utilisateur existe, on va vérifier que le mot de passe est correct
    if ($user) {
        // On vérifie le mot de passe saisi et le hash de la BDD
        if (password_verify($password, $user['password'])) {
            // On met l'utilisateur dans la session
            $_SESSION['user'] = $user;
            // On va rediriger l'utilisateur vers l'accueil
            header('Location: index.php');
            exit;
            echo 'ON SE CONNECTE';
        } else {
            echo 'MOT DE PASSE INCORRECT';
        }
    }

}

?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <form action="" method="post">
                <div class="form-group"> 
                    <label for="email">Email ou pseudo</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?= $email; ?>">
                </div>
                <div class="form-group"> 
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <button class="btn btn-danger btn-block">Se connecter</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../partials/footer.php';