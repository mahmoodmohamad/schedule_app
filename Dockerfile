# ===========================
# Stage 1 - PHP dependencies
# ===========================
FROM php:8.2-fpm AS backend

# Install system packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY . .

# ===========================
# Stage 2 - Build Vue frontend
# ===========================
FROM node:18 AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .
RUN npm run build

# ===========================
# Stage 3 - Production image
# ===========================
FROM nginx:stable

# Copy Laravel app
COPY --from=backend /var/www /var/www

# Copy built Vue files into public/
COPY --from=frontend /app/public /var/www/public

# Copy Nginx config
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
