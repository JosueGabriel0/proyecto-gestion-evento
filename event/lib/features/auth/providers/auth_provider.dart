import 'package:flutter/foundation.dart';
import '../../../core/services/api_service.dart';
import '../../../config/constants/api_constants.dart';
import '../models/user_model.dart';
import '../models/login_response_model.dart';

enum AuthStatus {
  initial,
  loading,
  authenticated,
  unauthenticated,
  error,
}

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  AuthStatus _status = AuthStatus.initial;
  UserModel? _user;
  String? _errorMessage;

  AuthStatus get status => _status;
  UserModel? get user => _user;
  String? get errorMessage => _errorMessage;
  bool get isAuthenticated => _status == AuthStatus.authenticated;

  // Login
  Future<bool> login({
    required String email,
    required String password,
  }) async {
    try {
      _setStatus(AuthStatus.loading);

      final response = await _apiService.post(
        endpoint: ApiConstants.login,
        body: {
          'email': email,
          'password': password,
        },
      );

      // Parsear la respuesta
      final loginResponse = LoginResponseModel.fromJson(response);

      // Guardar el token
      await _apiService.saveToken(loginResponse.token);

      // Guardar el usuario
      _user = loginResponse.user;

      _setStatus(AuthStatus.authenticated);
      return true;

    } on ApiException catch (e) {
      _errorMessage = e.message;
      _setStatus(AuthStatus.error);
      return false;
    } catch (e) {
      _errorMessage = 'Error inesperado: ${e.toString()}';
      _setStatus(AuthStatus.error);
      return false;
    }
  }

  // Register
  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      _setStatus(AuthStatus.loading);

      await _apiService.post(
        endpoint: ApiConstants.register,
        body: {
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        },
      );

      // Después de registrar, hacer login automático
      return await login(email: email, password: password);

    } on ApiException catch (e) {
      _errorMessage = e.message;
      _setStatus(AuthStatus.error);
      return false;
    } catch (e) {
      _errorMessage = 'Error inesperado: ${e.toString()}';
      _setStatus(AuthStatus.error);
      return false;
    }
  }

  // Obtener usuario actual
  Future<void> getCurrentUser() async {
    try {
      final token = await _apiService.getToken();

      if (token == null) {
        _setStatus(AuthStatus.unauthenticated);
        return;
      }

      _setStatus(AuthStatus.loading);

      final response = await _apiService.get(
        endpoint: ApiConstants.user,
        includeAuth: true,
      );

      _user = UserModel.fromJson(response);
      _setStatus(AuthStatus.authenticated);

    } catch (e) {
      await logout();
    }
  }

  // Logout
  Future<void> logout() async {
    await _apiService.removeToken();
    _user = null;
    _setStatus(AuthStatus.unauthenticated);
  }

  // Método auxiliar para cambiar el estado
  void _setStatus(AuthStatus newStatus) {
    _status = newStatus;
    notifyListeners();
  }

  // Limpiar error
  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}