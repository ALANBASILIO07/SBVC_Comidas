# 🔧 Guía de Solución: Error SQLite - Columna "nombre_titular" no encontrada

## 📋 Resumen del Problema

**Error actual:**
```
SQLSTATE[HY000]: General error: 1 table clientes has no column named nombre_titular
(Connection: sqlite, SQL: insert into "clientes" ...)
```

**Causa raíz:**
1. La extensión PDO SQLite no está habilitada en PHP
2. La base de datos SQLite no tiene la estructura correcta (migraciones no se han ejecutado)

---

## 🚨 Problema 1: Extensión SQLite Deshabilitada

### Error previo observado:
```
could not find driver (Connection: sqlite, SQL: ...)
```

### Solución:

#### Paso 1: Localizar el archivo php.ini de Laragon

1. Abre **Laragon**
2. Click derecho en el icono de Laragon en la bandeja del sistema
3. Ve a: **PHP** → **php.ini**

O manualmente en:
```
C:\laragon\bin\php\php-8.x.x\php.ini
```

#### Paso 2: Habilitar extensiones SQLite

Busca estas líneas en `php.ini` y **quita el punto y coma (;)** al inicio:

**Antes:**
```ini
;extension=pdo_sqlite
;extension=sqlite3
```

**Después:**
```ini
extension=pdo_sqlite
extension=sqlite3
```

#### Paso 3: Reiniciar Laragon

1. En Laragon, click en **"Stop All"**
2. Luego click en **"Start All"**
3. Verifica que Apache y MySQL se reinicien correctamente

#### Paso 4: Verificar que SQLite está habilitado

Ejecuta en CMD:
```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php -m | findstr -i sqlite
```

**Salida esperada:**
```
pdo_sqlite
sqlite3
```

Si no aparece nada, revisa que editaste el php.ini correcto.

---

## 🗄️ Problema 2: Base de Datos SQLite Sin Estructura

### Causa:
Las migraciones de Laravel nunca se ejecutaron, por lo que la tabla `clientes` no existe o no tiene las columnas correctas.

### Solución:

#### Opción A: Refrescar Migraciones (Recomendado para desarrollo)

**⚠️ ADVERTENCIA: Esto borrará todos los datos de la base de datos**

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php artisan migrate:fresh --seed
```

**Qué hace:**
- Elimina todas las tablas
- Ejecuta todas las migraciones desde cero
- Ejecuta los seeders (si los hay)

#### Opción B: Ejecutar solo las migraciones faltantes

Si ya tienes datos importantes:

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php artisan migrate
```

**Qué hace:**
- Ejecuta solo las migraciones que no se han corrido
- Respeta los datos existentes

#### Opción C: Forzar recreación de tabla específica

Si solo la tabla `clientes` tiene problemas:

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php artisan migrate:rollback --step=1
php artisan migrate
```

---

## 🔍 Verificación de la Estructura de la Base de Datos

### Método 1: Usando Laravel Tinker

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php artisan tinker
```

Dentro de tinker, ejecuta:

```php
// Verificar que la tabla existe
Schema::hasTable('clientes');
// Debe retornar: true

// Ver todas las columnas de la tabla
Schema::getColumnListing('clientes');
// Debe incluir: nombre_titular, email_contacto, telefono, etc.

// Verificar columna específica
Schema::hasColumn('clientes', 'nombre_titular');
// Debe retornar: true

// Salir de tinker
exit
```

### Método 2: Inspección directa del archivo SQLite

**Ubicación de la base de datos:**
```
C:\laragon\www\SBVC_Comidas\web_laravel\database\database.sqlite
```

**Usar DB Browser for SQLite (herramienta gratuita):**

1. Descarga: https://sqlitebrowser.org/
2. Abre el archivo `database.sqlite`
3. Ve a la pestaña **"Database Structure"**
4. Expande la tabla `clientes`
5. Verifica que existan las columnas:
   - `id`
   - `user_id`
   - `nombre_titular` ✅
   - `email_contacto`
   - `telefono`
   - `plan`
   - `fecha_inicio_suscripcion`
   - `fecha_fin_suscripcion`
   - `suscripcion_activa`
   - `rfc_titular`
   - `razon_social_titular`
   - `created_at`
   - `updated_at`
   - `deleted_at`

---

## 📝 Estructura Esperada de la Tabla `clientes`

Según la migración en:
`database/migrations/2025_11_14_201741_create_clientes_table.php`

```sql
CREATE TABLE "clientes" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "user_id" INTEGER NOT NULL UNIQUE,
    "nombre_titular" VARCHAR NOT NULL,
    "email_contacto" VARCHAR NOT NULL,
    "telefono" VARCHAR NOT NULL,
    "plan" VARCHAR NOT NULL DEFAULT 'estandar',
    "fecha_inicio_suscripcion" DATETIME,
    "fecha_fin_suscripcion" DATETIME,
    "suscripcion_activa" TINYINT(1) NOT NULL DEFAULT 1,
    "rfc_titular" VARCHAR,
    "razon_social_titular" VARCHAR,
    "created_at" DATETIME,
    "updated_at" DATETIME,
    "deleted_at" DATETIME,
    FOREIGN KEY("user_id") REFERENCES "users"("id") ON DELETE CASCADE
);
```

