import 'package:flutter/material.dart';
import 'screens/login_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Food Finder',
      theme: ThemeData(
        primaryColor: const Color(0xFFDC6601),
        colorScheme: ColorScheme.fromSwatch(
          primarySwatch: MaterialColor(0xFFDC6601, {
            50: Color(0xFFDC6601).withOpacity(0.1),
            100: Color(0xFFDC6601).withOpacity(0.2),
            200: Color(0xFFDC6601).withOpacity(0.3),
            300: Color(0xFFDC6601).withOpacity(0.4),
            400: Color(0xFFDC6601).withOpacity(0.5),
            500: Color(0xFFDC6601),
            600: Color(0xFFC45A01),
            700: Color(0xFFAC4E01),
            800: Color(0xFF944201),
            900: Color(0xFF7C3601),
          }),
          accentColor: const Color(0xFF241178),
          backgroundColor: Colors.white,
        ),
        scaffoldBackgroundColor: Colors.white,
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFFDC6601),
          foregroundColor: Colors.white,
          elevation: 0,
        ),
        useMaterial3: true,
      ),
      home: const LoginScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}