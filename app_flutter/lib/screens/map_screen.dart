import 'package:flutter/foundation.dart'; // ← NUEVO: para detectar plataforma
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:sistemadecomidas/widgets/promotion_panel.dart';
import 'package:sistemadecomidas/widgets/restaurant_card.dart';
import 'package:sistemadecomidas/widgets/filter_panel.dart';
import 'package:sistemadecomidas/screens/restaurant_detail_screen.dart';
import 'package:sistemadecomidas/screens/profile_screen.dart';
import 'package:sistemadecomidas/screens/login_screen.dart';

class MapScreen extends StatefulWidget {
  const MapScreen({super.key});

  @override
  State<MapScreen> createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  late GoogleMapController mapController;
  final LatLng _center = const LatLng(-12.0464, -77.0428);
  Map<String, dynamic> _activeFilters = {};

  // Mock data de restaurantes
  final List<Map<String, dynamic>> _allRestaurants = [
    {
      'name': 'Pizza Hut',
      'distance': '0.5 km',
      'rating': 4.3,
      'isOpen': true,
      'businessType': 'formal',
      'priceRange': 'medio',
      'position': const LatLng(-12.0464, -77.0428),
    },
    {
      'name': 'Burger King',
      'distance': '0.8 km',
      'rating': 4.1,
      'isOpen': false,
      'businessType': 'formal',
      'priceRange': 'economico',
      'position': const LatLng(-12.0450, -77.0400),
    },
    {
      'name': 'Antojitos María',
      'distance': '1.2 km',
      'rating': 4.5,
      'isOpen': true,
      'businessType': 'informal',
      'priceRange': 'economico',
      'position': const LatLng(-12.0470, -77.0430),
    },
    {
      'name': 'Restaurante Gourmet',
      'distance': '1.5 km',
      'rating': 4.8,
      'isOpen': true,
      'businessType': 'formal',
      'priceRange': 'premium',
      'position': const LatLng(-12.0440, -77.0410),
    },
  ];

  List<Map<String, dynamic>> get _filteredRestaurants {
    var filtered = List<Map<String, dynamic>>.from(_allRestaurants);

    if (_activeFilters.isNotEmpty) {
      if (_activeFilters['minRating'] != null && _activeFilters['minRating'] > 0) {
        filtered = filtered
            .where((r) => r['rating'] >= _activeFilters['minRating'])
            .toList();
      }
      if (_activeFilters['businessType'] != null &&
          _activeFilters['businessType'] != 'todos') {
        filtered = filtered
            .where((r) => r['businessType'] == _activeFilters['businessType'])
            .toList();
      }
      if (_activeFilters['priceRange'] != null &&
          _activeFilters['priceRange'] != 'todos') {
        filtered = filtered
            .where((r) => r['priceRange'] == _activeFilters['priceRange'])
            .toList();
      }
      if (_activeFilters['isOpenNow'] == true) {
        filtered = filtered.where((r) => r['isOpen'] == true).toList();
      }
    }
    return filtered;
  }

  void _onMapCreated(GoogleMapController controller) {
    mapController = controller;
  }

