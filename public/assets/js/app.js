console.log('It works !');

$(document).ready(function () {
var stripe = Stripe('pk_test_F97nQwk8FSuVEw1fPs2pI1JG00JHR56dqd');
var elements = stripe.elements();

// Je crée un formulaire de CB
var card = elements.create('card', {
    style: {
        base: {
            lineHeight: 1.75,
        }
    }
});
card.mount('#card-element');

// Affichage des erreurs de la CB
card.addEventListener('change', ({error}) => {
    const displayError = document.getElementById('card-errors');
    if (error) {
      displayError.textContent = error.message;
    } else {
      displayError.textContent = '';
      $('#card-errors').hide();
    }
  });

  var form = document.getElementById('payment-form');

  form.addEventListener('submit', function (event) {
      // On annule le formulaire
      event.preventDefault();

      // On envoie le client secret et la CB à la stripe
      stripe.confirmCardPayment(form.getAttribute('data-pi'), {
        payment_method:{ card: card }
      }).then(function (result) {

        console.log(result);

        //Si le paiement a réussi
        if (result.paymentIntent.status === 'succeeded') {
            // On redirige
            window.location = './account.php?stripe_id='+result.paymentIntent.id;
        }
      });
  });

});