# Multi-stage build - simples e eficiente
FROM php:8.2-fpm-alpine

# Definir diretório de trabalho
WORKDIR /var/www

# Instalar extensões PHP necessárias
RUN apk add --no-cache \
    mysql-client \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Exposar porta (o nginx vai acessar internamente)
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
