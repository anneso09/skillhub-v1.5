# ── STAGE 1 : builder ──────────────────────────────────────────
# On utilise une image PHP légère avec les extensions nécessaires
FROM php:8.2-cli-alpine AS builder

# Extensions PHP requises par Laravel + MongoDB
RUN apk add --no-cache \
    curl \
    unzip \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
  && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb

# Installe Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copie d'abord les fichiers de dépendances AVANT le reste du code
# Pourquoi ? Docker met en cache chaque étape — si composer.json
# ne change pas, il réutilise le cache et n'installe pas tout à nouveau
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Maintenant on copie tout le code source
COPY . .

# ── STAGE 2 : runtime ──────────────────────────────────────────
# Image finale plus légère — on ne garde que ce qui est nécessaire
# pour faire tourner l'application, pas les outils de build
FROM php:8.2-fpm-alpine AS runtime

RUN apk add --no-cache \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
  && docker-php-ext-install pdo pdo_mysql mbstring \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb

WORKDIR /var/www/html

# On copie UNIQUEMENT le résultat du build (vendor + code)
# sans les outils de compilation — image finale plus petite
COPY --from=builder /app .

# Permissions correctes pour Laravel (storage + cache)
RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Lance le serveur Laravel intégré
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]