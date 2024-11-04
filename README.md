
# Instalación Sistema SIPADU

  ## Requisitos
  

 - php 8.2 o Mayor
 -  Base de datos : MariaDB ó Mysql
- Composer
 - node.js

  ## Instalación

### 1. Clonar proyecto del repositorio de GitHub

Dentro de la carpeta Raíz del servidor web , Ingrese el comando :

https://github.com/HelyChirinos/medicina.git

cambie al nuevo directorio del proyecto

  

### 2. Instalar las Dependencias de Composer

Ingrese el comando:

composer install

  

### 3. Instalar las Dependencias de NPM

Ingrese el comando:

npm install

npm run build

  
### 4. Crear el archivo .env

Ingrese el comando:

cp .env.example .env

  

### 5. Generar el App Encryption Key

Ingrese el comando:

php artisan key:generate

  

### 6. Crear una base de Datos vacia para el sistema

Use your favorite database management tool to create an empty database.

Configure a username and password.

  

### 7. Configure the .env file

Edite el archivo .env y coloque los valores correctos de :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=********

También Actualice los valores de mail:

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"


### 8. Impotar la Base de Datos
Utilice cualquier editor de Base de Datos e importe los datos de postgrado.sql
