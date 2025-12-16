/*
* Nombre de la clase         : restaurant_model.dart
* Descripción de la clase    : Modelos de datos para restaurantes y usuarios del sistema,
*                               define las estructuras para la información de establecimientos.
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

/// Modelo de datos para un restaurante
class Restaurant {
  final String id;
  final String name;
  final double latitude;
  final double longitude;
  final String address;
  final bool isOpen;
  final String openingTime;
  final String closingTime;
  final List<String> workingDays;
  final List<String> paymentMethods;
  final List<String> availableFoods;
  final bool hasInvoicing;
  final double rating;

  Restaurant({
    required this.id,
    required this.name,
    required this.latitude,
    required this.longitude,
    required this.address,
    required this.isOpen,
    required this.openingTime,
    required this.closingTime,
    required this.workingDays,
    required this.paymentMethods,
    required this.availableFoods,
    required this.hasInvoicing,
    required this.rating,
  });

  /// Crea un Restaurant desde JSON (para API)
  factory Restaurant.fromJson(Map<String, dynamic> json) {
    return Restaurant(
      id: json['id'].toString(),
      name: json['nombre_establecimiento'] ?? '',
      latitude: double.tryParse(json['latitud']?.toString() ?? '0') ?? 0.0,
      longitude: double.tryParse(json['longitud']?.toString() ?? '0') ?? 0.0,
      address: json['direccion'] ?? '',
      isOpen: json['is_open'] ?? false,
      openingTime: json['hora_apertura'] ?? '09:00',
      closingTime: json['hora_cierre'] ?? '22:00',
      workingDays: (json['dias_laborales'] as String?)?.split(',') ?? [],
      paymentMethods: (json['metodos_pago'] as String?)?.split(',') ?? [],
      availableFoods: (json['comidas_disponibles'] as String?)?.split(',') ?? [],
      hasInvoicing: json['facturacion'] ?? false,
      rating: double.tryParse(json['calificacion_promedio']?.toString() ?? '0') ?? 0.0,
    );
  }

  /// Convierte el Restaurant a JSON (para envío a API)
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nombre_establecimiento': name,
      'latitud': latitude.toString(),
      'longitud': longitude.toString(),
      'direccion': address,
      'is_open': isOpen,
      'hora_apertura': openingTime,
      'hora_cierre': closingTime,
      'dias_laborales': workingDays.join(','),
      'metodos_pago': paymentMethods.join(','),
      'comidas_disponibles': availableFoods.join(','),
      'facturacion': hasInvoicing,
      'calificacion_promedio': rating,
    };
  }
}

/// Modelo de datos para un usuario
class User {
  final String name;
  final String email;
  final String plan; // 'basic' o 'premium'

  User({
    required this.name,
    required this.email,
    required this.plan,
  });

  /// Crea un User desde JSON (para API)
  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      plan: json['plan'] ?? 'basic',
    );
  }

  /// Convierte el User a JSON (para envío a API)
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'email': email,
      'plan': plan,
    };
  }
}
