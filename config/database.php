<?php

/*
 | -------------------------------
 | Connexion à la base de données
 | -------------------------------
 |
 | Connexion entre PHP et MySQL avec PDO.
 |
 */

try {
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_name",
        $db_user,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les erreurs SQL
            // On récupère tous les résultats en associatif
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}