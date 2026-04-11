@echo off
REM Script para actualizar base de datos desde schema.sql
REM Proyecto Macuin - Sincronización completa
REM Importante: usar siempre la carpeta donde está este .bat (evita import vacío si se abre con doble clic)

cd /d "%~dp0"

if not exist "schema.sql" (
    echo ERROR: No se encuentra schema.sql en:
    echo   %CD%
    echo Abre la consola en la carpeta del proyecto o mueve el .bat junto a schema.sql.
    pause
    exit /b 1
)

echo 🚀 Actualizando base de datos desde schema.sql...

REM Verificar si el contenedor está corriendo
docker ps | findstr "macuin-db" >nul
if errorlevel 1 (
    echo ⚠️  Iniciando contenedor de la base de datos...
    docker-compose up -d db
    timeout /t 10 /nobreak >nul
)

REM Eliminar base de datos existente y recrearla
echo 🗑️  Eliminando base de datos existente...
docker exec macuin-db psql -U macuin_user -d postgres -c "DROP DATABASE IF EXISTS macuin_db;"
docker exec macuin-db psql -U macuin_user -d postgres -c "CREATE DATABASE macuin_db;"

REM Importar el schema.sql completo
echo 📋 Importando schema.sql...
docker exec -i macuin-db psql -U macuin_user -d macuin_db < schema.sql

if errorlevel 1 (
    echo ❌ Error importando la base de datos
    pause
    exit /b 1
)

echo ✅ Base de datos actualizada exitosamente!
echo 🎉 Tu base de datos ahora es idéntica a la del servidor
pause
