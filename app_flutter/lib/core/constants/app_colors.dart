/*
* Nombre de la clase         : app_colors.dart
* Descripción de la clase    : Define la paleta de colores oficial del sistema SBVC_Comidas
*                               según los estándares del Manual de Programación Flutter V3.7.
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

/// Paleta de colores oficial SBVC_Comidas
/// Según Manual_PRO_Flutter_V3.7.pdf
class AppColors {
  // Color primario (Naranja)
  static const Color primary = Color(0xFFDE6A01);

  // Colores secundarios (Azules)
  static const Color secondary = Color(0xFF241F78);
  static const Color secondaryDark = Color(0xFF1A0D5A);

  // Color de éxito (Verde)
  static const Color success = Color(0xFF42A958);

  // Color de error (Rojo)
  static const Color error = Color(0xFFED0000);

  // Colores base
  static const Color black = Color(0xFF000000);
  static const Color white = Color(0xFFFFFFFF);

  // Escala de grises
  static const Color grey500 = Color(0xFF9E9E9E);
  static const Color grey400 = Color(0xFFBDBDBD);
  static const Color grey300 = Color(0xFFE0E0E0);
  static const Color grey200 = Color(0xFFEEEEEE);
  static const Color grey100 = Color(0xFFF5F5F5);

  // Colores de fondo
  static const Color background = white;
  static const Color surface = white;
  static const Color surfaceVariant = grey100;

  // Colores de texto
  static const Color textPrimary = black;
  static const Color textSecondary = grey500;
  static const Color textOnPrimary = white;
  static const Color textOnSecondary = white;

  // Colores de bordes
  static const Color border = grey300;
  static const Color borderLight = grey200;

  // Colores de estado
  static const Color active = success;
  static const Color inactive = grey400;
  static const Color warning = Color(0xFFFF9800);
  static const Color info = Color(0xFF2196F3);

  // Sombras
  static const Color shadow = Color(0x1A000000);
  static const Color shadowLight = Color(0x0D000000);

  /// Genera MaterialColor desde Color para ThemeData
  static MaterialColor createMaterialColor(Color color) {
    List<int> strengths = <int>[50, 100, 200, 300, 400, 500, 600, 700, 800, 900];
    Map<int, Color> swatch = <int, Color>{};
    final int r = color.red;
    final int g = color.green;
    final int b = color.blue;

    for (int strength in strengths) {
      final double ds = 0.5 - strength / 1000;
      swatch[strength] = Color.fromRGBO(
        r + ((ds < 0 ? r : (255 - r)) * ds).round(),
        g + ((ds < 0 ? g : (255 - g)) * ds).round(),
        b + ((ds < 0 ? b : (255 - b)) * ds).round(),
        1,
      );
    }
    return MaterialColor(color.value, swatch);
  }
}
