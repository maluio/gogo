dev:
	docker-compose up

prod: yarn
	docker-compose down
	docker-compose run node yarn run encore production
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up -d --build
	docker-compose exec app composer install --no-dev --optimize-autoloader
	docker-compose exec app bin/console cache:clear
	docker-compse logs -f

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

app:
	docker-compose exec app sh

encore:
	docker-compose run node yarn run encore dev

watch:
	docker-compose run node yarn run encore dev --watch

yarn:
	docker-compose run node yarn install

node:
	docker-compose run node bash