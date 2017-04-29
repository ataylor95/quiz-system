This folder contains the Quiz System application.

Installation

Packages required:
	php5
	php5-cli
	php5-common
	php5-curl
	php5-mcrypt
	php5-mysql
	imagemagick
	php-imagick
	ghostscript

Other requirements:
	Database of some description, recommended MySQL
	Webserver of some description (though Laravel can run its own local server)
	Composer - https://getcomposer.org/

Instructions:
1. cd to the WebApplication folder
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. php artisan migrate

If you have no webserver, running "php artisan serve" should run one with the application.

If you want to seed the database: php artisan migrate:refresh --seed (be careful as this will remigrate the database and delete and information you have stored)

To run the tests: php artisan dusk
