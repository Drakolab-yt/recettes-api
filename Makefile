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

.PHONY: start start.daemon stop restart update check csfix help

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
update: composer.install migrate ## Met à jour le projet

composer.install:
	$(EXEC) composer install

entity: ## Génère ou modifie une entité
	$(EXEC) $(CONSOLE) make:entity

migration: ## Crée une migration à partir des entités et de leurs modifications
	$(EXEC) $(CONSOLE) make:migration

migrate: ## Applique les migrations
	$(EXEC) $(CONSOLE) doctrine:migrations:migrate -n


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