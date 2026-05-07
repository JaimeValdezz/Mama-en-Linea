#!/bin/bash

# 1. Instalar PHP y herramientas básicas
apt-get update && apt-get install -y php-cli php-mbstring php-xml php-curl unzip

# 2. Bajar Composer manualmente
curl -sS https://getcomposer.org/installer | php

# 3. Instalar lo que Laravel ocupa
php composer.phar install --no-dev --optimize-autoloader

# 4. Preparar la app
php artisan config:cache
php artisan route:cache
