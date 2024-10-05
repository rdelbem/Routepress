FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    net-tools \ 
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install \
        mysqli \
        pdo_mysql \
        mbstring \
        zip \
        pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp

# WP-browser needs the chrome driver
RUN apt-get install -y chromium

WORKDIR /var/www/html

EXPOSE 9000
