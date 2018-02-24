.PHONY dev:
dev:
	docker-compose up --build

.PHONY prod:
prod:
	docker-compose run node yarn run encore production
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up --build

.PHONY test:
test:
	docker-compose run app sh -c " APP_ENV='test' && DATABASE_URL=sqlite:///%kernel.project_dir%/var/test.db && php bin/console doctrine:schema:update --force && ./vendor/bin/simple-phpunit"

.PHONY migrations:
migrations:
	docker-compose run app php bin/console doctrine:migrations:migrate

.PHONY db:
db:
	docker-compose run app sqlite3 var/data.db

.PHONY shell:
shell:
	docker-compose run app sh

.PHONY webpack:
webpack:
	docker-compose run node yarn run encore dev

.PHONY watch:
watch:
	docker-compose run node yarn run encore dev --watch