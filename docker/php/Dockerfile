FROM php:8.0-fpm
COPY php.ini /usr/local/etc/php/

# 各種ライブラリインストール
RUN apt-get update \
  && apt-get install -y zlib1g-dev mariadb-client vim libzip-dev libicu-dev \
  && docker-php-ext-install zip pdo_mysql intl

# nodejs install
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
WORKDIR /var/www
RUN composer global require "laravel/installer"
