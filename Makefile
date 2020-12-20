#!/usr/bin/make

user_id := $(shell id -u)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null) --file "Docker/docker-compose.yml"
php_container_bin := $(docker_compose_bin) run --rm -u "$(user_id)" "php"

.PHONY : help build install shell fixer test coverage entites
.DEFAULT_GOAL := build

# --- [ Development tasks ] -------------------------------------------------------------------------------------------

build: ## Build container and install composer libs
	$(docker_compose_bin) build --force-rm

install: ## Install all data
	$(php_container_bin) composer update

shell: ## Runs shell in container
	$(php_container_bin) bash

fixer: ## Run fixer to fix code style
	$(php_container_bin) vendor/bin/php-cs-fixer fix -v

linter: ## Run linter to check project
	$(php_container_bin) vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run --stop-on-violation
	$(php_container_bin) vendor/bin/phpcpd ./ --exclude vendor --exclude Tests --exclude Entity
	$(php_container_bin) vendor/bin/psalm --show-info=true

test: ## Run tests
	$(php_container_bin) vendor/bin/phpunit --configuration phpunit.xml.dist

coverage: ## Run tests with coverage
	$(php_container_bin) vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-html=Tests/coverage

entites: ## Build entities from yaml file with description
	$(php_container_bin) php -f Resources/build/generate_entities.php
	$(php_container_bin) vendor/bin/php-cs-fixer fix -q
