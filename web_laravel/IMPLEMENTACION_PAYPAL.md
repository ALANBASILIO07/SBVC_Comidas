# Implementación de PayPal - Sistema de Pagos

## Fecha: 2025-11-27

---

## 📋 ARCHIVOS CREADOS

### 1. Backend

#### `app/Services/PayPalService.php` ✅
Servicio principal para integración con API de PayPal
- Autenticación con OAuth 2.0
- Creación de órdenes
- Captura de pagos
- Consulta de órdenes

#### `app/Http/Controllers/PayPalController.php` ✅
Controlador para manejo de pagos
- `create()` - Crea orden de pago
- `capture()` - Captura pago aprobado
- Validaciones de seguridad
- Actualización de plan del cliente

#### `config/paypal.php` ✅
Configuración de PayPal
- Modo (sandbox/live)
- Credenciales (client_id, secret)
- Moneda (MXN)

### 2. Frontend

#### `resources/js/plans-checkout.js` ✅
JavaScript para checkout de planes
- Selección visual de planes
- Integración con PayPal SDK
- Validaciones de cliente
- SweetAlert2 para notificaciones

### 3. Rutas

#### Agregadas en `routes/web.php` ✅
```php
Route::prefix('paypal')->name('paypal.')->group(function () {
    Route::post('/create-order', [PayPalController::class, 'create']);
    Route::post('/orders/{orderId}/capture', [PayPalController::class, 'capture']);
});
```

---

## ⚙️ CONFIGURACIÓN NECESARIA

### 1. Variables de Entorno (.env)

Agrega estas variables a tu archivo `.env`:

```env
# PayPal Configuration
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=tu_client_id_aqui
PAYPAL_CLIENT_SECRET=tu_secret_aqui
PAYPAL_CURRENCY=MXN
```

#### Obtener Credenciales de PayPal

**Para Pruebas (Sandbox):**
1. Ve a https://developer.paypal.com
2. Inicia sesión con tu cuenta PayPal
3. Ve a "Dashboard" > "Apps & Credentials"
4. En "Sandbox", click en "Create App"
5. Copia el **Client ID** y **Secret**

**Para Producción (Live):**
1. Mismo proceso pero en la pestaña "Live"
2. Cambia `PAYPAL_MODE=live` en el .env

###2. Limpiar Caché de Laravel

Después de agregar las variables al .env:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 3. Compilar Assets (JavaScript)

Si usas Vite:

```bash
npm install
npm run build
```

O en modo desarrollo:

```bash
npm run dev
```

---

## 🎨 ACTUALIZAR VISTA DE SUBSCRIPCIÓN

### Archivo: `resources/views/subscripcion/index.blade.php`

Necesitas agregar:

1. **PayPal SDK en el head:**
```blade
@push('head')
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency', 'MXN') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
```

2. **Cards de planes con clase `plan-card`:**
```blade
<div class="plan-card cursor-pointer transition-all duration-300" data-plan="estandar">
    <input type="radio" name="plan" value="estandar" class="hidden" />

    <!-- Contenido de la card -->
    <h3>Plan Estándar</h3>
    <p class="text-3xl font-bold">$299<span class="text-sm">/mes</span></p>

    <ul>
        <li>1 Establecimiento</li>
        <li>Promociones ilimitadas</li>
        <li>Estadísticas básicas</li>
        <li>Soporte prioritario</li>
    </ul>
</div>
```

3. **Contenedor para botones de PayPal:**
```blade
<div id="paypal-button-container" class="mt-8"></div>
```

4. **Inicializar JavaScript al final:**
```blade
@push('scripts')
<script type="module">
    import initPlansCheckout from '{{ asset('js/plans-checkout.js') }}';

    initPlansCheckout({
        createOrderUrl: '{{ route('paypal.create') }}',
        captureOrderUrl: '{{ route('paypal.capture') }}',
        csrfToken: '{{ csrf_token() }}'
    });
</script>
@endpush
```

---

## 💰 PRECIOS DE LOS PLANES

Definidos en `PayPalController.php`:

| Plan | Precio | ID |
|------|--------|-----|
| Básico | $0.00 MXN | `basico` |
| Estándar | $299.00 MXN | `estandar` |
| Premium | $599.00 MXN | `premium` |

---

## 🔄 FLUJO DE PAGO

### 1. Usuario Selecciona Plan
- Click en card del plan
- Se resalta visualmente
- Se habilitan botones de PayPal

### 2. Usuario Click en "PayPal"
- Validación: debe tener plan seleccionado
- Se crea orden en backend (`PayPalController@create`)
- Se abre ventana de PayPal

### 3. Usuario Aprueba Pago en PayPal
- PayPal redirige de vuelta
- Se captura el pago (`PayPalController@capture`)
- Se actualiza plan del cliente en BD

