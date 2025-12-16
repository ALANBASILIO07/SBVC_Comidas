/*
* Nombre de la clase         : profile_screen.dart
* Descripción de la clase    : Pantalla de perfil de usuario que muestra información personal,
*                               plan activo y opciones de configuración del sistema.
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
import '../auth/login_screen.dart';
import 'edit_profile_screen.dart';
import 'plan_management_screen.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    // Mock user data
    final user = {
      'name': 'Juan Pérez',
      'email': 'juan.perez@email.com',
      'plan': 'premium',
    };

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mi Perfil'),
        backgroundColor: AppColors.secondary,
        foregroundColor: AppColors.textOnSecondary,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Avatar y información básica
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: AppColors.surface,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: AppColors.shadow,
                    blurRadius: 10,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: Column(
                children: [
                  const CircleAvatar(
                    radius: 50,
                    backgroundColor: AppColors.secondary,
                    child: Icon(
                      Icons.person,
                      size: 50,
                      color: AppColors.white,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    user['name']!,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    user['email']!,
                    style: const TextStyle(
                      fontSize: 16,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      color: user['plan'] == 'premium'
                          ? AppColors.primary
                          : AppColors.secondary,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      user['plan'] == 'premium' ? 'Plan Premium' : 'Plan Básico',
                      style: const TextStyle(
                        color: AppColors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            // Opciones del perfil
            Expanded(
              child: ListView(
                children: [
                  _buildProfileOption(
                    Icons.edit,
                    'Editar Perfil',
                    () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const EditProfileScreen()),
                      );
                    },
                  ),
                  _buildProfileOption(
                    Icons.credit_card,
                    'Gestión de Plan',
                    () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const PlanManagementScreen()),
                      );
                    },
                  ),
                  _buildProfileOption(
                    Icons.settings,
                    'Configuración',
                    () {
                      // TODO: Implementar configuración
                    },
                  ),
                  _buildProfileOption(
                    Icons.help,
                    'Ayuda y Soporte',
                    () {
                      // TODO: Implementar ayuda
                    },
                  ),
                  _buildProfileOption(
                    Icons.privacy_tip,
                    'Política de Privacidad',
                    () {
                      // TODO: Implementar política
                    },
                  ),
                  _buildProfileOption(
                    Icons.logout,
                    'Cerrar Sesión',
                    () {
                      Navigator.pushReplacement(
                        context,
                        MaterialPageRoute(builder: (context) => const LoginScreen()),
                      );
                    },
                    isLogout: true,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileOption(
    IconData icon,
    String text,
    VoidCallback onTap, {
    bool isLogout = false,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      child: ListTile(
        leading: Icon(
          icon,
          color: isLogout ? AppColors.error : AppColors.secondary,
        ),
        title: Text(
          text,
          style: TextStyle(
            color: isLogout ? AppColors.error : AppColors.textPrimary,
            fontWeight: isLogout ? FontWeight.bold : FontWeight.normal,
          ),
        ),
        trailing: const Icon(
          Icons.arrow_forward_ios,
          size: 16,
          color: AppColors.primary,
        ),
        onTap: onTap,
      ),
    );
  }
}
