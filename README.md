
pizzeria showcase
https://github.com/LysunAleksandr/crt-symfony-4.git

#to install:

docker-compose up --build -d

docker-compose exec app composer install

#to create a database and tables, run the command 

docker-compose exec app  bin/console doctrine:migrations:migrate

#to download data

docker-compose exec app  bin/console --env=dev doctrine:fixtures:load

#login admin

admin/admin

#the site will be available at

http://localhost/

