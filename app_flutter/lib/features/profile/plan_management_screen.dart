/*
* Nombre de la clase         : plan_management_screen.dart
* Descripción de la clase    : Pantalla para gestión de planes de suscripción, permite seleccionar
*                               entre plan básico (gratis) y plan premium (funciones avanzadas).
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

class PlanManagementScreen extends StatefulWidget {
  const PlanManagementScreen({super.key});

  @override
  State<PlanManagementScreen> createState() => _PlanManagementScreenState();
}

class _PlanManagementScreenState extends State<PlanManagementScreen> {
  String _selectedPlan = 'basic';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Gestión de Plan'),
        backgroundColor: AppColors.secondary,
        foregroundColor: AppColors.textOnSecondary,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Selecciona tu Plan',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 20),

            // Plan Básico
            _buildPlanCard(
              title: 'Plan Básico',
              price: 'Gratis',
              description: 'Funcionalidades esenciales',
              features: const [
                'Búsqueda básica por nombre',
                'Visualización de restaurantes cercanos',
                'Información básica de restaurantes',
                'Mapa interactivo simple',
              ],
              planType: 'basic',
            ),
            const SizedBox(height: 16),

            // Plan Premium
            _buildPlanCard(
              title: 'Plan Premium',
              price: '\$9.99/mes',
              description: 'Búsquedas avanzadas y funciones exclusivas',
              features: const [
                'Búsqueda por ubicación exacta',
                'Filtros por calificación y categorías',
                'Búsqueda por rango de precios',
                'Resultados prioritarios',
                'Sin anuncios publicitarios',
                'Soporte prioritario',
              ],
              planType: 'premium',
            ),
            const SizedBox(height: 24),

            // Botón de confirmación
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: () {
                  _showConfirmationDialog(context);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: AppColors.textOnPrimary,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: Text(
                  _selectedPlan == 'premium'
                      ? 'Suscribirse al Plan Premium'
                      : 'Continuar con Plan Básico',
                  style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPlanCard({
    required String title,
    required String price,
    required String description,
    required List<String> features,
    required String planType,
  }) {
    return Card(
      elevation: 3,
      color: _selectedPlan == planType
          ? AppColors.secondary.withOpacity(0.1)
          : AppColors.surface,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: _selectedPlan == planType ? AppColors.secondary : AppColors.border,
          width: _selectedPlan == planType ? 2 : 1,
        ),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: _selectedPlan == planType
                        ? AppColors.secondary
                        : AppColors.textPrimary,
                  ),
                ),
                Radio(
                  value: planType,
                  groupValue: _selectedPlan,
                  onChanged: (value) {
                    setState(() {
                      _selectedPlan = value.toString();
                    });
                  },
                  activeColor: AppColors.secondary,
                ),
              ],
            ),
            Text(
              price,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              description,
              style: const TextStyle(
                color: AppColors.textSecondary,
              ),
            ),
            const SizedBox(height: 12),
            ...features.map((feature) => Padding(
                  padding: const EdgeInsets.symmetric(vertical: 4),
                  child: Row(
                    children: [
                      const Icon(
                        Icons.check_circle,
                        color: AppColors.success,
                        size: 16,
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          feature,
                          style: const TextStyle(
                            color: AppColors.textPrimary,
                            fontSize: 14,
                          ),
                        ),
                      ),
                    ],
                  ),
                )),
          ],
        ),
      ),
    );
  }

  void _showConfirmationDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(
          _selectedPlan == 'premium'
              ? 'Confirmar Suscripción Premium'
              : 'Continuar con Plan Básico',
          style: const TextStyle(color: AppColors.textPrimary),
        ),
        content: Text(
          _selectedPlan == 'premium'
              ? '¿Estás seguro de que deseas suscribirte al Plan Premium por \$9.99 al mes?'
              : 'Continuarás disfrutando de las funciones básicas de forma gratuita.',
          style: const TextStyle(color: AppColors.textSecondary),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar', style: TextStyle(color: AppColors.error)),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(
                    _selectedPlan == 'premium'
                        ? '¡Suscripción Premium activada!'
                        : 'Plan Básico confirmado',
                  ),
                  backgroundColor: AppColors.secondary,
                ),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.secondary,
            ),
            child: const Text(
              'Confirmar',
              style: TextStyle(color: AppColors.textOnSecondary),
            ),
          ),
        ],
      ),
    );
  }
}
