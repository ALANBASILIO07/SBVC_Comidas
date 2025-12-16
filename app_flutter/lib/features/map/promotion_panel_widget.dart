/*
* Nombre de la clase         : promotion_panel_widget.dart
* Descripción de la clase    : Panel expansible que muestra promociones activas de restaurantes,
*                               con información de ofertas y fechas de validez.
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

class PromotionPanel extends StatefulWidget {
  const PromotionPanel({super.key});

  @override
  State<PromotionPanel> createState() => _PromotionPanelState();
}

class _PromotionPanelState extends State<PromotionPanel> {
  bool _isExpanded = false;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.all(16),
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
      child: ExpansionTile(
        title: const Row(
          children: [
            Icon(Icons.local_offer, color: AppColors.primary),
            SizedBox(width: 8),
            Text(
              'Promociones Disponibles',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
          ],
        ),
        children: [
          _buildPromotionItem('Pizza Hut', '2x1 en pizzas grandes', 'Válido hasta 30/11'),
          _buildPromotionItem('Burger King', 'Combo Whopper a 5', 'Solo hoy'),
          _buildPromotionItem('KFC', '20% de descuento en alitas', 'Fin de semana'),
        ],
      ),
    );
  }

  Widget _buildPromotionItem(String restaurant, String offer, String validUntil) {
    return ListTile(
      leading: const Icon(Icons.restaurant_menu, color: AppColors.success),
      title: Text(
        restaurant,
        style: const TextStyle(color: AppColors.textPrimary),
      ),
      subtitle: Text(
        offer,
        style: const TextStyle(color: AppColors.textSecondary),
      ),
      trailing: Text(
        validUntil,
        style: const TextStyle(
          fontSize: 12,
          color: AppColors.textSecondary,
        ),
      ),
    );
  }
}
