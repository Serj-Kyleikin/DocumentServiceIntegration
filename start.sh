#!/bin/bash

composer create-project --prefer-dist laravel/laravel
sudo chmod -R 777 laravel
cd laravel

sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=root/g' .env
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=work/g' .env
sed -i 's/...protected..n/protected $n/g' app/Providers/RouteServiceProvider.php

cd ..
sudo cp -rpt laravel project/.

docker-compose up -d --build
docker exec -it app bash -c "composer update && php artisan migrate --seed"

echo "Done! Good luck.."
