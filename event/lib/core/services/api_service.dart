import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../config/constants/api_constants.dart';

class ApiService {
  // Singleton pattern
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  String? _token;

  // Obtener el token almacenado
  Future<String?> getToken() async {
    if (_token != null) return _token;

    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
    return _token;
  }

  // Guardar el token
  Future<void> saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Eliminar el token
  Future<void> removeToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // Headers base
  Map<String, String> _getHeaders({bool includeAuth = false}) {
    final headers = {
      'Content-Type': ApiConstants.contentType,
      'Accept': ApiConstants.accept,
    };

    if (includeAuth && _token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }

    return headers;
  }

  // POST request (Login, Register)
  Future<Map<String, dynamic>> post({
    required String endpoint,
    required Map<String, dynamic> body,
    bool includeAuth = false,
  }) async {
    try {
      final response = await http
          .post(
        Uri.parse(endpoint),
        headers: _getHeaders(includeAuth: includeAuth),
        body: jsonEncode(body),
      )
          .timeout(ApiConstants.connectionTimeout);

      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // GET request
  Future<Map<String, dynamic>> get({
    required String endpoint,
    bool includeAuth = true,
  }) async {
    try {
      await getToken(); // Asegurar que tenemos el token

      final response = await http
          .get(
        Uri.parse(endpoint),
        headers: _getHeaders(includeAuth: includeAuth),
      )
          .timeout(ApiConstants.receiveTimeout);

      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // PUT request
  Future<Map<String, dynamic>> put({
    required String endpoint,
    required Map<String, dynamic> body,
    bool includeAuth = true,
  }) async {
    try {
      await getToken();

      final response = await http
          .put(
        Uri.parse(endpoint),
        headers: _getHeaders(includeAuth: includeAuth),
        body: jsonEncode(body),
      )
          .timeout(ApiConstants.connectionTimeout);

      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // DELETE request
  Future<Map<String, dynamic>> delete({
    required String endpoint,
    bool includeAuth = true,
  }) async {
    try {
      await getToken();

      final response = await http
          .delete(
        Uri.parse(endpoint),
        headers: _getHeaders(includeAuth: includeAuth),
      )
          .timeout(ApiConstants.connectionTimeout);

      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // Manejar respuestas
  Map<String, dynamic> _handleResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (response.body.isEmpty) {
        return {'success': true};
      }
      return jsonDecode(response.body);
    } else {
      // Error del servidor
      final error = jsonDecode(response.body);
      throw ApiException(
        message: error['message'] ?? 'Error desconocido',
        statusCode: response.statusCode,
        errors: error['errors'],
      );
    }
  }

  // Manejar errores
  Exception _handleError(dynamic error) {
    if (error is ApiException) {
      return error;
    }

    return ApiException(
      message: 'Error de conexión: ${error.toString()}',
      statusCode: 0,
    );
  }
}

// Clase personalizada para excepciones de API
class ApiException implements Exception {
  final String message;
  final int statusCode;
  final Map<String, dynamic>? errors;

  ApiException({
    required this.message,
    required this.statusCode,
    this.errors,
  });

  @override
  String toString() {
    if (errors != null) {
      return '$message\nErrores: ${errors.toString()}';
    }
    return message;
  }
}