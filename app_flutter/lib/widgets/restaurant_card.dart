import 'package:flutter/material.dart';

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
                color: Colors.orange.withOpacity(0.2),
                borderRadius: BorderRadius.circular(25),
              ),
              child: const Icon(Icons.restaurant, color: Colors.orange),
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
                    ),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Icon(
                        isOpen ? Icons.check_circle : Icons.cancel,
                        color: isOpen ? Colors.green : Colors.red,
                        size: 16,
                      ),
                      const SizedBox(width: 4),
                      Text(
                        isOpen ? 'Abierto' : 'Cerrado',
                        style: TextStyle(
                          color: isOpen ? Colors.green : Colors.red,
                        ),
                      ),
                    ],
                  ),
                  Text('A $distance de distancia'),
                ],
              ),
            ),

            // Rating
            Column(
              children: [
                const Icon(Icons.star, color: Colors.amber, size: 20),
                Text(rating.toString()),
              ],
            ),
          ],
        ),
      ),
    );
  }
}