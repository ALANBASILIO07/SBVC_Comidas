# Configuración de Google Maps para el Formulario de Establecimientos

## Problema Actual
El formulario de creación de establecimientos requiere seleccionar una ubicación en el mapa de Google Maps, pero falta la configuración de la API key.

## Solución 1: Configurar Google Maps API (RECOMENDADA)

### Paso 1: Obtener una API Key de Google Maps

1. **Ir a Google Cloud Console**
   - Visita: https://console.cloud.google.com/

2. **Crear un proyecto nuevo** (si no tienes uno)
   - Haz clic en el menú desplegable del proyecto (parte superior)
   - Clic en "Nuevo proyecto"
   - Nombre: "SBVC_Comidas"
   - Clic en "Crear"

3. **Habilitar las APIs necesarias**
   - Ve a: "APIs y servicios" > "Biblioteca"
   - Busca y habilita las siguientes APIs:
     * **Maps JavaScript API** (para mostrar el mapa)
     * **Geocoding API** (para convertir direcciones a coordenadas)
     * **Places API** (para autocompletar direcciones)

4. **Crear credenciales (API Key)**
   - Ve a: "APIs y servicios" > "Credenciales"
   - Clic en "+ CREAR CREDENCIALES" > "Clave de API"
   - Copia la API key generada

5. **Restringir la API Key (IMPORTANTE para seguridad)**
   - Edita la API key recién creada
   - En "Restricciones de la aplicación":
     * Selecciona "Referentes HTTP (sitios web)"
     * Agrega tus dominios permitidos:
       ```
       http://localhost/*
       http://127.0.0.1/*
       https://tudominio.com/*
       ```
   - En "Restricciones de API":
     * Selecciona "Restringir clave"
     * Marca las 3 APIs habilitadas anteriormente
   - Guardar

### Paso 2: Configurar en Laravel

1. **Agregar al archivo `.env`**
   ```env
   GOOGLE_MAPS_API_KEY=TU_API_KEY_AQUI
   ```

2. **Verificar que el código lo usa correctamente** (ya está configurado en `create.blade.php`)
   ```blade
   <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
   ```

### Paso 3: Probar

1. Ejecuta el servidor:
   ```bash
   php artisan serve
   ```

2. Ve a: http://localhost:8000/establecimientos/create

3. El mapa debe cargar automáticamente centrado en México

4. Puedes:
   - Buscar direcciones en el campo "Buscar dirección"
   - Hacer clic en el mapa para colocar un marcador
   - Arrastrar el marcador a otra ubicación
   - Los campos de dirección se llenarán automáticamente

---

## Solución 2: Usar OpenStreetMap / Leaflet (GRATIS, sin API key)

Si prefieres no usar Google Maps, puedo implementar una alternativa completamente gratuita con Leaflet y OpenStreetMap.

**Ventajas:**
- 100% gratuito
- Sin límites de uso
- Sin necesidad de API keys
- Código abierto

**Desventajas:**
- Geocodificación menos precisa en México
- Sin autocompletar tan robusto como Google Places

---

## Costos de Google Maps

### Plan Gratuito (Suficiente para desarrollo y pequeños proyectos)
- **$200 USD de crédito mensual GRATIS**
- Maps JavaScript API: **28,000 cargas de mapa gratis/mes**
- Geocoding API: **40,000 peticiones gratis/mes**
- Places API Autocomplete: **1,000 peticiones gratis/mes**

### Cálculo aproximado para tu proyecto:
- Si tienes 100 usuarios creando 2 establecimientos/mes = 200 cargas de mapa
- **MUY por debajo del límite gratuito**

---

## Recomendación Final

**Para tu proyecto, usa Google Maps (Solución 1)** porque:
1. Es gratis para tu volumen de uso
2. Mejor experiencia de usuario
3. Geocodificación muy precisa en México
4. Ya está implementado en tu código

Solo necesitas agregar la API key al archivo `.env`
