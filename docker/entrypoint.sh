#!/bin/sh
set -e

php artisan storage:link || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    n=0
    until php artisan migrate --force; do
        n=$((n + 1))
        if [ "$n" -ge 20 ]; then
            echo "No se pudo conectar a la base de datos tras $n intentos"
            exit 1
        fi
        echo "Esperando base de datos... ($n/20)"
        sleep 3
    done
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

exec "$@"
