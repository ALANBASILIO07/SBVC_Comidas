# 📘 GUÍA DE IMPLEMENTACIÓN Y USO DE LA API REST - SBVC_Comidas

**Versión**: 1.0
**Fecha**: 16 de Diciembre de 2024
**Elaboró**: Claude Code Assistant
**Autorizó**: Alan Basilio

---

## 📋 ÍNDICE

1. [Resumen de Cambios](#resumen-de-cambios)
2. [Estructura de la API](#estructura-de-la-api)
3. [Endpoints Disponibles](#endpoints-disponibles)
4. [Pruebas de la API](#pruebas-de-la-api)
5. [Integración con Flutter](#integración-con-flutter)
6. [Guía de Commit a GitHub](#guía-de-commit-a-github)
7. [Solución de Problemas](#solución-de-problemas)

---

## 🎯 RESUMEN DE CAMBIOS

### ✅ Implementaciones Completadas

#### 1. **Laravel Sanctum Instalado y Configurado**
- ✅ Paquete `laravel/sanctum` v4.2.1 instalado
- ✅ Migración `personal_access_tokens` ejecutada
- ✅ Modelo `User.php` actualizado con trait `HasApiTokens`
- ✅ Archivo `config/sanctum.php` publicado

#### 2. **Controladores API Creados** (con prologues estandarizados)
```
web_laravel/app/Http/Controllers/Api/
├── AuthApiController.php              (184 líneas)
├── EstablecimientoApiController.php   (203 líneas)
├── CategoriaApiController.php         (105 líneas)
└── ResenaApiController.php            (215 líneas)
```

#### 3. **Archivo routes/api.php Creado**
- ✅ 17 endpoints REST definidos
- ✅ Autenticación con Sanctum
- ✅ Throttling configurado (6 intentos/minuto para registro, 10 para login)
- ✅ Prologues estandarizados siguiendo el proyecto Estacionamiento

#### 4. **Bootstrap Actualizado**
- ✅ `bootstrap/app.php` configurado para cargar rutas API

---

## 🏗️ ESTRUCTURA DE LA API

### URL Base
```
http://localhost:8000/api
```

### Autenticación
La API utiliza **Laravel Sanctum** con tokens Bearer:

```http
Authorization: Bearer {token}
```

### Formato de Respuestas

**Respuesta Exitosa:**
```json
{
  "data": { ... },
  "meta": {
    "total": 34,
    "current_page": 1
  }
}
```

**Respuesta de Error:**
```json
{
  "message": "Error de validación",
  "errors": {
    "email": ["El email ya está registrado"]
  }
}
```

---

## 🔌 ENDPOINTS DISPONIBLES

### 🔐 **AUTENTICACIÓN** (`/api/auth`)

#### 1. Registro de Usuario
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta (201):**
```json
{
  "message": "Usuario registrado exitosamente",
  "token": "1|abc123...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "tiene_cliente": false
  }
}
```

#### 2. Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Respuesta (200):**
```json
{
  "message": "Autenticación exitosa",
  "token": "2|xyz789...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "tiene_cliente": false,
    "cliente_info": null
  }
}
```

#### 3. Obtener Usuario Actual
```http
GET /api/auth/me
Authorization: Bearer {token}
```

**Respuesta (200):**
```json
{
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "tiene_cliente": false,
    "cliente_info": null,
    "avatar_url": "https://ui-avatars.com/api/?name=JP...",
    "iniciales": "JP"
  }
}
```

#### 4. Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Respuesta (200):**
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

---

### 📂 **CATEGORÍAS** (`/api/categorias`)

#### 1. Listar Todas las Categorías
```http
GET /api/categorias
```

**Respuesta (200):**
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Comida Mexicana",
      "tipo_establecimiento": "Restaurante",
      "activo": true
    },
    ...
  ],
  "agrupadas": {
    "Restaurante": [...],
    "Bar": [...],
    "Food Truck": [...]
  },
  "meta": {
    "total": 34
  }
}
```

#### 2. Categorías Agrupadas por Tipo
```http
GET /api/categorias/agrupadas
```

#### 3. Detalles de una Categoría
```http
GET /api/categorias/{id}
```

---

### 🏪 **ESTABLECIMIENTOS** (`/api/establecimientos`)

#### 1. Listar Establecimientos (con filtros)
```http
GET /api/establecimientos?categoria_id=1&min_rating=4&per_page=20
```

**Parámetros de Query:**
- `categoria_id` - Filtrar por categoría
- `tipo` - Filtrar por tipo (formal/informal)
- `min_rating` - Valoración mínima (1-5)
- `min_confianza` - Grado de confianza mínimo (0-100)
- `metodo_pago` - Filtrar por método de pago (efectivo, tarjeta, transferencia)
- `facturacion` - Filtrar por facturación (true/false)
- `order_by` - Ordenar por campo (created_at, valoracion)
- `order_direction` - Dirección (asc, desc)
- `per_page` - Elementos por página (default: 15)

**Respuesta (200):**
```json
{
  "data": [
    {
      "id": 1,
      "nombre_establecimiento": "Tacos El Güero",
      "lat": 19.432608,
      "lng": -99.133209,
      "valoracion_promedio": 4.5,
      "categoria": {
        "id": 1,
        "nombre": "Comida Mexicana"
      }
    },
    ...
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73
  }
}
```

#### 2. Detalles de un Establecimiento
```http
GET /api/establecimientos/{id}
```

#### 3. Establecimientos Cercanos (Geolocalización)
```http
GET /api/establecimientos/cercanos/buscar?lat=19.432608&lng=-99.133209&radio_km=5
```

**Parámetros Requeridos:**
- `lat` - Latitud (-90 a 90)
- `lng` - Longitud (-180 a 180)
- `radio_km` - Radio de búsqueda en kilómetros (default: 5, max: 50)

#### 4. Establecimientos por Categoría
```http
GET /api/establecimientos/categoria/{categoriaId}
```

#### 5. Buscar Establecimientos
```http
GET /api/establecimientos/buscar/termino?q=tacos
```

---

### ⭐ **RESEÑAS** (`/api/resenas`)

#### 1. Obtener Reseñas de un Establecimiento
```http
GET /api/resenas/establecimiento/{id}
```

#### 2. Crear Reseña (requiere autenticación)
```http
POST /api/resenas
Authorization: Bearer {token}
Content-Type: application/json

{
  "establecimiento_id": 1,
  "calificacion": 5,
  "comentario": "Excelente servicio y comida deliciosa"
}
```

#### 3. Actualizar Reseña (requiere autenticación)
```http
PUT /api/resenas/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "calificacion": 4,
  "comentario": "Muy buena experiencia"
}
```

#### 4. Eliminar Reseña (requiere autenticación)
```http
DELETE /api/resenas/{id}
Authorization: Bearer {token}
```

---

### ✅ **HEALTH CHECK**

```http
GET /api/health
```

**Respuesta:**
```json
{
  "status": "ok",
  "service": "SBVC_Comidas API",
  "version": "1.0",
  "timestamp": "2025-12-16T21:36:17.973977Z"
}
```

---

## 🧪 PRUEBAS DE LA API

### Método 1: cURL (Línea de Comandos)

#### 1. Probar Health Check
```bash
curl http://localhost:8000/api/health
```

#### 2. Registrar Usuario
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### 3. Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

#### 4. Obtener Categorías
```bash
curl http://localhost:8000/api/categorias
```

#### 5. Obtener Establecimientos
```bash
curl http://localhost:8000/api/establecimientos
```

#### 6. Crear Reseña (con token)
```bash
curl -X POST http://localhost:8000/api/resenas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "establecimiento_id": 1,
    "calificacion": 5,
    "comentario": "Excelente lugar"
  }'
```

### Método 2: Postman

1. **Importar Colección**:
   - Crear nueva colección "SBVC_Comidas API"
   - Agregar variable de entorno `base_url` = `http://localhost:8000/api`
   - Agregar variable `token` para autenticación

2. **Crear Requests**:
   - Health Check: `GET {{base_url}}/health`
   - Register: `POST {{base_url}}/auth/register`
   - Login: `POST {{base_url}}/auth/login`
   - Categorías: `GET {{base_url}}/categorias`
   - Establecimientos: `GET {{base_url}}/establecimientos`

3. **Configurar Autenticación**:
   - Después de login, copiar el `token` de la respuesta
   - En requests que requieren auth, agregar header:
     ```
     Authorization: Bearer {{token}}
     ```

### Método 3: Thunder Client (VS Code)

1. Instalar extensión "Thunder Client" en VS Code
2. Crear nueva colección
3. Agregar requests con los endpoints listados arriba
4. Para auth, usar la pestaña "Auth" → "Bearer Token"

---

## 📱 INTEGRACIÓN CON FLUTTER

### 1. Configurar BaseURL

Crear archivo `lib/core/constants/api_constants.dart`:

```dart
class ApiConstants {
  // Desarrollo local
  static const String baseUrl = 'http://10.0.2.2:8000/api';  // Android Emulator
  // static const String baseUrl = 'http://localhost:8000/api';  // iOS Simulator

  // Producción (actualizar cuando se despliegue)
  // static const String baseUrl = 'https://tu-dominio.com/api';

  // Endpoints
  static const String auth = '/auth';
  static const String categorias = '/categorias';
  static const String establecimientos = '/establecimientos';
  static const String resenas = '/resenas';
}
```

### 2. Crear Servicio de API

Crear `lib/services/api_service.dart`:

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants/api_constants.dart';

class ApiService {
  static String? _token;

  // Headers por defecto
  static Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  // Guardar token
  static Future<void> saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Cargar token
  static Future<void> loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }

  // Eliminar token
  static Future<void> clearToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // LOGIN
  static Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.auth}/login'),
      headers: _headers,
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      await saveToken(data['token']);
      return data;
    } else {
      throw Exception('Error en login: ${response.body}');
    }
  }

  // OBTENER CATEGORÍAS
  static Future<List<dynamic>> getCategorias() async {
    final response = await http.get(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.categorias}'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    } else {
      throw Exception('Error al obtener categorías');
    }
  }

  // OBTENER ESTABLECIMIENTOS
  static Future<Map<String, dynamic>> getEstablecimientos({
    int? categoriaId,
    double? minRating,
    int page = 1,
  }) async {
    final queryParams = {
      if (categoriaId != null) 'categoria_id': categoriaId.toString(),
      if (minRating != null) 'min_rating': minRating.toString(),
      'page': page.toString(),
    };

    final uri = Uri.parse('${ApiConstants.baseUrl}${ApiConstants.establecimientos}')
        .replace(queryParameters: queryParams);

    final response = await http.get(uri, headers: _headers);

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al obtener establecimientos');
    }
  }

  // CREAR RESEÑA
  static Future<Map<String, dynamic>> crearResena({
    required int establecimientoId,
    required int calificacion,
    String? comentario,
  }) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.resenas}'),
      headers: _headers,
      body: jsonEncode({
        'establecimiento_id': establecimientoId,
        'calificacion': calificacion,
        if (comentario != null) 'comentario': comentario,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al crear reseña: ${response.body}');
    }
  }
}
```

### 3. Ejemplo de Uso en Flutter

```dart
// En initState de tu widget
@override
void initState() {
  super.initState();
  _cargarDatos();
}

