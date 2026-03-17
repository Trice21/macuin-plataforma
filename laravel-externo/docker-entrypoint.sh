#!/bin/bash
# Salir inmediatamente si un comando falla (excepto en el chequeo de DB)
set -e

echo "--- Iniciando proceso de arranque de Laravel ---"

# 1. Configuración del entorno
if [ ! -f ".env" ]; then
    echo "Copiando .env.example a .env..."
    cp .env.example .env
fi

# 2. Instalar dependencias si no existen (Caso: clonado nuevo o volumen vacío)
if [ ! -f "vendor/autoload.php" ]; then
    echo "Dependencias de Composer no encontradas. Instalando..."
    composer install --no-interaction --prefer-dist
fi

if [ ! -f "node_modules/.bin/vite" ]; then
    echo "Dependencias de Node no encontradas. Instalando..."
    npm install
    echo "Compilando assets con Vite..."
    npm run build
fi

# 3. Generar clave de aplicación
if ! grep -q "APP_KEY=base64" .env; then
    echo "Generando clave de aplicación..."
    php artisan key:generate --force
fi

# 4. Esperar a la Base de Datos
echo "Esperando a la base de datos (db:3306)..."
# Desactivamos set -e momentáneamente para el bucle de conexión
set +e
for i in {1..30}; do
    if php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT', '$DB_USERNAME', '$DB_PASSWORD');" > /dev/null 2>&1; then
        echo "¡Base de datos conectada!"
        DB_READY=true
        break
    fi
    echo "Intento $i: DB no lista, reintentando en 2s..."
    sleep 2
done
set -e

if [ "$DB_READY" != true ]; then
    echo "Error: No se pudo conectar a la base de datos tras 30 intentos."
    exit 1
fi

# 5. Base de Datos y Optimización
echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Optimizando Laravel (Caché de configuración, rutas y vistas)..."
# Primero limpiamos para evitar conflictos y luego cacheamos
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Permisos (Vital para que Apache pueda escribir)
echo "Ajustando permisos de carpetas..."
# Intentar ajustar permisos solo si el directorio existe
if [ -d "storage" ]; then
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
fi

echo "--- Todo listo. El servidor debería ser mucho más rápido ahora. ---"
exec apache2-foreground
