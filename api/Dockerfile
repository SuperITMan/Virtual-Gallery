FROM php:apache
MAINTAINER SuperITMan <admin@superitman.com>

RUN apt-get update && \
    apt-get install -y \
    unzip \
    curl \
    libmcrypt-dev \
    git && \
    curl -sS https://getcomposer.org/installer |  php -- --install-dir=/usr/local/bin --filename=composer

COPY src/ /var/www/
RUN rm -R /var/www/html
RUN mkdir -p /var/www/config
RUN mkdir -p /var/www/public/uploads

# php extensions
RUN docker-php-ext-install pdo pdo_mysql mcrypt


# php ini file
COPY config/php.ini /usr/local/etc/php/

# Chown www folder by www-data user
RUN chown www-data:www-data -R /var/www
USER www-data

# Install composer files
WORKDIR /var/www
RUN composer install
RUN ls -l /var/www/vendor

USER root

# Configuration Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN a2ensite 000-default

EXPOSE 443

# Launch script when container runs
COPY ./docker-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/public
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]