class ApiConstants {
  // URL base - CÁMBIALA por la URL de tu servidor Laravel
  // Si estás en desarrollo local, usa:
  // Android Emulator: 'http://10.0.2.2:8000/api'
  // Dispositivo físico: 'http://192.168.X.X:8000/api' (tu IP local)
  // Producción: 'https://tu-dominio.com/api'

  static const String baseUrl = 'http://10.0.2.2:8000/api';

  // Endpoints de autenticación
  static const String register = '$baseUrl/register';
  static const String login = '$baseUrl/login';
  static const String user = '$baseUrl/user';

  // Endpoints de administración (requieren auth)
  static const String filiales = '$baseUrl/filiales';
  static const String facultades = '$baseUrl/facultades';
  static const String escuelas = '$baseUrl/escuelas';
  static const String roles = '$baseUrl/roles';
  static const String usuarios = '$baseUrl/usuarios';

  // Endpoints específicos con ID
  static String filialById(int id) => '$baseUrl/filiales/$id';
  static String facultadById(int id) => '$baseUrl/facultades/$id';
  static String escuelaById(int id) => '$baseUrl/escuelas/$id';
  static String roleById(int id) => '$baseUrl/roles/$id';
  static String usuarioById(int id) => '$baseUrl/usuarios/$id';

  // Endpoints con paginación y búsqueda
  static const String rolesPaginated = '$baseUrl/roles/paginated';
  static const String rolesSearch = '$baseUrl/roles/search';

  // Headers
  static const String contentType = 'application/json';
  static const String accept = 'application/json';

  // Timeouts
  static const Duration connectionTimeout = Duration(seconds: 30);
  static const Duration receiveTimeout = Duration(seconds: 30);
}