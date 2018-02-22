.PHONY dev:
dev:
	docker-compose up --build

.PHONY prod:
prod:
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up --build

.PHONY test:
test:
	docker-compose run app sh -c " APP_ENV='test' && DATABASE_URL=sqlite:///%kernel.project_dir%/var/test.db && php bin/console doctrine:schema:update --force && ./vendor/bin/simple-phpunit"