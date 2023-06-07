FROM php:8.1-fpm

RUN apt-get update \
  && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  zlib1g-dev \
  libpq-dev \
  libzip-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_mysql mysqli zip bcmath gd

COPY ./uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

RUN composer install
