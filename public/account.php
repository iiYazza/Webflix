<?php

// Inclure le header

use Stripe\PaymentIntent;

require __DIR__ . '/../partials/header.php';

// On récupère l'utilisateur connecté
$user = $_SESSION['user'];

// On va faire le PaymentIntent
require_once __DIR__.'/../vendor/autoload.php';

// On configure Stripe
\Stripe\Stripe::setApiKey('sk_test_oVCtBcho5lERfuAKA57WUg5E');

// On vérifie si un paiement a eu lieu
if (isset($_GET['stripe_id'])) {
    $stripeId = $_GET['stripe_id'];
    try {
        $pi = PaymentIntent::retrieve($stripeId); // On vérifie que le paiement a bien eu lieu
        if ($pi->status !== 'succeeded') {
            header('Location: account.php'); // On redirige si le paiement n'est pas bon
            exit;
        }
        $exists = $db->query("SELECT * FROM subscription WHERE stripe_id = '$stripeId'")->fetch();
        if ($exists) {
            header('Location: account.php'); // L'utilisateur veut tricher en réactualisant la page
            exit;
        }
    } catch (Exception $e) {
        // die('Interdit');
        header('Location: account.php'); // Si l'utilisateur tape n'importe quoi dans ?stripe_id=
        exit;
    }

    // Ici 2 requête SQL pour ajouter l'abonnement
    // et attacher l'abonnement à l'utilisateur
    // @todo Si l'utilisateur a déjà un abonnement, on fait juste un UPDATE
    // et on ajoute 30 jours au ends_at
    $query = $db->prepare('INSERT INTO subscription (stripe_id, status, ends_at) VALUES (:stripe_id, :status, :ends_at)');
    $query->bindValue(':stripe_id', $stripeId);
    $query->bindValue(':status', 'succeeded');
    $query->bindValue(':ends_at', date('Y-m-d', strtotime('+30 days')));
    $query->execute();

    // Attacher l'abonnement à l'utilisateur connecté
    // lastInsertId() nous renvoie l'id de la subscription qui vient d'être ajoutée
    $lastId = $db->lastInsertId();
    $db->query('UPDATE user SET subscription_id = '.$lastId.' WHERE id = '.$user['id']);

    // On mets à jour la session pour que l'utilisateur n'ait pas besoin de se déconnecter quand il s'abonne
    $_SESSION['user']['subscription_id'] = $lastId;
    $_SESSION['user']['ends_at'] = date('Y-m-d', strtotime('+30 days'));
    $user = $_SESSION['user'];
}

$intent = \Stripe\PaymentIntent::create([
    'amount' => 9999,
    'currency' => 'eur',
]);

// Le client secret est l'identifiant du paiement
$clientSecret = $intent->client_secret;

?>

<div class="container">
    <h1>Bienvenue <?= $user['username']; ?></h1>

    <?php
    // Si on est administrateur, on peut ajouter un film
    if (isAdmin()) { ?>
        <a href="movie_add.php">Ajouter un film</a>
    <?php }

    // Si l'utilisateur a un abonnement, on affiche le nombre de jours restants
    if ($user['subscription_id'] !== null) { ?>
        <div class="alert alert-success">
            Votre abonnement expire le <?= $user['ends_at']; ?>
        </div>
    <?php } ?>

    <form id="payment-form" data-pi="<?= $clientSecret; ?>">
        <div id="card-element" class="form-control mb-4">
            <!-- Elements will create input elements here -->
        </div>

        <!-- We'll put the error messages in this element -->
        <div id="card-errors" class="mb-4 alert alert-danger" style="display: none"></div>

        <button class="btn btn-danger" id="submit">S'abonner</button>
    </form>
</div>

<?php
// Inclure le footer
require __DIR__ . '/../partials/footer.php';