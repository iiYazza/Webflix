<?php

// Test envoi de mail
$headers = "From: matthieumota@gmail.com\r\n";
$headers .= "Content-type: text/plain; charset=UTF-8\r\n";
mail('matthieumota@gmail.com', 'Sujet', 'Message', $headers);

// On va envoyer un mail aux inscrits sur le site
// "Bonjour PSEUDO, votre mot de passe est PASSWORD"