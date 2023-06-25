#!/bin/sh

# Stop the application
docker compose down 

# setup the config
cp .env.example .env

# build the test image
docker build -t test ./docker/MpiContainer
# change the docker socket permission
sudo chown root:1000 /var/run/docker.sock

# install php dependencies
docker run -v .:/var/www/html -w /var/www/html -e WWW_GROUP=1000 -it laravelsail/php82-composer:latest composer install

# start the app
docker compose up -d
docker compose exec -u sail -it laravel.test npm install
docker compose exec -u sail -it laravel.test npm run build

# FIXME: this is the dirty way to wait for db start up, may use some kind of method
# to check if db is ready. 
# sleep 5 s for db start up
sleep 10
docker compose exec -u sail -it laravel.test php artisan migrate --seed