Future<void> _cargarDatos() async {
  try {
    // Cargar token guardado
    await ApiService.loadToken();

    // Obtener categorías
    final categorias = await ApiService.getCategorias();
    setState(() {
      _categorias = categorias;
    });

    // Obtener establecimientos
    final response = await ApiService.getEstablecimientos(
      minRating: 4.0,
      page: 1,
    );
    setState(() {
      _establecimientos = response['data'];
    });
  } catch (e) {
    print('Error: $e');
  }
}
```

---

## 🚀 GUÍA DE COMMIT A GITHUB

### Paso 1: Verificar Cambios
```bash
cd C:\laragon\www\SBVC_Comidas
git status
```

### Paso 2: Agregar Archivos al Staging
```bash
git add web_laravel/app/Models/User.php
git add web_laravel/app/Http/Controllers/Api/
git add web_laravel/routes/api.php
git add web_laravel/config/sanctum.php
git add web_laravel/database/migrations/2025_12_16_153127_create_personal_access_tokens_table.php
git add web_laravel/bootstrap/app.php
git add web_laravel/composer.json
git add web_laravel/composer.lock
git add GUIA_API_REST.md
```

### Paso 3: Crear Commit
```bash
git commit -m "feat: implement REST API with Laravel Sanctum

- Instalar y configurar Laravel Sanctum v4.2.1
- Crear controladores API con prologues estandarizados:
  * AuthApiController: registro, login, logout, perfil
  * EstablecimientoApiController: CRUD, búsqueda, geolocalización
  * CategoriaApiController: listado y agrupación
  * ResenaApiController: CRUD de reseñas
