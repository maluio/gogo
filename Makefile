#! make

include .env
include .env.global
export

dev: down up composer

dev-embedded: down up-embedded composer

prod: down prod-up js-routes prod-assets permissions

prod-quick: down prod-up-without-build permissions

prod-up:
	docker-compose -f docker-compose.yml -f build/docker/docker-compose-production.yml up -d --build
	docker-compose exec -T app composer install --no-dev --optimize-autoloader
	docker-compose exec -T app bin/console cache:clear
	docker-compose exec -T app bin/console doctrine:schema:update --force

prod-assets: yarn-install yarn-prod js-routes

prod-up-without-build:
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up -d

up: docker-up permissions

up-embedded: docker-up-embedded permissions

down: docker-down

docker-up:
	docker-compose -f docker-compose.yml -f build/docker/docker-compose-standalone.yml -f build/docker/docker-compose-db.yml up -d

docker-up-embedded:
	docker-compose -f docker-compose.yml -f build/docker/docker-compose-embedded.yml up -d

docker-down:
	docker-compose down --remove-orphans

permissions:
	docker-compose exec -T app chown -R www-data var

logs:
	docker-compose logs -f

test:
	docker-compose run -e APP_ENV=test -e DATABASE_URL=sqlite:///%kernel.project_dir%/var/test.db app sh -c "php bin/console doctrine:schema:update --force && ./vendor/bin/simple-phpunit"

composer:
	docker-compose exec app composer install

migrate:
	docker-compose exec app php bin/console doctrine:migrations:migrate

fixtures:
	docker-compose exec -T app php bin/console doctrine:fixtures:load -n

shell:
	docker-compose exec app sh

encore:
	docker-compose run node yarn run encore dev

watch:
	docker-compose run node yarn run encore dev --watch

yarn-install:
	docker-compose run node yarn install

yarn-prod:
	docker-compose run node yarn run encore production

node:
	docker-compose run node bash

js-routes:
	docker-compose exec -T app bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

run-shell:
	docker run -v /Users/malu/proj/gogo:/srv/symfony -it malulu/php-7.1:latest sh