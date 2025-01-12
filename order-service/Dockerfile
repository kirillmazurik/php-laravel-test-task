FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    sudo \
    zip \
    unzip \    
    mariadb-client

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    pdo_mysql \
    intl \
    decimal-^1 \
    && rm /usr/local/bin/install-php-extensions

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USERNAME=developer
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} mygroup
RUN useradd -u ${USER_ID} -g mygroup -m $USERNAME
USER $USERNAME

WORKDIR /var/www/api/order-service

COPY . .

USER root
RUN mkdir -p bootstrap/cache
RUN chown -R developer:mygroup /var/www/api/order-service

USER $USERNAME
