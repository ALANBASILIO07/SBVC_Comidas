/*
* Nombre de la clase         : restaurant_card_widget.dart
* Descripción de la clase    : Widget reutilizable que muestra la tarjeta de información resumida
*                               de un restaurante incluyendo nombre, estado, distancia y calificación.
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

class RestaurantCard extends StatelessWidget {
  final String name;
  final String status;
  final String distance;
  final double rating;
  final bool isOpen;

  const RestaurantCard({
    super.key,
    required this.name,
    required this.status,
    required this.distance,
    required this.rating,
    required this.isOpen,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            // Icono del restaurante
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: AppColors.primary.withOpacity(0.2),
                borderRadius: BorderRadius.circular(25),
              ),
              child: const Icon(Icons.restaurant, color: AppColors.primary),
            ),
            const SizedBox(width: 16),

            // Información del restaurante
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name,
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Icon(
                        isOpen ? Icons.check_circle : Icons.cancel,
                        color: isOpen ? AppColors.success : AppColors.error,
                        size: 16,
                      ),
                      const SizedBox(width: 4),
                      Text(
                        isOpen ? 'Abierto' : 'Cerrado',
                        style: TextStyle(
                          color: isOpen ? AppColors.success : AppColors.error,
                        ),
                      ),
                    ],
                  ),
                  Text(
                    'A $distance de distancia',
                    style: const TextStyle(color: AppColors.textSecondary),
                  ),
                ],
              ),
            ),

            // Rating
            Column(
              children: [
                const Icon(Icons.star, color: Colors.amber, size: 20),
                Text(
                  rating.toString(),
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    color: AppColors.textPrimary,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