### 4. Confirmación
- SweetAlert muestra éxito
- Redirige al dashboard
- Cliente tiene plan actualizado

---

## 🔐 SEGURIDAD

### Validaciones Implementadas

1. **Autenticación:**
   - Usuario debe estar autenticado
   - Usuario debe tener cliente asociado

2. **Autorización:**
   - Solo el cliente puede pagar su propio plan
   - Validación de referencia en captura

3. **Validaciones de Orden:**
   - Plan debe ser válido (basico/estandar/premium)
   - Orden debe estar en estado APPROVED
   - Captura debe ser COMPLETED

4. **Protección CSRF:**
   - Token CSRF en todas las peticiones
   - Validado por middleware de Laravel

### Logs de Seguridad

Todos los errores se registran en `storage/logs/laravel.log`:
- Errores de autenticación PayPal
- Fallos en creación de orden
- Errores en captura
- Discrepancias de cliente/usuario

---

## 🧪 PRUEBAS

### Modo Sandbox (Pruebas)

1. **Cuenta de Prueba:**
   - Ve a https://developer.paypal.com
   - Sección "Sandbox" > "Accounts"
   - Usa cuentas de prueba o crea nuevas

2. **Tarjetas de Prueba:**
   PayPal genera automáticamente cuentas con saldo virtual

3. **Flujo de Prueba:**
   ```
   Seleccionar plan → PayPal (sandbox) → Login con cuenta de prueba →
   Aprobar pago → Confirmación
   ```

### Datos de Prueba

```
Email: sb-xxxxxx@personal.example.com (generado por PayPal)
Password: (generado por PayPal)
```

---

## 📊 TABLA CLIENTES

Asegúrate de que la tabla `clientes` tenga la columna `plan`:

```sql
ALTER TABLE clientes ADD COLUMN plan VARCHAR(20) DEFAULT 'basico';
```

O crear migración:

```bash
php artisan make:migration add_plan_to_clientes_table
```

```php
public function up()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->string('plan')->default('basico')->after('telefono');
    });
}
```

---

## 🐛 DEBUGGING

### Si no funciona PayPal:

1. **Verificar Credenciales:**
```bash
php artisan tinker
>>> config('paypal.client_id')
>>> config('paypal.mode')
```

2. **Ver Logs:**
```bash
tail -f storage/logs/laravel.log
```

3. **Consola del Navegador:**
- Abrir DevTools (F12)
- Ver errores JavaScript
- Network tab para peticiones

4. **Verificar PayPal SDK:**
```javascript
console.log(window.paypal);
// Debe mostrar objeto PayPal
```

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [ ] Crear cuenta en PayPal Developer
- [ ] Obtener Client ID y Secret (Sandbox)
- [ ] Agregar variables a `.env`
- [ ] Ejecutar `php artisan config:clear`
- [ ] Actualizar vista de subscripción
- [ ] Compilar assets: `npm run build`
- [ ] Verificar tabla `clientes` tiene columna `plan`
- [ ] Probar flujo completo en sandbox
- [ ] Verificar logs no tienen errores
- [ ] Antes de producción: obtener credenciales Live
- [ ] Cambiar `PAYPAL_MODE=live` en producción

---

## 🚀 IR A PRODUCCIÓN

### Pasos Finales:

1. **Obtener Credenciales Live:**
   - Dashboard PayPal > Apps & Credentials > Live
   - Crear app de producción
   - Copiar Client ID y Secret

2. **Actualizar .env:**
   ```env
   PAYPAL_MODE=live
   PAYPAL_CLIENT_ID=tu_live_client_id
   PAYPAL_CLIENT_SECRET=tu_live_secret
   ```

3. **Limpiar Caché:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Verificar SSL:**
   - PayPal requiere HTTPS en producción
   - Asegúrate de tener certificado SSL

---

## 📞 SOPORTE

### Recursos PayPal:
- Documentación: https://developer.paypal.com/docs
- Dashboard: https://developer.paypal.com/dashboard
- Sandbox: https://www.sandbox.paypal.com

### Logs del Sistema:
```bash
storage/logs/laravel.log
```

### Debugging:
```php
// En PayPalController o PayPalService
Log::info('PayPal debug', ['data' => $data]);
```

---

## 🎉 ESTADO

**Implementación:** ✅ COMPLETA

**Archivos Creados:** 4
**Rutas Agregadas:** 2
**Configuración:** Lista

**Próximo Paso:** Configurar credenciales de PayPal en .env y probar

---

**Documentado por:** Claude Code
**Fecha:** 2025-11-27
**Sistema:** SBVC Comidas - Laravel 12.38.1
**Versión PayPal SDK:** v2
