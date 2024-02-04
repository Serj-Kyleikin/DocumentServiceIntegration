#!/bin/bash

cd laravel

docker exec -it app php artisan route:clear
docker exec -it app php artisan config:clear
docker exec -it app php artisan cache:clear
