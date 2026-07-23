FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN { \
		echo 'display_errors = Off'; \
		echo 'log_errors = On'; \
	} > /usr/local/etc/php/conf.d/error-handling.ini

COPY . /var/www/html/
