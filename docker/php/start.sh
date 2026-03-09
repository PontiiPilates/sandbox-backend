#!/bin/sh

# Запуск PHP-FPM в фоне
php-fpm -D

# Запуск планировщика на переднем плане
php artisan schedule:work