---

## 🧪 Prueba Completa del Sistema

Una vez aplicadas las correcciones, ejecuta esta secuencia de pruebas:

### 1. Verificar Servidor Laravel

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
php artisan serve
```

**Salida esperada:**
```
INFO  Server running on [http://127.0.0.1:8000]
```

### 2. Verificar Configuración de Base de Datos

```bash
php artisan tinker
```

```php
// Verificar conexión
DB::connection()->getPdo();
// Debe mostrar: PDO object

// Verificar nombre de la base de datos
DB::connection()->getDatabaseName();
// Debe mostrar: C:\laragon\www\SBVC_Comidas\web_laravel\database\database.sqlite

// Verificar driver
DB::connection()->getDriverName();
// Debe mostrar: sqlite

exit
```

### 3. Probar Registro de Cliente

1. Navega a: http://127.0.0.1:8000/completar-registro
2. Llena el formulario:
   - **Nombre completo:** Juan Pérez García
   - **Teléfono:** 7771234567
   - **RFC (opcional):** XAXX010101000
3. Click en **"Guardar"**
4. **Resultado esperado:**
   - SweetAlert verde con mensaje: "¡Registro completado exitosamente!"
   - Redirección al dashboard
   - Sin errores en consola

### 4. Verificar Logs de Laravel

```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel
tail -f storage/logs/laravel.log
```

**Logs esperados:**
```
[timestamp] local.INFO: Intentando crear cliente con datos: {"user_id":1,"nombre_titular":"Juan P\u00e9rez Garc\u00eda","email_contacto":"user@example.com"...}
[timestamp] local.INFO: Cliente creado exitosamente con ID: 1
```

---

## 🐛 Troubleshooting

### Error: "No such file or directory"

**Causa:** El archivo `database.sqlite` no existe.

**Solución:**
```bash
cd C:\laragon\www\SBVC_Comidas\web_laravel\database
type nul > database.sqlite
php artisan migrate:fresh
```

### Error: "Database is locked"

**Causa:** Otro proceso tiene la base de datos abierta.

**Solución:**
1. Cierra DB Browser for SQLite si lo tienes abierto
2. Cierra cualquier ventana de tinker
3. Reinicia el servidor Laravel

### Error: "FOREIGN KEY constraint failed"

**Causa:** Intentas crear un cliente sin un usuario válido.

**Solución:**
```bash
php artisan tinker
```

```php
// Verificar que el usuario existe
User::find(Auth::id());

// Si no existe, crear uno de prueba
User::factory()->create([
    'email' => 'test@example.com',
    'password' => bcrypt('password')
]);

exit
```

---

## ✅ Checklist de Solución

Marca cada paso conforme lo completes:

- [ ] Habilitar `extension=pdo_sqlite` en php.ini
- [ ] Habilitar `extension=sqlite3` en php.ini
- [ ] Reiniciar Laragon (Stop All → Start All)
- [ ] Verificar extensiones con `php -m | findstr sqlite`
- [ ] Ejecutar `php artisan migrate:fresh` (o `migrate`)
- [ ] Verificar tabla con tinker: `Schema::hasTable('clientes')`
- [ ] Verificar columna con tinker: `Schema::hasColumn('clientes', 'nombre_titular')`
- [ ] Probar formulario de registro en navegador
- [ ] Revisar logs de Laravel para confirmar creación exitosa

---

## 📚 Archivos Relacionados

### Controlador:
`app/Http/Controllers/ClienteController.php`

### Modelo:
`app/Models/Cliente.php`

### Migración:
`database/migrations/2025_11_14_201741_create_clientes_table.php`

### Vista del formulario:
`resources/views/clientes/complete_profile.blade.php`

### Rutas:
`routes/web.php`
- `GET /completar-registro` → `ClienteController@create`
- `POST /clientes` → `ClienteController@store`

---

## 🎯 Resultado Final Esperado

Después de aplicar todas las correcciones:

1. **El formulario de registro funciona correctamente**
2. **Los datos se guardan en SQLite sin errores**
3. **SweetAlert muestra notificación de éxito**
4. **La app de Flutter puede acceder a la misma base de datos SQLite**
5. **Los logs de Laravel muestran "Cliente creado exitosamente"**

---

## 🔗 Referencias

- **Documentación de Laravel sobre SQLite:** https://laravel.com/docs/11.x/database#sqlite-configuration
- **Guía de migraciones:** https://laravel.com/docs/11.x/migrations
- **DB Browser for SQLite:** https://sqlitebrowser.org/
- **SweetAlert2 con CDN:** https://sweetalert2.github.io/

---

**Fecha de creación:** 24 de noviembre de 2025
**Última actualización:** 24 de noviembre de 2025
