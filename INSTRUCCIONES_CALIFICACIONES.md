# Sistema de Calificaciones - Instrucciones de Instalación

## ✅ Archivos Creados

Se ha implementado un sistema completo de calificaciones con las siguientes características:

### 1. Base de Datos

**Migración:** `database/migrations/2025_12_10_000001_create_resenas_table.php`

Tabla `resenas` con los siguientes campos:
- `id` - ID único
- `establecimiento_id` - Relación con establecimientos
- `cliente_nombre` - Nombre del cliente que calificó
- `cliente_email` - Email opcional
- `puntuacion` - Calificación de 1 a 5 estrellas
- `comentario` - Texto de la reseña
- `verificada` - Si la reseña está verificada
- `activa` - Si la reseña está activa
- `created_at`, `updated_at` - Timestamps

### 2. Modelo

**Archivo:** `app/Models/Resena.php`

**Características:**
- Relación `belongsTo` con Establecimientos
- **Scopes avanzados:**
  - `activas()` - Solo reseñas activas
  - `porEstablecimiento($id)` - Filtrar por establecimiento
  - `porPuntuacion($puntuacion)` - Filtrar por estrellas
  - `masRecientes()` - Ordenar por más recientes
  - `masAntiguas()` - Ordenar por más antiguas
  - `mejorCalificadas()` - Ordenar por mejor puntuación
  - `peorCalificadas()` - Ordenar por peor puntuación
  - `verificadas()` - Solo verificadas

- **Métodos helper:**
  - `estrellasTexto()` - Devuelve "★★★★★" según puntuación
  - `colorPuntuacion()` - Clase CSS según puntuación
  - `tiempoRelativo()` - "hace 2 días"
  - `fechaFormateada()` - "10 de Diciembre, 2025"

**Relación agregada a Establecimientos:**
```php
public function resenas(): HasMany
{
    return $this->hasMany(Resena::class, 'establecimiento_id');
}
```

### 3. Controlador

**Archivo:** `app/Http/Controllers/CalificacionController.php`

**Métodos:**
- `index()` - Vista principal con filtros y estadísticas
- `todas()` - Vista completa de todas las reseñas
- `calcularEstadisticas()` - Promedio, total, reseñas del mes
- `calcularDistribucion()` - Distribución de 1-5 estrellas con porcentajes

**Filtros implementados:**
- Por establecimiento
- Por puntuación (1-5 estrellas)
- Ordenamiento: recientes, antiguas, mejor/peor calificadas

### 4. Rutas

**Archivo:** `routes/web.php` (líneas 131-134)

```php
Route::prefix('calificaciones')->name('calificaciones.')->group(function () {
    Route::get('/', [CalificacionController::class, 'index'])->name('index');
    Route::get('/todas', [CalificacionController::class, 'todas'])->name('todas');
});
```

### 5. Vista

**Archivo:** `resources/views/calificaciones/index.blade.php`

**Mejoras implementadas:**
- ✅ Eliminados los 2 filtros duplicados "Todos los restaurantes"
- ✅ 3 filtros funcionales con auto-submit:
  1. **Establecimientos** - Todos los restaurantes del cliente
  2. **Puntuación** - 1 a 5 estrellas
  3. **Ordenamiento** - Recientes, antiguas, mejor/peor calificadas
- ✅ Estadísticas dinámicas:
  - Calificación promedio con estrellas
  - Total de reseñas
  - Reseñas este mes
- ✅ Distribución visual con barras de progreso
- ✅ Widget de reseñas recientes (últimas 4)
- ✅ Botón "Ver todas las reseñas" que mantiene los filtros

### 6. Seeder

**Archivo:** `database/seeders/ResenasSeeder.php`

**Características:**
- Genera entre 5-20 reseñas por establecimiento
- Distribución realista: 60% 5★, 20% 4★, 10% 3★, 7% 2★, 3% 1★
- 20 nombres de clientes mexicanos
- Comentarios contextuales según puntuación
- Fechas aleatorias en los últimos 6 meses
- Actualiza automáticamente `valoracion_promedio` y `total_resenas` del establecimiento

---

## 🚀 Pasos para Activar el Sistema

### Paso 1: Habilitar SQLite en PHP

El error `could not find driver` significa que las extensiones SQLite no están habilitadas.

**En Windows con Laragon:**

1. Abre el archivo `php.ini`:
   - Laragon → Menú → PHP → php.ini
   - O busca: `C:\laragon\bin\php\php-8.x.x\php.ini`

2. Busca las siguientes líneas y elimina el `;` al inicio:
   ```ini
   ;extension=pdo_sqlite
   ;extension=sqlite3
   ```

   Deben quedar así:
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```

3. Guarda el archivo y **reinicia Laragon** o el servicio Apache

4. Verifica que se habilitó:
   ```bash
   php -m | grep -i sqlite
   ```

   Debe mostrar:
   ```
   pdo_sqlite
   sqlite3
   ```

### Paso 2: Ejecutar las Migraciones

```bash
cd web_laravel
php artisan migrate --force
```

Esto creará la tabla `resenas` en la base de datos.

### Paso 3: Generar Datos de Prueba

```bash
php artisan db:seed --class=ResenasSeeder
```

Esto generará reseñas para todos tus establecimientos existentes.

### Paso 4: Iniciar el Servidor

```bash
php artisan serve
```

Abre en tu navegador: `http://localhost:8000/calificaciones`

