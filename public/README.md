#Webflix PHP SQL

On va créer un clone de Netflix afin d'apprendre à créer un projet en PHP/SQL.

Partie Back :

- `config/database.php` : Contiendra la connexion à la BDD. A inclure dans tous les fichiers.
- `config/config.php` : Contiendra les variables de configurations du projet.
- `config/functions.php` : Contiendra des fonctions utiles pour le projet.
- `partials/header.php` : le header du site à inclure dans toute les pages.
- `partials/footer.php` : Le footer du site à inclure dans toute les pages.
- `public/index.php` : Page d'acueil du site qui affiche 4 films aléatoires de la BDD.
- `public.movie_list.php` : Lister tous les films de la BDD.
- `public/movie_single.php` : La page d'un seul film
- `public/login.php` : Page de connexion

Partie Front : 

- `public/assets` : Dossier qui contient le css, js et les img
- `public/asset/css`
- `public/asset/js`
- `public/asset/img`
- `public/asset/uploads` : Dossier qui contient les images uploadés (Films, avatars)

Base de données :

Voici les tables à créer :

- movie
    - id
    - title
    - description
    - cover
    - duration

- comment 
    - id
    - nickname
    - message
    - note
    - create_at
    - movie_id

- category 
    - id 
    - name

-   actor
    - id
    - lastname
    - firstname
    - birthday

- movie_has_actor
    - movie_id
    - actor_id

- user
    - id
    - email
    - password
    - token
    - requested_at

- subscription 
    - id 
    - user_id
    - stripe_id
    - status
    - ends_at