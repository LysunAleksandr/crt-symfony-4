# crt-symfony-4
crt-symfony-4

docker-compose up --build -d
docker-compose exec app composer install
docker-compose exec app  bin/console doctrine:migrations:migrate