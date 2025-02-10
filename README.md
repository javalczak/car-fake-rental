### Fake Car Rental

**bardzo ważne - musicie mieć zainstalowanego node, ja używam: v22.12.0**

`$ git clone git@github.com:javalczak/car-fake-rental.git`  
`$ cd car-fake-rental`  
`$ docker-compose up -d --build`  
`$ docker exec -it car-rental-php /bin/bash`  
`$ composer install`  
`$ php bin/console d:s:u--force`  
`$ php bin/console first:run / zaznacz yes`  
`$ exit`  
`$ cd app`  
`$ npm install`  
`$ npm run build`  


strona główna projektu:   
http://localhost:1102

panel administracyjny:  
http://localhost:1102/admin/

dokumentacje:   
http://localhost:1102/api/doc

Panel oraz API zostały wykoanne w Symfony 7 przy użyciu php 8.3 oraz z bazą danych MySql
