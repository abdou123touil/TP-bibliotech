# 📚 Bibliotech

**Bibliotech** est une application web de gestion de médiathèque développée avec **Symfony 7.4**. Elle permet de consulter un catalogue de livres, de suivre les emprunts et de retrouver les informations des adhérents.

## MCD :
<img width="861" height="532" alt="image" src="https://github.com/user-attachments/assets/2191cebf-98f0-454d-8185-4c88555a42bc" />


## MLD :
<img width="861" height="753" alt="image" src="https://github.com/user-attachments/assets/06e8c3ef-ba69-4d68-ba00-1e19f365c746" />



##  Fonctionnalités

### 1. Catalogue des livres
- Affichage des livres enregistrés en base de données.
- Affichage des couvertures, titres, auteurs, genres et années de parution.
- Nombre total de livres dans le catalogue.
- Affichage du nombre d'exemplaires disponibles.
- Filtrage par genre et par auteur.
- Tri des livres par titre, année de parution ou auteur.
- Réinitialisation des filtres.

### 2. Fiche détaillée d'un livre
- Consultation des informations complètes d'un livre.
- Affichage du résumé et du nombre d'exemplaires.
- Calcul du nombre total d'emprunts.
- Affichage de la disponibilité des exemplaires.
- Historique des derniers emprunts.
- Accès aux informations des adhérents.
- Affichage d'autres livres appartenant au même genre.

### 3. Les livres les plus empruntés
- Affichage des cinq livres les plus empruntés.
- Classement basé sur le nombre total d'emprunts.
- Réutilisation des cartes du catalogue.

### 4. Fiche d'un adhérent
- Consultation des informations d'un adhérent.
- Affichage de son historique d'emprunts.
- Accès aux fiches des livres empruntés.
- Distinction entre les emprunts en cours et les emprunts rendus.

### 5. Gestion des données
- Entités Doctrine : Livre, Adherent et Emprunt.
- Relations entre les livres, les adhérents et les emprunts.
- Chargement de données de démonstration avec les fixtures.

## 🛠️ Technologies utilisées

| Technologie | Utilisation |
|---|---|
| PHP | Langage de programmation |
| Symfony 7.4 | Framework PHP |
| Doctrine ORM | Gestion de la base de données |
| MySQL | Système de gestion de base de données |
| Twig | Moteur de templates |
| HTML5 | Structure des pages |
| CSS3 | Mise en forme |
| Faker | Génération de données fictives |
| Symfony Fixtures | Chargement des données de test |

##  Structure du projet

```text
bibliotech/
├── assets/
│   └── styles/
│       └── app.css
├── migrations/
├── public/
├── src/
│   ├── Controller/
│   │   ├── CatalogueController.php
│   │   ├── LivreController.php
│   │   ├── PopulairesController.php
│   │   └── AdherentController.php
│   ├── DataFixtures/
│   │   └── AppFixtures.php
│   ├── Entity/
│   │   ├── Livre.php
│   │   ├── Adherent.php
│   │   └── Emprunt.php
│   └── Repository/
├── templates/
│   ├── base.html.twig
│   ├── catalogue/
│   │   └── index.html.twig
│   ├── livre/
│   │   ├── show.html.twig
│   │   └── _carte.html.twig
│   ├── populaires/
│   │   └── index.html.twig
│   └── adherent/
│       └── show.html.twig
├── .env
├── .env.local
├── composer.json
└── README.md
```




##  Installation

### Prérequis

Avant de commencer, installer :

- PHP compatible avec Symfony 7.4.
- Composer.
- Symfony CLI.
- MySQL.
- Un navigateur web.

### 1. Récupérer le projet

```bash
git clone URL_DU_DEPOT
cd bibliotech
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer la base de données

Créer un fichier `.env.local` à la racine du projet et configurer la connexion MySQL :

```dotenv
DATABASE_URL="mysql://UTILISATEUR:MOT_DE_PASSE@127.0.0.1:3306/bibliotech?serverVersion=8.0&charset=utf8mb4"
```

Remplacer `UTILISATEUR` et `MOT_DE_PASSE` par les identifiants de votre serveur MySQL.

Adapter également `serverVersion` à la version de MySQL installée.

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create
```

### 5. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

### 6. Charger les données de démonstration

```bash
php bin/console doctrine:fixtures:load
```

**Attention :** cette commande peut supprimer les données existantes avant de charger les fixtures. Ne pas l'utiliser sur une base contenant des données à conserver.

### 7. Lancer le serveur Symfony

```bash
symfony server:start
```

Ouvrir ensuite l'application dans le navigateur :

```text
https://127.0.0.1:8000
```

##  Routes principales

| Route | URL | Description |
|---|---|---|
| `app_catalogue` | `/` | Catalogue des livres |
| `app_livre` | `/livre/{id}` | Fiche détaillée d'un livre |
| `app_populaires` | `/populaires` | Les cinq livres les plus empruntés |
| `app_adherent` | `/adherent/{id}` | Fiche et historique d'un adhérent |

## 🗄️ Modèle de données

L'application repose sur trois entités principales :

- **Livre** : contient le titre, l'auteur, le genre, l'année de parution, le résumé, la couverture et le nombre d'exemplaires.
- **Adherent** : contient le nom, la ville et l'adresse e-mail de l'adhérent.
- **Emprunt** : associe un livre à un adhérent et enregistre la date d'emprunt ainsi que la date de retour.

### Relations entre les entités

- Un livre peut avoir plusieurs emprunts.
- Un adhérent peut effectuer plusieurs emprunts.
- Chaque emprunt concerne un seul livre et un seul adhérent.
- Une date de retour vide (`NULL`) indique un emprunt en cours.

##  Améliorations possibles

- Ajouter une véritable fonctionnalité de réservation.
- Ajouter des formulaires pour gérer les livres et les adhérents.
- Permettre l'enregistrement et le retour des emprunts.
- Ajouter un système d'authentification et de gestion des rôles.
- Ajouter une pagination au catalogue.
- Ajouter des statistiques sur les emprunts.