- Crear routes/api.php con 17 endpoints REST
- Agregar trait HasApiTokens al modelo User
- Configurar autenticación con tokens Bearer
- Implementar throttling (6/min registro, 10/min login)
- Agregar health check endpoint
- Actualizar bootstrap/app.php para cargar rutas API
- Crear guía de documentación y uso de la API

🤖 Generated with Claude Code

Co-Authored-By: Claude <noreply@anthropic.com>"
```

### Paso 4: Push a GitHub
```bash
git push origin develop
```

### Paso 5: Verificar en GitHub
1. Ir a https://github.com/tu-usuario/SBVC_Comidas
2. Verificar que el commit aparezca en la rama `develop`
3. Revisar los archivos modificados

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Problema 1: "Route [api] not defined"

**Solución**: Verificar que `bootstrap/app.php` tenga la línea:
```php
api: __DIR__.'/../routes/api.php',
```

### Problema 2: "Class 'Laravel\Sanctum\HasApiTokens' not found"

**Solución**: Ejecutar:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Problema 3: Endpoints no responden

**Solución**: Verificar que el servidor esté corriendo:
```bash
php artisan serve
```

### Problema 4: Error 401 en endpoints protegidos

**Solución**: Verificar que el header de autenticación esté correcto:
```
Authorization: Bearer {token_completo}
```

### Problema 5: CORS errors desde Flutter

**Solución**: Instalar Laravel CORS:
```bash
composer require fruitcake/laravel-cors
```

Agregar a `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN COMPLETADA

- [x] Laravel Sanctum instalado
- [x] Migración `personal_access_tokens` ejecutada
- [x] Modelo `User` con trait `HasApiTokens`
- [x] Controlador `AuthApiController` creado
- [x] Controlador `EstablecimientoApiController` creado
- [x] Controlador `CategoriaApiController` creado
- [x] Controlador `ResenaApiController` creado
- [x] Archivo `routes/api.php` creado
- [x] Bootstrap configurado para cargar rutas API
- [x] Prologues agregados al modelo User
- [x] Prologues agregados a todos los controladores API
- [x] Prólogo agregado a routes/api.php
- [x] Health check endpoint implementado
- [x] Pruebas básicas con cURL realizadas
- [x] Documentación completa generada

---

## 📞 SOPORTE

Para dudas o problemas con la API, contactar a:
- **Alan Basilio** - alan.basilio@example.com
- **Maileth Patiño** - maileth.patino@example.com

---

**Generado con Claude Code - 16 de Diciembre de 2024**
