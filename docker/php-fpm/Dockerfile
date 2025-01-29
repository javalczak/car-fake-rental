FROM php:8.3-fpm

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
    coreutils \
    make \
    curl \
    libcurl4-openssl-dev \
    libxml2-dev \
    zlib1g-dev \
    libjpeg-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libzip-dev \
    libxslt1-dev \
    bash \
    git \
    unzip \
    nginx

RUN docker-php-ext-install pcntl zip opcache pdo_mysql

RUN docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'upload_max_filesize = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'post_max_size = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

ENV PHP_MEMORY_LIMIT=4000M
ENV POST_MAX_SIZE=512M
ENV UPLOAD_MAX_FILESIZE = 512M

WORKDIR /var/www/app

CMD ["php-fpm"]

EXPOSE 9000