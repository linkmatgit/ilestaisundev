sy := php bin/console
dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
drtest := $(dc) -f docker-compose.test.yml run --rm
de := docker-compose exec
dr := $(dc) run --rm
sy := $(de) php bin/console
drtest := $(dc) -f docker-compose.test.yml run --rm
node := $(dr) node
php := $(dr) --no-deps php


.PHONY: lint
lint: vendor/autoload.php ## Analyse le code
	docker run -v $(PWD):/app -w /app -t --rm php:8.0-cli-alpine php -d memory_limit=-1 bin/console lint:container
	docker run -v $(PWD):/app -w /app -t --rm php:8.0-cli-alpine php -d memory_limit=-1 ./vendor/bin/phpstan analyse
tt:
	$(sy) cache:clear --env=test
	vendor/bin/phpunit-watcher watch --filter="nothing"

.PHONY: test
test: vendor/autoload.php ## Execute les tests
	$(drtest) phptest bin/console doctrine:schema:validate --skip-sync
	$(drtest) phptest vendor/bin/phpunit
cov:
	$(sy) cache:clear --env=test
	$(sy) doctrine:schema:validate --skip-sync
	vendor/bin/phpunit --coverage-html

cache:
	$(sy) cache:clear

.PHONY: seed
seed: vendor/autoload.php ## Génère des données dans la base de données (docker-compose up doit être lancé)
	$(sy) doctrine:migrations:migrate -q
	$(sy) app:seed -q

.PHONY: migration
migration: vendor/autoload.php ## Génère les migrations
	$(sy) make:migration

.PHONY: migrate
migrate: vendor/autoload.php ## Migre la base de données (docker-compose up doit être lancé)
	$(sy) doctrine:migrations:migrate -q

.PHONY: rollback
rollback:
	$(sy) doctrine:migration:migrate prev

.PHONY: format
format: ## Formate le code
	npx prettier-standard --lint --changed "assets/**/*.{js,css,jsx}"
	php vendor/bin/phpcbf
	php vendor/bin/php-cs-fixer fix

serve:
	php -S localhost:8000 -t public

watch:
	yarn dev
build:
	$(MAKE) prepare-test
	$(MAKE) analyze
	$(MAKE) tests
prepare-test:
	npm install
	composer install --prefer-dist
	php bin/console cache:clear --env=test
analyze:
	yarn audit
	composer valid
	php -d memory_limit=-1 ./vendor/bin/phpstan analyse

.PHONY: tests
tests:
	php bin/phpunit

.PHONY: build-docker
build-docker:
	$(dc) pull --ignore-pull-failures
	$(dc) build php
	$(dc) build node

.PHONY: dev
dev: vendor/autoload.php node_modules/time ## Lance le serveur de développement
	$(dc) up
# -----------------------------------
# Dépendances
# -----------------------------------
vendor/autoload.php: composer.lock
	$(php) composer install
	touch vendor/autoload.php

node_modules/time: yarn.lock
	$(node) yarn
	touch node_modules/time

public/assets: node_modules/time
	$(node) yarn run build

var/dump:
	mkdir var/dump

public/assets/manifest.json: package.json
	$(node) yarn
	$(node) yarn run build