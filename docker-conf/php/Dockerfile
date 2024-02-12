FROM php:8.2-apache

RUN apt-get update \
    &&  apt-get install -y --no-install-recommends ssl-cert \
        locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libmagickwand-dev\
\
    && a2enmod rewrite \
    && a2enmod ssl \
    && a2enmod setenvif \
    && a2enmod headers \
    &&  echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
    &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    &&  locale-gen \
\
    &&  curl -sS https://getcomposer.org/installer | php -- \
    &&  mv composer.phar /usr/local/bin/composer \
#\
#    &&  curl -sS https://get.symfony.com/cli/installer | bash \
#    &&  mv /root/.symfony/bin/symfony /usr/local/bin \
\
    &&  docker-php-ext-configure \
            intl \
    &&  docker-php-ext-install \
            pdo pdo_mysql opcache intl zip calendar dom mbstring gd xsl bcmath \
\
    &&  pecl install apcu xdebug imagick && docker-php-ext-enable apcu xdebug imagick

RUN usermod -u 1000 www-data

WORKDIR /var/www
