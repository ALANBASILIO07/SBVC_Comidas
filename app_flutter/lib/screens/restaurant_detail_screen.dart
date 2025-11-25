import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:sistemadecomidas/screens/login_screen.dart';

class RestaurantDetailScreen extends StatefulWidget {
  final String restaurantName;

  const RestaurantDetailScreen({super.key, required this.restaurantName});

  @override
  State<RestaurantDetailScreen> createState() => _RestaurantDetailScreenState();
}

class _RestaurantDetailScreenState extends State<RestaurantDetailScreen> {
  double _currentRating = 0;

  void _showRatingSuccess() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: const Text('¡Valoración enviada con éxito!'),
        backgroundColor: const Color(0xFF241178),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.restaurantName),
        backgroundColor: const Color(0xFF241178),
        foregroundColor: Colors.white,
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _buildInfoCard(),
          const SizedBox(height: 20),
          _buildScheduleCard(),
          const SizedBox(height: 20),
          _buildPaymentMethodsCard(),
          const SizedBox(height: 20),
          _buildFoodsCard(),
          const SizedBox(height: 20),
          _buildRatingCard(),
        ],
      ),
    );
  }

  Widget _buildInfoCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 60,
                  height: 60,
                  decoration: BoxDecoration(
                    color: const Color(0xFF241178).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(30),
                    border: Border.all(color: const Color(0xFF241178)),
                  ),
                  child: const Icon(Icons.restaurant, color: Color(0xFF241178), size: 30),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        widget.restaurantName,
                        style: const TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFF272800),
                        ),
                      ),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: const Color(0xFF241178),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: const Text(
                              'Abierto',
                              style: TextStyle(color: Colors.white, fontSize: 12),
                            ),
                          ),
                          const SizedBox(width: 8),
                          const Icon(Icons.star, color: Colors.amber, size: 16),
                          const SizedBox(width: 4),
                          const Text('4.3'),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: const Color(0xFFDC6601).withOpacity(0.1),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: const Color(0xFFDC6601)),
                        ),
                        child: const Text(
                          'Negocio Formal',
                          style: TextStyle(
                            color: Color(0xFFDC6601),
                            fontWeight: FontWeight.bold,
                            fontSize: 12,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Row(
              children: [
                Icon(Icons.location_on, size: 16, color: Color(0xFF241178)),
                SizedBox(width: 4),
                Text(
                  'Av. Principal 123, Lima',
                  style: TextStyle(color: Color(0xFF272800)),
                ),
              ],
            ),
            const SizedBox(height: 8),
            const Row(
              children: [
                Icon(Icons.phone, size: 16, color: Color(0xFF241178)),
                SizedBox(width: 4),
                Text(
                  '+51 987 654 321',
                  style: TextStyle(color: Color(0xFF272800)),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildScheduleCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Row(
              children: [
                Icon(Icons.schedule, color: Color(0xFF241178)),
                SizedBox(width: 8),
                Text(
                  'Horarios',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF272800),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            _buildScheduleItem('Lunes - Viernes', '09:00 AM - 10:00 PM'),
            _buildScheduleItem('Sábados', '10:00 AM - 11:00 PM'),
            _buildScheduleItem('Domingos', '11:00 AM - 09:00 PM'),
          ],
        ),
      ),
    );
  }

  Widget _buildScheduleItem(String days, String hours) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(days, style: const TextStyle(color: Color(0xFF272800))),
          Text(
            hours,
            style: const TextStyle(
              fontWeight: FontWeight.bold,
              color: Color(0xFF241178),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPaymentMethodsCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Row(
              children: [
                Icon(Icons.payment, color: Color(0xFF241178)),
                SizedBox(width: 8),
                Text(
                  'Métodos de Pago',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF272800),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                _buildPaymentChip('Efectivo'),
                _buildPaymentChip('Tarjeta Crédito'),
                _buildPaymentChip('Tarjeta Débito'),
                _buildPaymentChip('Yape'),
                _buildPaymentChip('Plin'),
              ],
            ),
            const SizedBox(height: 8),
            const Row(
              children: [
                Icon(Icons.receipt, size: 16, color: Color(0xFFDC6601)),
                SizedBox(width: 4),
                Text(
                  'Facturación disponible',
                  style: TextStyle(
                    color: Color(0xFF272800),
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentChip(String method) {
    return Chip(
      label: Text(
        method,
        style: const TextStyle(
          color: Colors.white,
          fontSize: 12,
        ),
      ),
      backgroundColor: const Color(0xFF241178),
    );
  }

  Widget _buildFoodsCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Row(
              children: [
                Icon(Icons.restaurant_menu, color: Color(0xFF241178)),
                SizedBox(width: 8),
                Text(
                  'Comidas Disponibles',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF272800),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                _buildFoodChip('Pizza'),
                _buildFoodChip('Hamburguesas'),
                _buildFoodChip('Ensaladas'),
                _buildFoodChip('Postres'),
                _buildFoodChip('Bebidas'),
                _buildFoodChip('Comida Vegana'),
                _buildFoodChip('Comida Rápida'),
                _buildFoodChip('Mariscos'),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFoodChip(String food) {
    return Chip(
      label: Text(
        food,
        style: const TextStyle(
          color: Colors.white,
          fontSize: 12,
        ),
      ),
      backgroundColor: const Color(0xFFDC6601),
    );
  }

  Widget _buildRatingCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Row(
              children: [
                Icon(Icons.star, color: Color(0xFF241178)),
                SizedBox(width: 8),
                Text(
                  'Calificar Restaurante',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF272800),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Center(
              child: RatingBar.builder(
                initialRating: _currentRating,
                minRating: 1,
                direction: Axis.horizontal,
                allowHalfRating: true,
                itemCount: 5,
                itemPadding: const EdgeInsets.symmetric(horizontal: 4.0),
                itemBuilder: (context, _) => const Icon(
                  Icons.star,
                  color: Colors.amber,
                ),
                onRatingUpdate: (rating) {
                  setState(() {
                    _currentRating = rating;
                  });
                  print('Rating: $rating');
                },
              ),
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  _showRatingSuccess();
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF241178),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: const Text(
                  'Enviar Valoración',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}