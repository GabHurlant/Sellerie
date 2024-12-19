# Variables
PHP = php
COMPOSER = composer
CONSOLE = $(PHP) bin/console
SYMFONY = symfony
ENV = dev

# Règle par défaut
.PHONY: help
help:
	@echo "Commandes disponibles :"
	@echo "  make install         - Installer les dépendances (Composer)"
	@echo "  make start           - Démarrer le serveur Symfony"
	@echo "  make stop            - Arrêter le serveur Symfony"
	@echo "  make cache-clear     - Vider le cache Symfony"
	@echo "  make db-create       - Créer la base de données"
	@echo "  make db-migrate      - Exécuter les migrations"
	@echo "  make db-fixtures     - Charger les fixtures"
	@echo "  make db-reset        - Réinitialiser la base de données"

# Installation des dépendances
.PHONY: install
install:
	$(COMPOSER) install

# Démarrage du serveur Symfony
.PHONY: start
start:
	@echo "Starting Symfony server..."
    $(SYMFONY) server:stop || true
    $(SYMFONY) server:start -d

# Arrêter le serveur Symfony
.PHONY: stop
stop:
	@echo "Stopping Symfony server..."
    $(SYMFONY) server:stop

# Vider le cache
.PHONY: cache-clear
cache-clear:
	@echo "Clearing Symfony cache..."
    $(CONSOLE) cache:clear --env=$(ENV)

# Création de la base de données
.PHONY: db-create
db-create:
	@echo "Creating database..."
	$(CONSOLE) doctrine:database:create --env=$(ENV)

# Exécuter les migrations
.PHONY: db-migrate
db-migrate:
	@echo "Running database migrations..."
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --env=$(ENV)

# Charger les fixtures
.PHONY: db-fixtures
db-fixtures:
	@echo "Loading database fixtures..."
	$(CONSOLE) doctrine:fixtures:load --no-interaction --env=$(ENV)

# Réinitialiser la base de données
.PHONY: db-reset
db-reset:
	@echo "Resetting the database..."
	$(CONSOLE) doctrine:database:drop --force --if-exists --env=$(ENV)
	$(CONSOLE) doctrine:database:create --env=$(ENV)
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --env=$(ENV)