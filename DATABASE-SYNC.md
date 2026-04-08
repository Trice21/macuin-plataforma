# Sincronización de Base de Datos - Proyecto Macuin

## 🎯 Objetivo
Este sistema asegura que todos los colaboradores tengan exactamente la misma base de datos que tú tienes en tu servidor PostgreSQL.

## 🚀 Para Colaboradores

Cuando hagas `git pull` y haya cambios en la base de datos:

### Windows
```bash
update-database.bat
```

### Linux/Mac
```bash
chmod +x update-database.sh
./update-database.sh
```

## 🔄 ¿Qué hace el script?

1. **Inicia el contenedor** de PostgreSQL si no está corriendo
2. **Elimina completamente** la base de datos actual
3. **Crea una base de datos nueva** y vacía
4. **Importa `schema.sql`** con toda la estructura y datos
5. **Resultado**: Base de datos idéntica a la del servidor

## 🛠️ Para Desarrolladores (cuando modificas la base)

1. **Haz cambios** en tu servidor PostgreSQL
2. **Exporta el schema**: `pg_dump -U macuin_user -h localhost macuin_db > schema.sql`
3. **Prueba localmente**: `update-database.bat` (Windows) o `./update-database.sh` (Linux/Mac)
4. **Commit**: `git add schema.sql && git commit -m "Update database schema"`
5. **Push**: `git push`

## 📋 Estructura Actual de la Base

Tu `schema.sql` contiene:
- **Tablas**: users, autoparts, orders, order_items, cart_items, migrations
- **Enums**: userrole, orderstatus
- **Datos iniciales**: Usuarios admin/cliente, autoparts de ejemplo
- **Secuencias**: Autoincrementales para IDs
- **Índices y restricciones**: Claves primarias, foráneas, únicas

## ⚠️ Advertencia Importante

- **El script ELIMINA todos los datos existentes** en la base de datos local
- **Los reemplaza completamente** con los datos de `schema.sql`
- **Ideal para desarrollo** pero **cuidado con datos de producción**

## 🔧 Comandos Útiles

```bash
# Verificar estado del contenedor
docker ps | grep macuin-db

# Acceder a la base de datos manualmente
docker exec -it macuin-db psql -U macuin_user -d macuin_db

# Reiniciar el contenedor
docker-compose restart db

# Verificar tablas después de sincronizar
docker exec macuin-db psql -U macuin_user -d macuin_db -c "\dt"
```

## 🎉 Resultado Final

Después de ejecutar el script, cada colaborador tendrá:
- ✅ **Misma estructura** de tablas y columnas
- ✅ **Mismos datos** iniciales
- ✅ **Mismos índices** y restricciones
- ✅ **Base idéntica** a la del servidor principal

---

**Este sistema garantiza que todos trabajen con exactamente la misma base de datos** que tú tienes en tu servidor PostgreSQL.
