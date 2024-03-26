#!/bin/bash

# Crie o diretório /var/run/php se ele não existir
if [ ! -d /var/run/php ]; then
  mkdir -p /var/run/php
fi

# Defina as permissões adequadas
chmod 755 /var/run/php

chmod -R 777 /var/www/storage

# composer install
php artisan key:generate
# php artisan jwt:secret
php artisan migrate:fresh --seed
