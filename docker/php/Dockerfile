FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor

# Install system dependencies
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap

# Install redis
# RUN pecl install redis && docker-php-ext-enable redis

# Install Supervisor
RUN apt-get install -y supervisor
RUN mkdir -p "/etc/supervisor/logs"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# Проверка установки composer
RUN which composer && composer --version

# Установка рабочей директории
WORKDIR /var/www/app

# Копируем остальной код приложения
COPY . .

# Установка зависимостей (только на первый раз, далее можно отключить)
RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Оптимизация автозагрузчика (только на первый раз, далее можно отключить)
RUN composer dump-autoload --optimize --classmap-authoritative

#todo: вернуть при случае вариант с superviosor
# CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]

# todo: вместо этого
COPY ./docker/php/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]