  void _showRestaurantDetails(BuildContext context, String restaurantName) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => RestaurantDetailScreen(restaurantName: restaurantName),
      ),
    );
  }

  void _showFilterPanel(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => DraggableScrollableSheet(
        initialChildSize: 0.8,
        minChildSize: 0.5,
        maxChildSize: 0.9,
        builder: (_, controller) => FilterPanel(
          onFiltersChanged: (filters) {
            setState(() {
              _activeFilters = filters;
            });
          },
        ),
      ),
    );
  }

  Set<Marker> _getMarkers() {
    return {
      for (var r in _filteredRestaurants)
        Marker(
          markerId: MarkerId(r['name']),
          position: r['position'],
          infoWindow: InfoWindow(
            title: r['name'],
            snippet:
            '${r['isOpen'] ? 'Abierto' : 'Cerrado'} • ${r['rating']}⭐',
            onTap: () => _showRestaurantDetails(context, r['name']),
          ),
          icon: BitmapDescriptor.defaultMarkerWithHue(
            r['isOpen'] ? BitmapDescriptor.hueGreen : BitmapDescriptor.hueRed,
          ),
        )
    };
  }

  String _getActiveFiltersText() {
    if (_activeFilters.isEmpty) return '';
    List<String> parts = [];

    if (_activeFilters['minRating'] != null && _activeFilters['minRating'] > 0) {
      parts.add('${_activeFilters['minRating'].toInt()}+⭐');
    }
    if (_activeFilters['businessType'] != null &&
        _activeFilters['businessType'] != 'todos') {
      parts.add(_activeFilters['businessType'] == 'formal' ? 'Formal' : 'Informal');
    }
    if (_activeFilters['priceRange'] != null &&
        _activeFilters['priceRange'] != 'todos') {
      final map = {
        'economico': 'Económico',
        'medio': 'Precio Medio',
        'premium': 'Premium'
      };
      parts.add(map[_activeFilters['priceRange']] ?? '');
    }
    if (_activeFilters['isOpenNow'] == true) parts.add('Abierto');

    return parts.join(' • ');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Food Finder'),
        backgroundColor: const Color(0xFF241178),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () => Navigator.push(context,
                MaterialPageRoute(builder: (_) => const ProfileScreen())),
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => Navigator.pushReplacement(context,
                MaterialPageRoute(builder: (_) => const LoginScreen())),
          ),
        ],
      ),
      body: Stack(
        children: [
          // =================== MAPA (solo móvil) / PLACEHOLDER (web) ===================
          if (kIsWeb)
            Container(
              color: const Color(0xFF241178),
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.map_outlined,
                        size: 100, color: Colors.white.withOpacity(0.8)),
                    const SizedBox(height: 24),
                    const Text(
                      "Mapa disponible en la versión móvil",
                      style: TextStyle(color: Colors.white, fontSize: 20),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      "En web usamos lista + filtros",
                      style: TextStyle(color: Colors.white70, fontSize: 16),
                    ),
                  ],
                ),
              ),
            )
          else
            GoogleMap(
              onMapCreated: _onMapCreated,
              initialCameraPosition: CameraPosition(target: _center, zoom: 15.0),
              markers: _getMarkers(),
              myLocationEnabled: true,
              myLocationButtonEnabled: true,
            ),

          // =================== BARRA DE BÚSQUEDA Y FILTROS ===================
          Positioned(
            top: 16,
            left: 16,
            right: 16,
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(25),
                    boxShadow: [
                      BoxShadow(
                          color: Colors.grey.withOpacity(0.3),
                          blurRadius: 10,
                          offset: const Offset(0, 2)),
                    ],
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.search, color: Color(0xFF241178)),
                      const SizedBox(width: 8),
                      const Expanded(
                        child: TextField(
                          decoration: InputDecoration(
                            hintText: 'Buscar comida o restaurante...',
                            border: InputBorder.none,
                          ),
                        ),
                      ),
                      IconButton(
                        icon: Badge(
                          isLabelVisible: _activeFilters.isNotEmpty,
                          backgroundColor: const Color(0xFFEE0000),
                          child: const Icon(Icons.filter_list,
                              color: Color(0xFF241178)),
                        ),
                        onPressed: () => _showFilterPanel(context),
                      ),
                    ],
                  ),
                ),

                // Filtros activos
                if (_activeFilters.isNotEmpty) ...[
                  const SizedBox(height: 8),
                  Container(
                    padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                            color: Colors.grey.withOpacity(0.2),
                            blurRadius: 5,
                            offset: const Offset(0, 1)),
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.filter_alt,
                            size: 14, color: Color(0xFF241178)),
                        const SizedBox(width: 4),
                        Text(_getActiveFiltersText(),
                            style: const TextStyle(fontSize: 12)),
                        const SizedBox(width: 8),
                        GestureDetector(
                          onTap: () => setState(() => _activeFilters = {}),
                          child: const Icon(Icons.close,
                              size: 14, color: Color(0xFFEE0000)),
                        ),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ),

          // =================== PANEL DE PROMOCIONES ===================
          Positioned(
            top: _activeFilters.isNotEmpty ? 120 : 80,
            left: 0,
            right: 0,
            child: const PromotionPanel(),
          ),

          // =================== LISTA DE RESTAURANTES ===================
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: Container(
              height: 200,
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(20),
                  topRight: Radius.circular(20),
                ),
                boxShadow: [
                  BoxShadow(color: Colors.grey, blurRadius: 10, offset: Offset(0, -2)),
                ],
              ),
              child: Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          '${_filteredRestaurants.length} Restaurantes ${_activeFilters.isNotEmpty ? 'Filtrados' : 'Cercanos'}',
                          style: const TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 18,
                              color: Color(0xFF272800)),
                        ),
                        if (_activeFilters.isNotEmpty)
                          TextButton(
                            onPressed: () => setState(() => _activeFilters = {}),
                            child: const Text('Limpiar',
                                style: TextStyle(
                                    color: Color(0xFFEE0000),
                                    fontWeight: FontWeight.bold)),
                          ),
                      ],
                    ),
                  ),
                  Expanded(
                    child: _filteredRestaurants.isEmpty
                        ? const Center(
                      child: Text(
                        'No se encontraron restaurantes\ncon los filtros aplicados',
                        textAlign: TextAlign.center,
                      ),
                    )
                        : ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: _filteredRestaurants.length,
                      itemBuilder: (context, i) {
                        final r = _filteredRestaurants[i];
                        return GestureDetector(
                          onTap: () => _showRestaurantDetails(context, r['name']),
                          child: Container(
                            width: 300,
                            margin: const EdgeInsets.only(left: 16, right: 8),
                            child: RestaurantCard(
                              name: r['name'],
                              status: r['isOpen'] ? 'Abierto' : 'Cerrado',
                              distance: r['distance'],
                              rating: r['rating'],
                              isOpen: r['isOpen'],
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}