/*
* Nombre de la clase         : api_constants.dart
* Descripción de la clase    : Define las constantes de configuración de la API REST para
*                               comunicación entre la app móvil Flutter y el backend Laravel.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Alan Osvaldo Basilio Delgado
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Maileth Patiño Ensastegui
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

/// Constantes de configuración de la API
class ApiConstants {
  // Base URLs por ambiente
  static const String baseUrlProduction = 'https://api.sbvccomidas.com';
  static const String baseUrlDevelopment = 'http://localhost:8000';

  // URL activa (cambiar según ambiente)
  static const String baseUrl = baseUrlDevelopment;
  static const String apiVersion = '/api';
  static const String apiBaseUrl = '$baseUrl$apiVersion';

  // ====================================================================
  // ENDPOINTS DE AUTENTICACIÓN
  // ====================================================================
  static const String authRegister = '$apiBaseUrl/auth/register';
  static const String authLogin = '$apiBaseUrl/auth/login';
  static const String authLogout = '$apiBaseUrl/auth/logout';
  static const String authMe = '$apiBaseUrl/auth/me';

  // ====================================================================
  // ENDPOINTS DE CATEGORÍAS
  // ====================================================================
  static const String categorias = '$apiBaseUrl/categorias';
  static const String categoriasAgrupadas = '$apiBaseUrl/categorias/agrupadas';

  /// Obtener categoría específica por ID
  static String categoriaById(int id) => '$apiBaseUrl/categorias/$id';

  // ====================================================================
  // ENDPOINTS DE ESTABLECIMIENTOS
  // ====================================================================
  static const String establecimientos = '$apiBaseUrl/establecimientos';
  static const String establecimientosCercanos = '$apiBaseUrl/establecimientos/cercanos/buscar';
  static const String establecimientosBuscar = '$apiBaseUrl/establecimientos/buscar/termino';

  /// Obtener establecimiento específico por ID
  static String establecimientoById(int id) => '$apiBaseUrl/establecimientos/$id';

  /// Obtener establecimientos por categoría
  static String establecimientosByCategoria(int categoriaId) =>
      '$apiBaseUrl/establecimientos/categoria/$categoriaId';

  // ====================================================================
  // ENDPOINTS DE RESEÑAS
  // ====================================================================
  static const String resenas = '$apiBaseUrl/resenas';

  /// Obtener reseñas de un establecimiento
  static String resenasByEstablecimiento(int establecimientoId) =>
      '$apiBaseUrl/resenas/establecimiento/$establecimientoId';

  /// Actualizar reseña específica
  static String resenaById(int id) => '$apiBaseUrl/resenas/$id';

  // ====================================================================
  // HEALTH CHECK
  // ====================================================================
  static const String health = '$apiBaseUrl/health';

  // ====================================================================
  // CONFIGURACIÓN HTTP
  // ====================================================================
  static const Duration timeoutDuration = Duration(seconds: 30);
  static const Duration connectionTimeout = Duration(seconds: 15);

  /// Headers comunes para todas las peticiones
  static Map<String, String> get defaultHeaders => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Device-Name': 'flutter-app',
  };

  /// Headers para peticiones autenticadas (agregar token dinámicamente)
  static Map<String, String> authenticatedHeaders(String token) => {
    ...defaultHeaders,
    'Authorization': 'Bearer $token',
  };

  // ====================================================================
  // PARÁMETROS DE QUERY COMUNES
  // ====================================================================

  /// Parámetros para filtrado de establecimientos
  static const String paramCategoria = 'categoria_id';
  static const String paramTipo = 'tipo';
  static const String paramMinRating = 'min_rating';
  static const String paramMetodoPago = 'metodo_pago';
  static const String paramFacturacion = 'facturacion';
  static const String paramPage = 'page';
  static const String paramPerPage = 'per_page';

  /// Parámetros para búsqueda por geolocalización
  static const String paramLatitud = 'lat';
  static const String paramLongitud = 'lng';
  static const String paramRadio = 'radio';

  /// Parámetros para búsqueda por término
  static const String paramTermino = 'termino';

  // ====================================================================
  // CÓDIGOS DE ESTADO HTTP
  // ====================================================================
  static const int statusOk = 200;
  static const int statusCreated = 201;
  static const int statusNoContent = 204;
  static const int statusBadRequest = 400;
  static const int statusUnauthorized = 401;
  static const int statusForbidden = 403;
  static const int statusNotFound = 404;
  static const int statusUnprocessableEntity = 422;
  static const int statusTooManyRequests = 429;
  static const int statusInternalServerError = 500;

  // ====================================================================
  // MENSAJES DE ERROR
  // ====================================================================
  static const String errorNoInternet = 'No hay conexión a internet';
  static const String errorTimeout = 'Tiempo de espera agotado';
  static const String errorServer = 'Error del servidor';
  static const String errorUnauthorized = 'No autorizado. Inicie sesión nuevamente';
  static const String errorNotFound = 'Recurso no encontrado';
  static const String errorGeneral = 'Ocurrió un error inesperado';
}
