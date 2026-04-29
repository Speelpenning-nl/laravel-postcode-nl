FROM composer:2.9.7

FROM php:8.2.30-cli-alpine3.23

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV COMPOSER_CACHE_DIR=/app/.composer.cache

ENTRYPOINT ["composer"]
