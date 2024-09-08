FROM php:8.3-apache


# Install PHP extensions deps
RUN apt-get update \
	&& apt-get install --no-install-recommends -y \
		zip unzip \
		git \
		zlib1g-dev libpng-dev libjpeg-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev \
		libzip-dev libicu-dev libssl-dev


RUN docker-php-ext-configure \
		gd --with-jpeg --with-webp \
	&& docker-php-ext-install \
		gd bcmath zip iconv intl phar


RUN a2enmod rewrite

COPY httpd.conf /etc/apache2/sites-available/000-default.conf
