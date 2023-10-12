#syntax=docker/dockerfile:1.4
FROM php:8.2.10-cli-alpine3.17

RUN set -eux; \
    apk update; \
    apk del; \
    rm -rf /var/lib/apt/lists/*;

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer pgsql;

COPY --link php.ini $PHP_INI_DIR/conf.d/
RUN set -eux; \
    cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini";
