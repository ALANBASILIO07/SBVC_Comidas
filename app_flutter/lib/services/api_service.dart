/*
* Nombre de la clase         : api_service.dart
* Descripción de la clase    : Servicio centralizado para gestionar todas las peticiones HTTP
*                               a la API REST de Laravel con autenticación Sanctum.
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

import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants/api_constants.dart';

/// Servicio singleton para gestionar peticiones a la API
class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  String? _authToken;

  // ====================================================================
  // GESTIÓN DE AUTENTICACIÓN
  // ====================================================================

  /// Obtener token de autenticación desde SharedPreferences
  Future<String?> getAuthToken() async {
    if (_authToken != null) return _authToken;
    final prefs = await SharedPreferences.getInstance();
    _authToken = prefs.getString('auth_token');
    return _authToken;
  }

  /// Guardar token de autenticación
  Future<void> saveAuthToken(String token) async {
    _authToken = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  /// Eliminar token de autenticación (logout)
  Future<void> clearAuthToken() async {
    _authToken = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  /// Verificar si el usuario está autenticado
  Future<bool> isAuthenticated() async {
    final token = await getAuthToken();
    return token != null && token.isNotEmpty;
  }

  // ====================================================================
  // MÉTODOS HTTP GENÉRICOS
  // ====================================================================

  /// GET request genérico
  Future<Map<String, dynamic>> get(
    String url, {
    Map<String, String>? queryParams,
    bool requiresAuth = false,
  }) async {
    try {
      final uri = Uri.parse(url).replace(queryParameters: queryParams);
      final headers = await _getHeaders(requiresAuth);

      final response = await http
          .get(uri, headers: headers)
          .timeout(ApiConstants.timeoutDuration);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException(ApiConstants.errorNoInternet);
    } on TimeoutException {
      throw ApiException(ApiConstants.errorTimeout);
    } catch (e) {
      throw ApiException('${ApiConstants.errorGeneral}: $e');
    }
  }

  /// POST request genérico
  Future<Map<String, dynamic>> post(
    String url, {
    Map<String, dynamic>? body,
    bool requiresAuth = false,
  }) async {
    try {
      final uri = Uri.parse(url);
      final headers = await _getHeaders(requiresAuth);

      final response = await http
          .post(
            uri,
            headers: headers,
            body: body != null ? jsonEncode(body) : null,
          )
          .timeout(ApiConstants.timeoutDuration);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException(ApiConstants.errorNoInternet);
    } on TimeoutException {
      throw ApiException(ApiConstants.errorTimeout);
    } catch (e) {
      throw ApiException('${ApiConstants.errorGeneral}: $e');
    }
  }

  /// PUT request genérico
  Future<Map<String, dynamic>> put(
    String url, {
    Map<String, dynamic>? body,
    bool requiresAuth = true,
  }) async {
    try {
      final uri = Uri.parse(url);
      final headers = await _getHeaders(requiresAuth);

      final response = await http
          .put(
            uri,
            headers: headers,
            body: body != null ? jsonEncode(body) : null,
          )
          .timeout(ApiConstants.timeoutDuration);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException(ApiConstants.errorNoInternet);
    } on TimeoutException {
      throw ApiException(ApiConstants.errorTimeout);
    } catch (e) {
      throw ApiException('${ApiConstants.errorGeneral}: $e');
    }
  }

  /// DELETE request genérico
  Future<Map<String, dynamic>> delete(
    String url, {
    bool requiresAuth = true,
  }) async {
    try {
      final uri = Uri.parse(url);
      final headers = await _getHeaders(requiresAuth);

      final response = await http
          .delete(uri, headers: headers)
          .timeout(ApiConstants.timeoutDuration);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException(ApiConstants.errorNoInternet);
    } on TimeoutException {
      throw ApiException(ApiConstants.errorTimeout);
    } catch (e) {
      throw ApiException('${ApiConstants.errorGeneral}: $e');
    }
  }

  // ====================================================================
  // MÉTODOS PRIVADOS
  // ====================================================================

  /// Obtener headers con o sin autenticación
  Future<Map<String, String>> _getHeaders(bool requiresAuth) async {
    if (requiresAuth) {
      final token = await getAuthToken();
      if (token == null) {
        throw ApiException(ApiConstants.errorUnauthorized);
      }
      return ApiConstants.authenticatedHeaders(token);
    }
    return ApiConstants.defaultHeaders;
  }

  /// Procesar respuesta HTTP
  Map<String, dynamic> _handleResponse(http.Response response) {
    final statusCode = response.statusCode;

    // Respuesta exitosa
    if (statusCode >= 200 && statusCode < 300) {
      if (response.body.isEmpty) {
        return {'success': true};
      }
      return jsonDecode(response.body) as Map<String, dynamic>;
    }

    // Errores específicos
    switch (statusCode) {
      case ApiConstants.statusUnauthorized:
        clearAuthToken(); // Limpiar token inválido
        throw ApiException(ApiConstants.errorUnauthorized);

      case ApiConstants.statusNotFound:
        throw ApiException(ApiConstants.errorNotFound);

      case ApiConstants.statusUnprocessableEntity:
        final errors = jsonDecode(response.body);
        throw ApiException(_formatValidationErrors(errors));

      case ApiConstants.statusTooManyRequests:
        throw ApiException('Demasiadas peticiones. Intente más tarde');

      case ApiConstants.statusInternalServerError:
        throw ApiException(ApiConstants.errorServer);

      default:
        throw ApiException(
          'Error ${statusCode}: ${response.reasonPhrase ?? "Desconocido"}',
        );
    }
  }

  /// Formatear errores de validación Laravel
  String _formatValidationErrors(Map<String, dynamic> errors) {
    if (errors.containsKey('errors')) {
      final errorMap = errors['errors'] as Map<String, dynamic>;
      final messages = errorMap.values
          .expand((e) => e as List)
          .map((e) => e.toString())
          .join('\n');
      return messages;
    }
    return errors['message'] ?? ApiConstants.errorGeneral;
  }

  // ====================================================================
  // MÉTODOS DE API ESPECÍFICOS - AUTENTICACIÓN
  // ====================================================================

  /// Registrar nuevo usuario
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    final response = await post(
      ApiConstants.authRegister,
      body: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      },
    );

    // Guardar token si viene en la respuesta
    if (response.containsKey('token')) {
      await saveAuthToken(response['token']);
    }

    return response;
  }

  /// Iniciar sesión
  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await post(
      ApiConstants.authLogin,
      body: {
        'email': email,
        'password': password,
      },
    );

    // Guardar token
    if (response.containsKey('token')) {
      await saveAuthToken(response['token']);
    }

    return response;
  }

  /// Cerrar sesión
  Future<void> logout() async {
    try {
      await post(ApiConstants.authLogout, requiresAuth: true);
    } catch (e) {
      // Continuar incluso si falla el logout en el servidor
    } finally {
      await clearAuthToken();
    }
  }

  /// Obtener información del usuario autenticado
  Future<Map<String, dynamic>> getCurrentUser() async {
    return await get(ApiConstants.authMe, requiresAuth: true);
  }

  // ====================================================================
  // MÉTODOS DE API ESPECÍFICOS - CATEGORÍAS
  // ====================================================================

  /// Obtener todas las categorías
  Future<List<dynamic>> getCategorias() async {
    final response = await get(ApiConstants.categorias);
    return response['data'] ?? [];
  }

  /// Obtener categorías agrupadas por tipo
  Future<Map<String, dynamic>> getCategoriasAgrupadas() async {
    return await get(ApiConstants.categoriasAgrupadas);
  }

  /// Obtener categoría por ID
  Future<Map<String, dynamic>> getCategoriaById(int id) async {
    return await get(ApiConstants.categoriaById(id));
  }

  // ====================================================================
  // MÉTODOS DE API ESPECÍFICOS - ESTABLECIMIENTOS
  // ====================================================================

  /// Obtener establecimientos con filtros opcionales
  Future<Map<String, dynamic>> getEstablecimientos({
    int? categoriaId,
    String? tipo,
    double? minRating,
    String? metodoPago,
    bool? facturacion,
    int page = 1,
    int perPage = 15,
  }) async {
    final queryParams = <String, String>{
      ApiConstants.paramPage: page.toString(),
      ApiConstants.paramPerPage: perPage.toString(),
    };

    if (categoriaId != null) {
      queryParams[ApiConstants.paramCategoria] = categoriaId.toString();
    }
    if (tipo != null) {
      queryParams[ApiConstants.paramTipo] = tipo;
    }
    if (minRating != null) {
      queryParams[ApiConstants.paramMinRating] = minRating.toString();
    }
    if (metodoPago != null) {
      queryParams[ApiConstants.paramMetodoPago] = metodoPago;
    }
    if (facturacion != null) {
      queryParams[ApiConstants.paramFacturacion] = facturacion ? '1' : '0';
    }

    return await get(ApiConstants.establecimientos, queryParams: queryParams);
  }

  /// Obtener establecimiento por ID
  Future<Map<String, dynamic>> getEstablecimientoById(int id) async {
    return await get(ApiConstants.establecimientoById(id));
  }

  /// Obtener establecimientos cercanos
  Future<List<dynamic>> getEstablecimientosCercanos({
    required double latitud,
    required double longitud,
    double radio = 5.0,
  }) async {
    final queryParams = {
      ApiConstants.paramLatitud: latitud.toString(),
      ApiConstants.paramLongitud: longitud.toString(),
      ApiConstants.paramRadio: radio.toString(),
    };

    final response = await get(
      ApiConstants.establecimientosCercanos,
      queryParams: queryParams,
    );

    return response['data'] ?? [];
  }

  /// Buscar establecimientos por término
  Future<List<dynamic>> buscarEstablecimientos(String termino) async {
    final queryParams = {
      ApiConstants.paramTermino: termino,
    };

    final response = await get(
      ApiConstants.establecimientosBuscar,
      queryParams: queryParams,
    );

    return response['data'] ?? [];
  }

  // ====================================================================
  // MÉTODOS DE API ESPECÍFICOS - RESEÑAS
  // ====================================================================

  /// Obtener reseñas de un establecimiento
  Future<Map<String, dynamic>> getResenasByEstablecimiento(
    int establecimientoId, {
    int page = 1,
  }) async {
    final queryParams = {
      ApiConstants.paramPage: page.toString(),
    };

    return await get(
      ApiConstants.resenasByEstablecimiento(establecimientoId),
      queryParams: queryParams,
    );
  }

  /// Crear nueva reseña
  Future<Map<String, dynamic>> createResena({
    required int establecimientoId,
    required String clienteNombre,
    String? clienteEmail,
    required int puntuacion,
    required String comentario,
  }) async {
    return await post(
      ApiConstants.resenas,
      body: {
        'establecimiento_id': establecimientoId,
        'cliente_nombre': clienteNombre,
        'cliente_email': clienteEmail,
        'puntuacion': puntuacion,
        'comentario': comentario,
      },
      requiresAuth: true,
    );
  }

  /// Actualizar reseña existente
  Future<Map<String, dynamic>> updateResena({
    required int resenaId,
    required int puntuacion,
    required String comentario,
  }) async {
    return await put(
      ApiConstants.resenaById(resenaId),
      body: {
        'puntuacion': puntuacion,
        'comentario': comentario,
      },
    );
  }

  /// Eliminar reseña
  Future<void> deleteResena(int resenaId) async {
    await delete(ApiConstants.resenaById(resenaId));
  }

  // ====================================================================
  // HEALTH CHECK
  // ====================================================================

  /// Verificar estado de la API
  Future<Map<String, dynamic>> healthCheck() async {
    return await get(ApiConstants.health);
  }
}

/// Excepción personalizada para errores de API
class ApiException implements Exception {
  final String message;
  ApiException(this.message);

  @override
  String toString() => message;
}

class TimeoutException implements Exception {
  final String message;
  TimeoutException([this.message = 'Tiempo de espera agotado']);

  @override
  String toString() => message;
}
