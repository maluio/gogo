#!/usr/bin/env bash

rsync -av root@staging.malukas.de:/var/www/gogo/composer.json ../
rsync -av root@staging.malukas.de:/var/www/gogo/composer.lock ../
rsync -av root@staging.malukas.de:/var/www/gogo/symfony.lock ../
rsync -av root@staging.malukas.de:/var/www/gogo/yarn.lock ../
rsync -av root@staging.malukas.de:/var/www/gogo/package.json ../
rsync -av root@staging.malukas.de:/var/www/gogo/config ../
rsync -av root@staging.malukas.de:/var/www/gogo/vendor ../