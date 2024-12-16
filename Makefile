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
	@echo "  make db-reset        - Réinitialiser la base de données"

# Installation des dépendances
.PHONY: install
install:
	$(COMPOSER) install

# Démarrage du serveur Symfony
.PHONY: start
start:
	$(SYMFONY) server:start -d

# Arrêter le serveur Symfony
.PHONY: stop
stop:
	$(SYMFONY) server:stop

# Vider le cache
.PHONY: cache-clear
cache-clear:
	$(CONSOLE) cache:clear --env=$(ENV)

# Création de la base de données
.PHONY: db-create
db-create:
	$(CONSOLE) doctrine:database:create

# Exécuter les migrations
.PHONY: db-migrate
db-migrate:
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

# Réinitialiser la base de données
.PHONY: db-reset
db-reset:
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(CONSOLE) doctrine:database:create
	$(CONSOLE) doctrine:migrations:migrate --no-interaction