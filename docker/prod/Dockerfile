FROM php:8.4-fpm AS app

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring

COPY . /var/www/html

RUN mkdir -p /var/www/html/public \
    && chown -R www-data:www-data /var/www/html

RUN groupadd -g 1000 app && useradd -m -u 1000 -g app app

USER www-data

CMD ["php-fpm"]

FROM nginx:alpine AS nginx

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --from=app /var/www/html/public /var/www/html/public

WORKDIR /var/www/html
