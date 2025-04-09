// lib/main.dart (modifié)

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter/services.dart';
import 'screens/login_screen.dart';
import 'providers/moto_provider.dart';
import 'providers/cart_provider.dart';
import 'providers/commande_provider.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (ctx) => MotoProvider()),
        ChangeNotifierProvider(create: (ctx) => CartProvider()),
        ChangeNotifierProvider(create: (ctx) => CommandeProvider()), 
      ],
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        title: 'Moto Parts Shop',
        theme: ThemeData(
          primaryColor: Colors.red,
          colorScheme: ColorScheme.dark(
            primary: Colors.red,
            secondary: Colors.red,
            surface: Colors.grey[850]!,
            background: Colors.black,
          ),
          scaffoldBackgroundColor: Colors.black,
          appBarTheme: AppBarTheme(
            backgroundColor: Colors.grey[900],
            foregroundColor: Colors.white,
          ),
          textTheme: TextTheme(
            bodyLarge: TextStyle(color: Colors.white),
            bodyMedium: TextStyle(color: Colors.white),
          ),
        ),
        home: LoginScreen(),
      ),
    );
  }
}