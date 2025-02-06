 
# Используем PHP с Apache
FROM php:8.2-apache

# Устанавливаем расширения PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl libonig-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd mbstring pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем зависимости Laravel
WORKDIR /var/www
COPY . . 

# Создаем нужные папки и даем права
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Открываем порт 80
EXPOSE 80

# Запускаем Apache
CMD ["apache2-foreground"]
