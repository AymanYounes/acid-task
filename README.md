# acid-task
This is a task to Acid corporate with the functionality of fetching products from CSV file and render those products as api requests

it is a laravel project with simple installing steps

Steps to produce

1- clone project to new folder

2- create empty database 

3- copy .env.example and rename it to be .env

4- update .env file replace following variables with your new created database variables
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
5- run command php artisan migrate to implement database tables
6- run command php artisan serv to start your server environment with <<localhost/port>>
7- please check next endpoints 
  a. upload csv file endpoint : <<localhost/port>>/api/products/import
  METHOD : POST
  Data : file with name products
  
    curl --location --request POST '<<localhost/port>>/api/products/import' \
    --header 'Content-Type: application/json' \
    --form 'products=@"<<Path to file>>"'

  b. get all products endpoint : <<localhost/port>>/api/products/all
  METHOD : GET
  
  curl --location --request GET '<<localhost/port>>/api/products/all'
  
  c. get single product data endpoint : <<localhost/port>>/api/products/{part_number}
  METHOD: GET
  
  curl --location --request GET '<<localhost/port>>/api/products/{part_number}'
  
  
 
