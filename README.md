# SBVC_Comidas - Sistema de Búsqueda y Valoración de Comida

Sistema completo de gestión de establecimientos de comida con aplicación móvil Flutter y backend Laravel.

## 📋 Descripción

SBVC_Comidas es una plataforma que permite a los usuarios:
- 🔍 Buscar restaurantes y establecimientos de comida cercanos
- ⭐ Valorar y reseñar establecimientos
- 🗺️ Visualizar ubicaciones en mapa interactivo
- 🎯 Filtrar por categoría, precio, calificación y más
- 💳 Gestionar planes de suscripción (básico/premium)

## 🏗️ Arquitectura del Proyecto

```
SBVC_Comidas/
├── web_laravel/          # Backend Laravel 11 + API REST
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── Api/      # Controladores API REST
│   │   │   └── ...       # Controladores web
│   │   └── Models/       # Modelos Eloquent
│   ├── database/
│   │   └── migrations/   # Migraciones de BD
│   ├── routes/
│   │   ├── api.php       # Rutas API REST
│   │   └── web.php       # Rutas web
│   └── database.sqlite   # Base de datos SQLite
│
└── app_flutter/          # App móvil Flutter
    └── lib/
        ├── main.dart
        ├── core/
        │   ├── constants/    # Colores, API URLs
        │   └── models/       # Modelos de datos
        ├── services/         # Servicios HTTP
        └── features/         # Pantallas por feature
            ├── auth/
            ├── map/
            └── profile/
```

## 🚀 Tecnologías Utilizadas

### Backend (Laravel 11)
- **Framework**: Laravel 11.35.1
- **Base de Datos**: SQLite
- **Autenticación API**: Laravel Sanctum 4.2.1
- **PHP**: 8.2+

### Frontend (Flutter)
- **Framework**: Flutter 3.7+
- **Lenguaje**: Dart 3.0+
- **Mapa**: Google Maps Flutter
- **HTTP**: http package
- **Persistencia**: shared_preferences
- **UI**: Material Design 3

## 📦 Instalación

### Requisitos Previos
- PHP 8.2 o superior
- Composer
- Flutter SDK 3.7+
- Laragon (opcional, para desarrollo en Windows)

### Backend Laravel

1. **Clonar el repositorio**
```bash
cd C:\laragon\www
git clone <repository-url> SBVC_Comidas
cd SBVC_Comidas/web_laravel
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Ejecutar migraciones**
```bash
php artisan migrate --seed
```

5. **Iniciar servidor**
```bash
php artisan serve
```

El servidor estará disponible en `http://localhost:8000`

### App Flutter

1. **Navegar al directorio**
```bash
cd C:\laragon\www\SBVC_Comidas\app_flutter
```

2. **Instalar dependencias**
```bash
flutter pub get
```

3. **Configurar API URL**
Editar `lib/core/constants/api_constants.dart`:
```dart
static const String baseUrlDevelopment = 'http://10.0.2.2:8000'; // Para emulador Android
// static const String baseUrlDevelopment = 'http://localhost:8000'; // Para web
```

4. **Ejecutar aplicación**
```bash
flutter run
```

## 🔌 API REST Endpoints

### Autenticación
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/login` - Iniciar sesión
- `GET /api/auth/me` - Obtener usuario actual (requiere auth)
- `POST /api/auth/logout` - Cerrar sesión (requiere auth)

### Categorías
- `GET /api/categorias` - Listar todas las categorías
- `GET /api/categorias/agrupadas` - Categorías agrupadas por tipo
- `GET /api/categorias/{id}` - Obtener categoría específica

### Establecimientos
- `GET /api/establecimientos` - Listar establecimientos (con filtros)
- `GET /api/establecimientos/{id}` - Obtener establecimiento específico
- `GET /api/establecimientos/cercanos/buscar` - Buscar por geolocalización
- `GET /api/establecimientos/buscar/termino` - Buscar por término

**Filtros disponibles:**
- `categoria_id` - Filtrar por categoría
- `tipo` - Tipo de establecimiento
- `min_rating` - Calificación mínima
- `metodo_pago` - Método de pago
- `facturacion` - Si ofrece facturación (0/1)
- `page` - Número de página
- `per_page` - Resultados por página

### Reseñas
- `GET /api/resenas/establecimiento/{id}` - Obtener reseñas de un establecimiento
- `POST /api/resenas` - Crear reseña (requiere auth)
- `PUT /api/resenas/{id}` - Actualizar reseña (requiere auth)
- `DELETE /api/resenas/{id}` - Eliminar reseña (requiere auth)

### Health Check
- `GET /api/health` - Verificar estado de la API

## 🎨 Paleta de Colores Oficial

Según Manual_PRO_Flutter_V3.7.pdf:

- **Primary (Naranja)**: `#DE6A01`
- **Secondary (Azul)**: `#241F78`
- **Secondary Dark**: `#1A0D5A`
- **Success (Verde)**: `#42A958`
- **Error (Rojo)**: `#ED0000`

## 📱 Funcionalidades de la App

### Autenticación
- Login con email y contraseña
- Registro de nuevos usuarios
- Gestión de sesión con Sanctum tokens

### Búsqueda de Restaurantes
- Mapa interactivo con Google Maps
- Búsqueda por texto
- Filtros avanzados:
  - Calificación mínima
  - Tipo de negocio (formal/informal)
  - Rango de precios
  - Solo abiertos ahora

### Detalles de Restaurante
- Información completa del establecimiento
- Horarios de atención
- Métodos de pago aceptados
- Comidas disponibles
- Sistema de calificación con estrellas

### Perfil de Usuario
- Edición de datos personales
- Gestión de plan (básico/premium)
- Configuración de la cuenta

## 🗂️ Base de Datos

### Tablas Principales

- `users` - Usuarios del sistema
- `clientes` - Clientes propietarios de establecimientos
- `categorias` - Categorías de establecimientos
- `establecimientos` - Establecimientos de comida
- `resenas` - Reseñas y calificaciones
- `promociones` - Ofertas y promociones
- `banners` - Banners publicitarios
- `personal_access_tokens` - Tokens de autenticación API

## 📄 Documentación Adicional

- **GUIA_API_REST.md** - Guía completa de la API con ejemplos de uso
- **Manual_PRO_Flutter_V3.7.pdf** - Estándares de programación Flutter
- **Manual_de_Usuario_web.pdf** - Manual de usuario para la versión web

## 🔒 Seguridad

- Autenticación mediante Laravel Sanctum
- Tokens de acceso personal
- Validación de datos en todas las peticiones
- Protección CORS configurada
- Rate limiting en endpoints críticos

## 👥 Autores

**Elaborado por:**
- Alan Osvaldo Basilio Delgado
- Maileth Patiño Ensastegui

**Versión:** 1.0
**Fecha de Liberación:** 16/12/2024

## 📝 Licencia

Este proyecto es privado y confidencial. Todos los derechos reservados.

---

## 🆘 Soporte

Para reportar errores o solicitar nuevas funcionalidades, contactar al equipo de desarrollo.

**API Base URL (Desarrollo):** `http://localhost:8000/api`
**API Base URL (Producción):** `https://api.sbvccomidas.com/api`