---

## 📊 Uso del Sistema

### Ver Calificaciones

1. Inicia sesión con tu cuenta
2. Ve a **Calificaciones** en el menú principal
3. Verás:
   - Estadísticas generales (promedio, total, mes actual)
   - Distribución de calificaciones con barras
   - Últimas 4 reseñas recientes

### Filtrar Calificaciones

**Filtro 1: Por Establecimiento**
- Selecciona un establecimiento del dropdown
- Se filtrará automáticamente

**Filtro 2: Por Puntuación**
- Selecciona de 1 a 5 estrellas
- Mostrará solo reseñas con esa puntuación

**Filtro 3: Ordenamiento**
- **Más recientes** - Las últimas primero
- **Más antiguas** - Las primeras primero
- **Mejor calificadas** - 5★ primero
- **Peor calificadas** - 1★ primero

### Ver Todas las Reseñas

Haz clic en el botón **"Ver todas las reseñas"** para ir a una vista completa con paginación de 20 reseñas por página.

---

## 🎨 Características de la Interfaz

### Respeta el Diseño Original

- ✅ Mantiene el estilo naranja/orange del tema
- ✅ Cards con borde naranja de 4px
- ✅ Gradientes azules en barras de distribución
- ✅ Dark mode completo
- ✅ Iconos y emojis (📍 para ubicación, ★ para estrellas)

### Elementos Dinámicos

**Estadísticas:**
```php
{{ $estadisticas['promedio'] }}  // 4.8
{{ $estadisticas['total'] }}     // 1,243
{{ $estadisticas['este_mes'] }}  // 150
```

**Distribución:**
```php
@foreach($distribucion as $puntuacion => $datos)
    {{ $datos['cantidad'] }}    // Cantidad de reseñas
    {{ $datos['porcentaje'] }}  // Porcentaje para la barra
@endforeach
```

**Reseñas:**
```php
@foreach($resenasRecientes as $resena)
    {{ $resena->cliente_nombre }}
    {{ $resena->fechaFormateada() }}  // 10 de Diciembre, 2025
    {!! $resena->estrellasTexto() !!} // ★★★★★
    {{ $resena->establecimiento->nombre_establecimiento }}
    {{ $resena->comentario }}
@endforeach
```

---

## 🔧 Solución de Problemas

### Error: "could not find driver"
**Causa:** SQLite no está habilitado en PHP
**Solución:** Ver "Paso 1: Habilitar SQLite en PHP"

### Error: "Class 'App\Models\Resena' not found"
**Causa:** Autoload no actualizado
**Solución:**
```bash
composer dump-autoload
```

### No aparecen reseñas
**Causa:** No has ejecutado el seeder o no tienes establecimientos
**Solución:**
```bash
# Primero crea establecimientos en /establecimientos/create
# Luego ejecuta el seeder
php artisan db:seed --class=ResenasSeeder
```

### Los filtros no funcionan
**Causa:** JavaScript deshabilitado o error en el formulario
**Solución:** Verifica que `onchange="this.form.submit()"` esté en cada select

---

## 📝 Próximas Mejoras (Opcionales)

Si quieres extender el sistema:

1. **Responder a reseñas** - Agregar campo `respuesta` a la tabla
2. **Calificar desde app móvil** - Crear API REST
3. **Notificaciones** - Alertar al cliente de nuevas reseñas
4. **Reportar reseñas** - Sistema de moderación
5. **Fotos en reseñas** - Upload de imágenes
6. **Verificación automática** - Verificar si el cliente compró

---

## ✅ Resumen de Cambios

| Archivo | Acción | Estado |
|---------|--------|--------|
| `database/migrations/2025_12_10_000001_create_resenas_table.php` | Creado | ✅ |
| `app/Models/Resena.php` | Creado | ✅ |
| `app/Models/Establecimientos.php` | Modificado (agregada relación) | ✅ |
| `app/Http/Controllers/CalificacionController.php` | Creado | ✅ |
| `routes/web.php` | Modificado (líneas 131-134) | ✅ |
| `resources/views/calificaciones/index.blade.php` | Reescrito completo | ✅ |
| `database/seeders/ResenasSeeder.php` | Creado | ✅ |

---

## 🎯 Resultado Final

Una vez completados los pasos:

1. ✅ Vista de calificaciones completamente funcional
2. ✅ 3 filtros dinámicos (sin duplicados)
3. ✅ Estadísticas en tiempo real
4. ✅ Distribución visual con barras
5. ✅ Widget de reseñas recientes
6. ✅ Sistema de paginación
7. ✅ Datos de prueba realistas
8. ✅ Diseño respetando el tema original

**¡Listo para usar!** 🚀
