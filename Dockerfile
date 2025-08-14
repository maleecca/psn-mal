# ---------- Stage 1: install dependency via Composer ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
# Jika tidak ada composer.lock, perintah ini tetap aman; nanti lanjut install lagi setelah source disalin
RUN composer install --no-dev --no-interaction --prefer-dist || true
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ---------- Stage 2: runtime PHP ----------
FROM php:8.3-cli
WORKDIR /app

# System & PHP extensions minimal untuk Laravel + MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev \
 && docker-php-ext-install pdo_mysql zip bcmath mbstring exif \
 && rm -rf /var/lib/apt/lists/*

# Copy aplikasi dari stage vendor
COPY --from=vendor /app /app

# Permission folder penting
RUN mkdir -p storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Railway akan kasih $PORT; default 8000 kalau tak ada
ENV PORT=8000

# Start: siapkan .env (jika belum), generate key (jika perlu), migrate, cache config, lalu jalankan server
CMD sh -lc 'cp -n .env.example .env 2>/dev/null || true; \
    php artisan key:generate --force 2>/dev/null || true; \
    php artisan storage:link 2>/dev/null || true; \
    php artisan migrate --force 2>/dev/null || true; \
    php artisan config:cache; \
    php -S 0.0.0.0:${PORT} -t public public/index.php'
