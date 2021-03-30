sy := php bin/console
.PHONY: analyze
lint:
	php vendor/bin/phpstan analyse

lintContainer:
	$(sy) lint:container
tt:
	$(sy) cache:clear --env=test
	vendor/bin/phpunit-watcher watch --filter="nothing"

test:
	php vendor/bin/phpunit
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
	./vendor/bin/phpcbf
	./vendor/bin/php-cs-fixer fix

serve:
	php -S localhost:8000 -t public

watch:
	yarn dev

lintci:
	docker run -v $(PWD):/app -w /app --rm php:7.4-cli-alpine php -d memory_limit=-1 ./vendor/bin/phpstan analyse


