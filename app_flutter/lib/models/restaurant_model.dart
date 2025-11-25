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
}

class User {
  final String name;
  final String email;
  final String plan; // 'basic' o 'premium'

  User({
    required this.name,
    required this.email,
    required this.plan,
  });
}