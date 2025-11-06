# Prueba técnica Laravel CRUD

Este repositorio contiene una aplicación Laravel de ejemplo con un CRUD básico para proveedores y servicios.

## Requisitos

- PHP ^8.1
- Composer
- MySQL

## Instrucciones de instalación (adaptadas a este proyecto)

1. Clona el repositorio (reemplaza con tu usuario y nombre de repo):

	git clone https://github.com/<usuario>/<repo>.git
	cd <repo>

2. Instala dependencias PHP:

	composer install

3. Copia el archivo de entorno y genera la clave de aplicación:

	cp .env.example .env
	php artisan key:generate

4. Usar MySQL para pruebas locales:

	# DB_CONNECTION=mysql
	# DB_DATABASE=laravel
	# DB_HOST=127.0.0.1
	# DB_PORT=3306
	# DB_USERNAME=root
	# DB_PASSWORD=tu_password

5. Ejecuta migraciones y seeders de ejemplo:

	php artisan migrate --seed

Notas sobre seeders específicos (pequeña guía):

- Para crear/actualizar el super usuario usa:

  php artisan db:seed --class=AdminUserSeeder

- Para poblar datos de ejemplo (providers y services) usa:

  php artisan db:seed --class=DatabaseSeeder

- Si quieres borrar todo y arrancar desde cero (elimina y vuelve a migrar):

  php artisan migrate:fresh

> Estas tres líneas (AdminUserSeeder, DatabaseSeeder y migrate:fresh) las dejo como nota para uso rápido.

6. Levanta el servidor local de desarrollo:

	php artisan serve