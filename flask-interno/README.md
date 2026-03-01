# MACUIN — Panel interno (Flask)

Aplicación interna para gestión administrativa de MACUIN. Desarrollada con Flask.

## Requisitos

- Python 3.8 o superior

## Cómo ejecutar el proyecto

### 1. Entrar a la carpeta del proyecto

```bash
cd flask-interno
```

### 2. Crear el entorno virtual (si aún no existe)

```bash
python -m venv .venv
```

### 3. Activar el entorno virtual

**Windows (PowerShell):**
```powershell
.venv\Scripts\Activate.ps1
```

**Windows (CMD):**
```cmd
.venv\Scripts\activate.bat
```

**Linux / macOS:**
```bash
source .venv/bin/activate
```

Cuando el entorno esté activo, verás `(.venv)` al inicio de la línea en la terminal.

### 4. Instalar dependencias

```bash
pip install -r requirements.txt
```

### 5. Ejecutar la aplicación

```bash
python app.py
```

O con el comando de Flask:

```bash
flask run
```

La aplicación quedará disponible en **http://127.0.0.1:5000** (o el puerto que indique la consola).

### Rutas disponibles

- **GET /login** — Pantalla de acceso interno
- **GET /dashboard** — Panel de gestión (texto de prueba)

## Desactivar el entorno virtual

Cuando termines de trabajar, desactiva el entorno con:

```bash
deactivate
```
