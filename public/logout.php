<?php

session_start();
unset($_SESSION['user']); // On déconnecte l'utilisateur
header('Location: index.php');