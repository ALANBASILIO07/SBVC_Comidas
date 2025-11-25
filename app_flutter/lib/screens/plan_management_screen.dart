import 'package:flutter/material.dart';

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
        backgroundColor: const Color(0xFF241178),
        foregroundColor: Colors.white,
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
                color: Color(0xFF272800),
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
                  backgroundColor: const Color(0xFFDC6601),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: Text(
                  _selectedPlan == 'premium' ? 'Suscribirse al Plan Premium' : 'Continuar con Plan Básico',
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
      color: _selectedPlan == planType ? const Color(0xFF241178).withOpacity(0.1) : Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: _selectedPlan == planType ? const Color(0xFF241178) : Colors.grey.shade300,
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
                    color: _selectedPlan == planType ? const Color(0xFF241178) : const Color(0xFF272800),
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
                  activeColor: const Color(0xFF241178),
                ),
              ],
            ),
            Text(
              price,
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: const Color(0xFFDC6601),
              ),
            ),
            const SizedBox(height: 8),
            Text(
              description,
              style: const TextStyle(
                color: Color(0xFF272800),
              ),
            ),
            const SizedBox(height: 12),
            ...features.map((feature) => Padding(
              padding: const EdgeInsets.symmetric(vertical: 4),
              child: Row(
                children: [
                  Icon(
                    Icons.check_circle,
                    color: const Color(0xFF241178),
                    size: 16,
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      feature,
                      style: const TextStyle(
                        color: Color(0xFF272800),
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
          _selectedPlan == 'premium' ? 'Confirmar Suscripción Premium' : 'Continuar con Plan Básico',
          style: const TextStyle(color: Color(0xFF272800)),
        ),
        content: Text(
          _selectedPlan == 'premium'
              ? '¿Estás seguro de que deseas suscribirte al Plan Premium por \$9.99 al mes?'
              : 'Continuarás disfrutando de las funciones básicas de forma gratuita.',
          style: const TextStyle(color: Color(0xFF272800)),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar', style: TextStyle(color: Color(0xFFEE0000))),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              // Aquí iría la lógica de suscripción
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(
                    _selectedPlan == 'premium'
                        ? '¡Suscripción Premium activada!'
                        : 'Plan Básico confirmado',
                  ),
                  backgroundColor: const Color(0xFF241178),
                ),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF241178),
            ),
            child: const Text('Confirmar', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }
}