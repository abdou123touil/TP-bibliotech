

SELECT *
FROM livre
WHERE auteur = 'Jules Verne'
ORDER BY annee_parution ASC;



SELECT
    genre,
    COUNT(*) AS nombre_livres
FROM livre
GROUP BY genre
ORDER BY nombre_livres DESC;



SELECT
    e.id AS id_emprunt,
    a.nom AS nom_adherent,
    l.titre AS titre_livre,
    e.date_emp
FROM emprunt e
INNER JOIN adherent a ON e.adherent_id = a.id
INNER JOIN livre l ON e.livre_id = l.id
WHERE e.date_retour IS NULL
ORDER BY e.date_emp DESC;



SELECT
    l.id,
    l.titre,
    COUNT(e.id) AS nombre_emprunts
FROM livre l
INNER JOIN emprunt e ON e.livre_id = l.id
GROUP BY l.id, l.titre
ORDER BY nombre_emprunts DESC
LIMIT 5;



SELECT
    a.id,
    a.nom,
    a.ville,
    a.email
FROM adherent a
LEFT JOIN emprunt e ON e.adherent_id = a.id
WHERE e.id IS NULL
ORDER BY a.nom ASC;
