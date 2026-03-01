# MACUIN — Portal externo (Laravel)

Portal para clientes y usuarios externos de MACUIN. Desarrollado con Laravel.

## Requisitos

- **PHP** 8.2 o superior
- **Composer** ([getcomposer.org](https://getcomposer.org))

## Cómo ejecutar el proyecto

### 1. Entrar a la carpeta del proyecto

```bash
cd laravel-externo
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

Copia el archivo de ejemplo y genera la clave de aplicación:

```bash
copy .env.example .env
```

**Linux / macOS:**
```bash
cp .env.example .env
```

Luego genera la clave:

```bash
php artisan key:generate
```

### 4. Levantar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación quedará disponible en **http://127.0.0.1:8000**.

### 5. (Opcional) Base de datos

Si vas a usar base de datos, configura en `.env` las variables `DB_*` y ejecuta:

```bash
php artisan migrate
```

## Rutas principales

| Ruta        | Descripción              |
|------------|--------------------------|
| `/`        | Página de bienvenida     |
| `/login`   | Iniciar sesión           |
| `/registro`| Crear cuenta             |
| `/dashboard` | Panel externo          |
| `/catalogo`  | Catálogo de autopartes |
| `/pedidos/crear` | Crear pedido        |
| `/pedidos/{id}`  | Detalle de pedido    |

## Logo e imágenes

Coloca el logo en **`public/images/logo_macuin.png`** para que se muestre en login, registro, dashboard y catálogo.
