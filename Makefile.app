init-app:
	mkdir -p var

init-db:
	docker-compose exec -T app php bin/console doctrine:database:create
	docker-compose exec -T app php bin/console doctrine:schema:create -f

db-shell:
	docker exec -it gogo_db_1 mysql -u $(DB_USER) -p

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

node:
	docker-compose run node bash

js-routes:
	docker-compose exec -T app bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

run-shell:
	docker run -v /Users/malu/proj/gogo:/srv/symfony -it malulu/php-7.1:latest sh