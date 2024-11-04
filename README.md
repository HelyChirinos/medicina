
# Instalación Sistema SIPADU


## Clonar proyecto del repositorio de GitHub 
Dentro de la carpeta Raiz del servidor web , Ingrese el comando :
https://github.com/HelyChirinos/medicina.git
cambie al nuevo directorio del proyecto

## Installe Composer Dependencies
Ingrese el comando: 
composer install

## Install NPM Dependencies
Ingrese el comando: 
npm install
npm run dev or npm run build

## Create your copy of the .env file
Enter the command :
cp .env.example .env

## Generate an App Encryption Key
Enter the command :
php artisan key:generate

## Create an empty database for our application
Use your favorite database management tool to create an empty database.
Configure a username and password.

## Configure the .env file
Open the .env file for editing :
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=********
Adjust the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME and DB_PASSWORD options to match your situation. Also adjust the mail settings:
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
Adjust the MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION and MAIL_FROM_ADDRESS options to match your situation.

## Migrate the database
Enter the command : :
 php artisan migrate:fresh --seed
