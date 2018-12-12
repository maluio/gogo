#! /bin/bash

# -n option: don't overwrite existing file
cp -n .env.dist .env

mkdir -p var

make run
docker-compose exec -T app php bin/console doctrine:database:create
docker-compose exec -T app php bin/console doctrine:schema:create