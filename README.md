# Cuir et Crinière

Bienvenue sur cette documentation concernant le projet **"Cuir et Crinière"** de developpement back du BUT MMI de Saint-Lô.

Vous trouverez dans ce document toutes les information nécessaires afin de pouvoir y accéder et démarrer le projet.

## Récupération du projet

Vous pouvez récupérer le dépôt Git du projet en utilisant cette commande dans un terminal :

`> git clone https://github.com/GabHurlant/beatbank.git`

## initialisation du projet

Dans un premier temps, créez un fichier `.env.local`  et copiez-collez-y le contenu du fichier `.env`.

## MakeFile & Commandes disponibles

### `make ou make help`

Affiche la liste des commandes disponibles.

### `make project`

Exécute toutes les commandes nécessaires pour démarrer le projet :

1. `make install`
2. `make cache-clear`
3. `make db-create`
4. `make db-migrate`
5. `make db-fixtures`
6. `make create-user`
7. `make start`

### `make install`

Installe les dépendances du projet via Composer.

### `make start`

Démarre le serveur Symfony en arrière-plan.

* Arrête d'abord le serveur s'il est déjà en cours d'exécution.

### `make stop`

Arrête le serveur Symfony.

### `make cache-clear`

Vide le cache de Symfony pour l'environnement spécifié.

### `make db-create`

Crée la base de données pour l'environnement spécifié.

### `make db-migrate`

Exécute les migrations de la base de données pour l'environnement spécifié.

### `make db-fixtures`

Charge les fixtures dans la base de données pour l'environnement spécifié.

### `make create-user`

Crée un nouvel utilisateur en utilisant la commande `app:add-user`.


### `make db-reset`

Réinitialise la base de données :

1. Supprime la base de données existante.
2. Crée une nouvelle base de données.
3. Exécute les migrations.
4. Charge les fixtures.

## Ordre d'utilisation des commandes

Afin de démarrer le projet sans encombres voici l'odre d'utilisation des commandes :

**Si vous voulez démarrer l'enssemble du projet en une fois utilisez la commande :**

`make project`


pour démarrer le projet étape par étape suivez cet ordre :

1. `make install` : Afin d'installer les dépendances via composer
2. `make cache-clear`: Afin de vider le cache de symfony
3. `make db-create`: Afin de démarrer créer la base de donnée SQLite
4. `make db-migrate:` Afin d'éxécuter les migration
5. `make db-fixtures`: Afin de charger la base de donnée avec des fixtures
6. `make create-user`: Pour vous créer un utilisateur (il peut être Administrateur ou non)
7. `make start`: Pour démarrer le serveur symfony et accéder au projet
