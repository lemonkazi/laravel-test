FROM php:8.2.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# credits goes here
LABEL author="Mamun"
LABEL maintainer="Mamun"
LABEL inc="true"
LABEL laravel-app="true"

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    cron \
    nano \
    curl \
    vim \
    locales \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    -y mariadb-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install zip mysqli pdo_mysql mbstring exif pcntl bcmath gd && docker-php-ext-enable mysqli


# install php intl extension
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
# install calendar extension
RUN docker-php-ext-configure calendar
RUN docker-php-ext-install calendar
# install php redis extension
RUN pecl install redis
RUN docker-php-ext-enable redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer



# composer
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php composer-setup.php
# RUN php -r "unlink('composer-setup.php');"
# RUN php composer.phar install


# copy the apache config file
#COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# change uid and gid of apache to docker user uid/gid
# Add user for laravel application
# RUN groupadd -g 1000 html
# RUN useradd -u 1000 -ms /bin/bash -g html html

# Copy existing application directory contents
# COPY . /var/www/html
# COPY docker/apache/apache2.conf /etc/apache2/apache2.conf
# copy the PHP ini settings
#COPY docker/php/* /usr/local/etc/php/conf.d/

# enable mod-rewrite
#RUN a2enmod rewrite

# fix the permission issue
#RUN umask 000

#RUN crontab -l | { cat; echo "* * * * * cd /var/www/html && /bin/bash bin/cake PushSend"; } | crontab -


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Copy existing application directory permissions
#COPY --chown=user:user . /var/www/html


USER $user

# restart apache
#RUN service apache2 start

# Expose port 9000 and start php-fpm server
#EXPOSE 9000
CMD ["php-fpm"]
