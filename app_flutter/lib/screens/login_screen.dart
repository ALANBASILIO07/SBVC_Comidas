import 'package:flutter/material.dart';
import 'map_screen.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              // Logo - Reemplazar con tu imagen
              Container(
                width: 120,
                height: 120,
                decoration: BoxDecoration(
                  color: const Color(0xFF241178), // Azul oscuro del logo
                  borderRadius: BorderRadius.circular(60),
                  image: const DecorationImage(
                    image: AssetImage('web/icons/logo_comidas.jpg'),
                    fit: BoxFit.cover,
                  ),
                ),
                child: const Icon(
                  Icons.restaurant,
                  size: 60,
                  color: Colors.white,
                ), // Fallback si no hay imagen
              ),
              const SizedBox(height: 40),

              // Título
              const Text(
                'SGC',
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF241178), // Azul oscuro
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                'Encuentra los mejores restaurantes',
                style: TextStyle(
                  fontSize: 16,
                  color: Color(0xFF272800), // Verde oscuro
                ),
              ),
              const SizedBox(height: 40),

              // Campo de email
              TextField(
                decoration: InputDecoration(
                  labelText: 'Email',
                  labelStyle: const TextStyle(color: Color(0xFF272800)),
                  prefixIcon: const Icon(Icons.email, color: Color(0xFFDC6601)),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: Color(0xFF241178)),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: Color(0xFFDC6601)),
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Campo de contraseña
              TextField(
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Contraseña',
                  labelStyle: const TextStyle(color: Color(0xFF272800)),
                  prefixIcon: const Icon(Icons.lock, color: Color(0xFFDC6601)),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: Color(0xFF241178)),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: Color(0xFFDC6601)),
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Botón de login
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(builder: (context) => const MapScreen()),
                    );
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF241178), // Azul oscuro
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                    elevation: 2,
                  ),
                  child: const Text(
                    'Iniciar Sesión',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Opción de registro
              TextButton(
                onPressed: () {
                  // Mock: ir a registro
                },
                child: const Text(
                  '¿No tienes cuenta? Regístrate',
                  style: TextStyle(
                    color: Color(0xFFDC6601), // Naranja
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}