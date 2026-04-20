#!/bin/sh

# Запуск PHP-FPM в фоне
php-fpm -D

# Запуск планировщика на переднем плане
php artisan schedule:work

# Создание ссылки на публичную директорию
php artisan storage:link

# Раздача прав на tmp для MadelineProto
sudo chmod 777 /tmp -R