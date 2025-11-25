import 'package:flutter/material.dart';

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
      child: ExpansionTile(
        title: const Row(
          children: [
            Icon(Icons.local_offer, color: Colors.orange),
            SizedBox(width: 8),
            Text(
              'Promociones Disponibles',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: Colors.orange,
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
      leading: const Icon(Icons.restaurant_menu, color: Colors.green),
      title: Text(restaurant),
      subtitle: Text(offer),
      trailing: Text(
        validUntil,
        style: const TextStyle(fontSize: 12, color: Colors.grey),
      ),
    );
  }
}