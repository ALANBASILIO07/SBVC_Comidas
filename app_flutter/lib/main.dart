/*
* Nombre de la clase         : main.dart
* Descripción de la clase    : Punto de entrada principal de la aplicación SBVC_Comidas,
*                               configura el tema Material 3 y define la pantalla inicial.
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
import 'core/constants/app_colors.dart';
import 'features/auth/login_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SBVC Comidas',
      theme: ThemeData(
        primaryColor: AppColors.primary,
        colorScheme: ColorScheme.fromSwatch(
          primarySwatch: AppColors.createMaterialColor(AppColors.primary),
          accentColor: AppColors.secondary,
          backgroundColor: AppColors.background,
        ),
        scaffoldBackgroundColor: AppColors.background,
        appBarTheme: const AppBarTheme(
          backgroundColor: AppColors.secondary,
          foregroundColor: AppColors.textOnSecondary,
          elevation: 0,
        ),
        useMaterial3: true,
      ),
      home: const LoginScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}