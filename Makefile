dev: down up logs

prod: down prod-up prod-assets reverse-proxy-up permissions logs

prod-up:
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up -d --build
	docker-compose exec app composer install --no-dev --optimize-autoloader
	docker-compose exec app bin/console cache:clear

prod-assets: yarn-install yarn-prod js-routes

reverse-proxy-down:
	cd ../reverse-proxy/ && docker-compose down && cd -

reverse-proxy-up:
	cd ../reverse-proxy/ && docker-compose up -d && cd -

up: docker-up reverse-proxy-up permissions

down: reverse-proxy-down docker-down

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

permissions:
	docker-compose exec app chown -R www-data var

logs:
	docker-compose logs -f

test:
	docker-compose run -e APP_ENV=test -e DATABASE_URL=sqlite:///%kernel.project_dir%/var/test.db app sh -c "php bin/console doctrine:schema:update --force && ./vendor/bin/simple-phpunit"

composer:
	docker-compose exec app composer install

migrate:
	docker-compose exec app php bin/console doctrine:migrations:migrate

fixtures:
	docker-compose exec app php bin/console doctrine:fixtures:load -n

db:
	docker-compose run app sqlite3 var/data.db

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

travis: docker-up composer yarn-install encore test

js-routes:
	docker-compose run app bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json