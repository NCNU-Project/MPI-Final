#!/bin/sh

# build the laravel container from docker compose
docker compose build
echo "Build complete, laravel container's name is: app/efficacy38"

# build the taiwind css from laravel container
docker compose up -d
docker compose exec laravel.test npm run build
docker compose down 

echo "build done, you can run the container with: docker compose up -d"

