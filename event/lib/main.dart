import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'config/theme/app_theme.dart';
import 'features/auth/providers/auth_provider.dart';
import 'features/auth/screens/login_screen.dart';
import 'features/home/screens/home_screen.dart';
import 'shared/widgets/loading_widget.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        // Aquí agregaremos más providers después
      ],
      child: MaterialApp(
        title: 'Eventos Universitarios',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        home: const AuthChecker(),
      ),
    );
  }
}

// Widget que verifica si el usuario está autenticado
class AuthChecker extends StatefulWidget {
  const AuthChecker({super.key});

  @override
  State<AuthChecker> createState() => _AuthCheckerState();
}

class _AuthCheckerState extends State<AuthChecker> {
  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    await Future.delayed(const Duration(milliseconds: 500)); // Splash simulado
    if (mounted) {
      await context.read<AuthProvider>().getCurrentUser();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<AuthProvider>(
      builder: (context, authProvider, child) {
        // Mientras verifica la autenticación
        if (authProvider.status == AuthStatus.initial ||
            authProvider.status == AuthStatus.loading) {
          return const Scaffold(
            body: LoadingWidget(
              message: 'Cargando...',
            ),
          );
        }

        // Si está autenticado, mostrar Home
        if (authProvider.status == AuthStatus.authenticated) {
          return const HomeScreen();
        }

        // Si no está autenticado, mostrar Login
        return const LoginScreen();
      },
    );
  }
}