FIG=docker-compose

# Dans la ligne de commande de notre machine, on vérifie si docker-compose est disponible
HAS_DOCKER:=$(shell command -v $(FIG) 2> /dev/null)
# Si c'est le cas, EXEC et EXEC_DB vont permettre d'exécuter des commandes dans les conteneurs
ifdef HAS_DOCKER
	EXEC=$(FIG) exec app
	EXEC_DB=$(FIG) exec db
# Sinon, on exécute les commandes sur la machine locale
else
	EXEC=
	EXEC_DB=
endif

# Ligne de commande du projet Symfony
CONSOLE=php bin/console

# Source pour la documentation du Makefile : http://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
# Définit une tâche par défaut (quand on appelle make sans sous-commande)
.DEFAULT_GOAL := help

.PHONY: start start.daemon stop restart update check csfix help upgrade entity migration migrate db.restart db.drop db.create

# =========================== #
# Manipulation des conteneurs #
# =========================== #
start: ## Démarrage des conteneurs et affiche les logs en temps réel
	docker-compose up

start.daemon: ## Démarrage des conteneurs et rend la ligne de commande
	docker-compose up -d

stop: ## Arrête les conteneurs
	docker-compose down

restart: stop start.daemon ## Arrête et redémarre les conteneurs

# ===================== #
# Mise à jour du projet #
# ===================== #
update: ## Met à jour le projet avec les informations de composer.lock (ne les met pas à jour)
	$(EXEC) composer install

upgrade: ## Met à jour le projet avec les informations de composer.json (met à jour le composer.lock)
	$(EXEC) composer update

# ================================== #
# Manipulation de la base de données #
# ================================== #
entity: ## Crée ou modifie une entité
	$(EXEC) $(CONSOLE) make:entity

migration: ## Génère une migration avec les changements des entités
	$(EXEC) $(CONSOLE) make:migration

migrate: ## Exécute les migrations
	$(EXEC) $(CONSOLE) doctrine:migrations:migrate -n

migrations.list: ## Liste les migrations
	$(EXEC) $(CONSOLE) doctrine:migrations:list

db.recreate: db.drop db.create migrate fixtures ## Commande (d'urgence) pour recréer la BdD depuis 0

db.drop:
	$(EXEC) $(CONSOLE) doctrine:database:drop -f

db.create:
	$(EXEC) $(CONSOLE) doctrine:database:create

fixtures: ## Charger les fixtures (Attention, vide la BdD !)
	$(EXEC) $(CONSOLE) doctrine:fixtures:load -n

# ========================= #
# Génération de formulaires #
# ========================= #

form: ## Crée un formulaire Symfony
	$(EXEC) $(CONSOLE) make:form

admin.crud: ## Crée un CRUD pour l'admin (EasyAdmin)
	$(EXEC) $(CONSOLE) make:admin:crud

# ============= #
# Vérifications #
# ============= #
check: ## Vérification de la qualité et de la cohérence du code
	$(EXEC) composer check
	$(EXEC) $(CONSOLE) lint:container
	$(EXEC) $(CONSOLE) lint:yaml config
	$(EXEC) $(CONSOLE) lint:twig templates
	# $(EXEC) $(CONSOLE) lint:yaml translations

csfix: ## Correction (automatique) de la qualité du code
	$(EXEC) composer fix

# ============= #
# Documentation #
# ============= #

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-9s\033[0m %s\n", $$1, $$2}'
