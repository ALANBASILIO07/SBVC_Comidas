import 'package:flutter/material.dart';
import 'login_screen.dart';
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
        backgroundColor: const Color(0xFF241178),
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Avatar y información básica
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.grey.withOpacity(0.3),
                    blurRadius: 10,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: Column(
                children: [
                  const CircleAvatar(
                    radius: 50,
                    backgroundColor: Color(0xFF241178),
                    child: Icon(
                      Icons.person,
                      size: 50,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    user['name']!,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF272800),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    user['email']!,
                    style: const TextStyle(
                      fontSize: 16,
                      color: Color(0xFF272800),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      color: user['plan'] == 'premium'
                          ? const Color(0xFFDC6601)  // Naranja para premium
                          : const Color(0xFF241178), // Azul para básico
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      user['plan'] == 'premium' ? 'Plan Premium' : 'Plan Básico',
                      style: const TextStyle(
                        color: Colors.white,
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
                      // Mock: configuración
                    },
                  ),
                  _buildProfileOption(
                    Icons.help,
                    'Ayuda y Soporte',
                        () {
                      // Mock: ayuda
                    },
                  ),
                  _buildProfileOption(
                    Icons.privacy_tip,
                    'Política de Privacidad',
                        () {
                      // Mock: política
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

  Widget _buildProfileOption(IconData icon, String text, VoidCallback onTap, {bool isLogout = false}) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      child: ListTile(
        leading: Icon(
          icon,
          color: isLogout ? const Color(0xFFEE0000) : const Color(0xFF241178),
        ),
        title: Text(
          text,
          style: TextStyle(
            color: isLogout ? const Color(0xFFEE0000) : const Color(0xFF272800),
            fontWeight: isLogout ? FontWeight.bold : FontWeight.normal,
          ),
        ),
        trailing: const Icon(Icons.arrow_forward_ios, size: 16, color: Color(0xFFDC6601)),
        onTap: onTap,
      ),
    );
  }
}