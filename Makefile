.PHONY dev:
dev:
	docker-compose up --build

.PHONY prod:
prod:
	docker-compose -f docker-compose.yml -f docker-compose-production.yml up --build

.PHONY test:
test:
	docker-compose exec app sh -c 'APP_ENV='test' && ./vendor/bin/simple-phpunit'