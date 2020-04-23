-- Ce script permet de remplir la BDD

-- Réinitialise la BDD
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE movie;
TRUNCATE TABLE category;
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO category (`name`) VALUES
('Non classé'),
('Film de gangster'),
('Action'),
('Horreur'),
('Science Fiction'),
('Thriller');

INSERT INTO movie (title, description, duration, released_at, category_id) VALUES
('Le parrain', 'Lorem ipsum', 120, '2002-06-11', 2),
('Scarface', 'Lorem ipsum', 95, '1983-01-01', 2),
('Les affranchis', 'Lorem ipsum', 115, '1990-01-01', 2),
('Heat', 'Lorem ipsum', 140, '1995-01-01', 2),
('Die Hard', 'Lorem ipsum', 80, '1988-01-01', 3),
('Demolition Man', 'Lorem ipsum', 86, '1993-01-01', 3),
('Taken', 'Lorem ipsum', 98, '2008-01-01', 3),
('Vendredi 13', 'Lorem ipsum', 87, '1980-01-01', 4),
('Star Wars IV Un nouvel espoir', 'Lorem ipsum', 123, '1977-01-01', 5),
('Usual Suspects', 'Lorem ipsum', 114, '1995-01-01', 6);