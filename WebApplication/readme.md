This folder contains the Quiz System application.

Installation

Packages required:
	php
	php-cli
	php-common
	php-curl
	php-mcrypt
	php-mysql
	imagemagick
	php-imagick
	ghostscript

PHP packages have to be version >5.6.4

Other requirements:
	Database of some description, recommended MySQL
	Webserver of some description (though Laravel can run its own local server, for development Laravel Valet recommended)
	Composer - https://getcomposer.org/

Instructions:
1. cd to this directory
2. composer install
if no .env file then
    cp .env.example .env
else
3. Fill .env will relevant data, such as database details, pusher details and change BROADCAST_DRIVER to pusher 
4. php artisan key:generate
5. php artisan migrate
6. php artisan storage:link
7. If using your own Pusher account, change resources/views/quizzes/run/quiz.blade.php pusher_app_key

If you have no webserver, running "php artisan serve" should run one with the application.

If you want to seed the database: php artisan migrate:refresh --seed (be careful as this will remigrate the database and delete and information you have stored)

For testing:
1. cp .env .env.dusk.local
2. change any relevant .env.dusk.local variables for testing environment

To run the tests: php artisan dusk
