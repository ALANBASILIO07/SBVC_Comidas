/*
* Nombre de la clase         : login_screen.dart
* Descripción de la clase    : Pantalla de inicio de sesión para usuarios del sistema SBVC_Comidas,
*                               permite autenticación mediante email y contraseña.
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

import 'package:flutter/material.dart';
import '../../core/constants/app_colors.dart';
import '../map/map_screen.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              // Logo
              Container(
                width: 120,
                height: 120,
                decoration: BoxDecoration(
                  color: AppColors.secondary,
                  borderRadius: BorderRadius.circular(60),
                  image: const DecorationImage(
                    image: AssetImage('web/icons/logo_comidas.jpg'),
                    fit: BoxFit.cover,
                  ),
                ),
                child: const Icon(
                  Icons.restaurant,
                  size: 60,
                  color: AppColors.white,
                ),
              ),
              const SizedBox(height: 40),

              // Título
              const Text(
                'SGC',
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.secondary,
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                'Encuentra los mejores restaurantes',
                style: TextStyle(
                  fontSize: 16,
                  color: AppColors.textPrimary,
                ),
              ),
              const SizedBox(height: 40),

              // Campo de email
              TextField(
                decoration: InputDecoration(
                  labelText: 'Email',
                  labelStyle: const TextStyle(color: AppColors.textPrimary),
                  prefixIcon: const Icon(Icons.email, color: AppColors.primary),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.secondary),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.primary),
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Campo de contraseña
              TextField(
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Contraseña',
                  labelStyle: const TextStyle(color: AppColors.textPrimary),
                  prefixIcon: const Icon(Icons.lock, color: AppColors.primary),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.secondary),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.primary),
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
                    backgroundColor: AppColors.secondary,
                    foregroundColor: AppColors.textOnSecondary,
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
                  // TODO: Implementar navegación a registro
                },
                child: const Text(
                  '¿No tienes cuenta? Regístrate',
                  style: TextStyle(
                    color: AppColors.primary,
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
