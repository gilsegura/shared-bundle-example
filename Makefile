COMPOSE=docker compose -f ./docker-compose.yaml
TARGETS := $(shell grep -E '^[a-zA-Z0-9 -]+:' $(MAKEFILE_LIST) | sort | awk -F ':.*' '{print $$1}')
ARGS = $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS))

.DEFAULT_GOAL := help

.PHONY: composer
composer: ## composer
	$(COMPOSE) run --build --rm php-cli sh -lc "composer $(ARGS)"

.PHONY: coding-standards
coding-standards: ## phpstan
	$(COMPOSE) run --build --rm php-cli sh -lc "./vendor/bin/phpstan analyse -l 9 src tests"

.PHONY: static-analysis
static-analysis:rector cs ## rector and cs

.PHONY: tests
tests:db ## phpunit
	$(COMPOSE) run --build --rm php-cli sh -lc "XDEBUG_MODE=coverage ./vendor/bin/phpunit $(ARGS)"

.PHONY: rector
rector: ## rector
	$(COMPOSE) run --build --rm php-cli sh -lc "./vendor/bin/rector process src tests"

.PHONY: cs
cs: ## cs
	$(COMPOSE) run --build --rm php-cli sh -lc "./vendor/bin/php-cs-fixer fix --no-interaction --allow-risky=yes --diff --verbose"

.PHONY: start
start:erase start-deps up db ## erase and recreate environment

.PHONY: erase
erase:  ## erase environment
	$(COMPOSE) down --remove-orphans -v --rmi local

.PHONY: start-deps
start-deps:  ## start dependencies
	$(COMPOSE) run --rm start_dependencies

.PHONY: up
up:  ## up environment
	$(COMPOSE) up --build nginx -d

.PHONY: db
db:  ## db
	$(COMPOSE) exec -T php-fpm sh -lc "./bin/console d:d:d --force --if-exists"
	$(COMPOSE) exec -T php-fpm sh -lc "./bin/console d:d:c --if-not-exists"
	$(COMPOSE) exec -T php-fpm sh -lc "./bin/console d:m:m -n"

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

%:
	@if [ -z "$(filter $(firstword $(MAKECMDGOALS)),$(TARGETS))" ]; then \
		echo "Help: Unknown command '$(firstword $(MAKECMDGOALS))'"; \
		$(MAKE) --no-print-directory help; \
		exit 1; \
	fi