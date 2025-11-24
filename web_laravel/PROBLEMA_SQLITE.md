# 🔴 PROBLEMA IDENTIFICADO: SQLite Driver No Disponible

## Error Encontrado

```
could not find driver (Connection: sqlite, SQL: ...)
```

## Causa

El driver de **SQLite** no está habilitado en tu instalación de PHP de Laragon.

---

## Solución

### Opción 1: Habilitar SQLite en PHP (Recomendado)

1. **Abre el archivo `php.ini` de Laragon:**
   - Ruta típica: `C:\laragon\bin\php\php-8.x.x\php.ini`
   - O desde Laragon: Click derecho en el ícono → PHP → php.ini

2. **Busca la línea:**
   ```ini
   ;extension=pdo_sqlite
   ;extension=sqlite3
   ```

3. **Quita el punto y coma (;) para habilitar:**
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```

4. **Guarda el archivo y reinicia Laragon**

5. **Verifica que esté habilitado:**
   ```bash
   php -m | grep sqlite
   ```

   Deberías ver:
   ```
   pdo_sqlite
   sqlite3
   ```

---

### Opción 2: Cambiar a MySQL (Alternativa)

Si prefieres usar MySQL (que Laragon ya tiene instalado):

1. **Crea una base de datos en MySQL:**
   - Abre HeidiSQL (desde Laragon)
   - Crea una nueva base de datos llamada `sbvc_comidas`

2. **Actualiza tu `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sbvc_comidas
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Ejecuta las migraciones:**
   ```bash
   php artisan migrate:fresh
   ```

---

## Verificar el Problema

**Comando para verificar extensiones PHP:**
```bash
php -m
```

**Buscar SQLite específicamente:**
```bash
php -m | grep -i sqlite
```

Si no aparece nada, SQLite no está habilitado.

---

## Después de Habilitar SQLite

1. **Verifica que funcione:**
   ```bash
   php artisan tinker --execute="echo DB::connection()->getPdo();"
   ```

2. **Ejecuta las migraciones (si es necesario):**
   ```bash
   php artisan migrate:fresh
   ```

3. **Prueba el formulario nuevamente:**
   ```bash
   composer run dev
   ```

   Navega a: http://127.0.0.1:8000/completar-registro

---

## Revisar Logs

Después de intentar guardar, revisa los logs:

```bash
tail -f storage/logs/laravel.log
```

Deberías ver ahora más detalles sobre el error real (si es que hay otro).

---

## Probar con Tinker (Después de Habilitar SQLite)

```bash
php artisan tinker
```

Luego ejecuta:
```php
// Ver usuarios
\App\Models\User::all();

// Ver clientes
\App\Models\Cliente::all();

// Crear un cliente de prueba
$cliente = \App\Models\Cliente::create([
    'user_id' => 1,
    'nombre_titular' => 'Juan Pérez',
    'email_contacto' => 'juan@test.com',
    'telefono' => '7771234567',
    'plan' => 'estandar',
    'fecha_inicio_suscripcion' => now(),
    'suscripcion_activa' => true
]);

// Verificar que se creó
\App\Models\Cliente::find($cliente->id);
```

---

## Mi Recomendación

**Usa MySQL** en lugar de SQLite para desarrollo con Laragon porque:

✅ MySQL ya está instalado y configurado en Laragon
✅ Más fácil de visualizar datos con HeidiSQL
✅ Más similar a producción
✅ Mejor soporte para características avanzadas
✅ No requiere habilitar extensiones de PHP

**Pasos rápidos para cambiar a MySQL:**

```bash
# 1. Cambiar .env
DB_CONNECTION=mysql
DB_DATABASE=sbvc_comidas

# 2. Crear la base de datos (desde HeidiSQL o línea de comandos)
mysql -u root -e "CREATE DATABASE sbvc_comidas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Ejecutar migraciones
php artisan migrate:fresh

# 4. Probar
composer run dev
```

---

## Siguiente Paso

**Decide qué opción prefieres:**

1. **Habilitar SQLite** → Edita php.ini, reinicia Laragon
2. **Cambiar a MySQL** → Actualiza .env, crea la BD, ejecuta migraciones

**Luego prueba nuevamente el formulario y dime si funciona.**

---

¿Cuál opción prefieres? Te ayudo a implementarla.
