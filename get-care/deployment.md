deployment


docker compose build 
docker compose up

docker exec getcare_app php artisan migrate
docker exec getcare_app php artisan db:seed