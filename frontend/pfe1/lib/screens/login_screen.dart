import 'package:flutter/material.dart';
import 'package:pfe1/screens/home_page.dart';
import 'package:pfe1/screens/register_screen.dart';
import 'package:pfe1/services/api_service.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  // ignore: library_private_types_in_public_api
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController CinController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  bool _isLoading = false;
  String? _errorMessage;

  // Fonction pour traiter la connexion
  void _login() async {
    String Cin = CinController.text.trim();
    String password = passwordController.text.trim();

    // Validation des champs
    if (Cin.isEmpty || password.isEmpty) {
      setState(() {
        _errorMessage = "Veuillez remplir tous les champs";
      });
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      // Appel à l'API pour la connexion
      final result = await ApiService.login(Cin, password);

      if (result['success'] == true) {
        // Connexion réussie
        // ignore: use_build_context_synchronously
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Connexion réussie"),
            backgroundColor: Colors.green,
            duration: Duration(seconds: 2),
          ),
        );

        // Rediriger vers la page d'accueil
        // ignore: use_build_context_synchronously
        await Future.delayed(const Duration(seconds: 2));
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const HomePage()),
        );
      } else {
        // Afficher le message d'erreur renvoyé par le serveur
        setState(() {
          _errorMessage = result['message'] ?? "Une erreur est survenue";
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = "Erreur de connexion: $e";
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.grey[900],
        title: const Text(
          "Se Connecter ",
          style: TextStyle(color: Colors.white),
        ),
        centerTitle: true,
        iconTheme: IconThemeData(color: Colors.red),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 20),
            
            // Logo ou image de marque
            Container(
              alignment: Alignment.center,
              margin: const EdgeInsets.only(bottom: 30),
              child: Icon(
                Icons.motorcycle,
                size: 80,
                color: Colors.red,
              ),
            ),

            // Affichage du message d'erreur
            if (_errorMessage != null)
              Container(
                padding: const EdgeInsets.all(10),
                margin: const EdgeInsets.only(bottom: 20),
                decoration: BoxDecoration(
                  color: Colors.red.shade900.withOpacity(0.3),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: Colors.red.shade800),
                ),
                child: Text(
                  _errorMessage!,
                  style: TextStyle(color: Colors.red.shade200),
                ),
              ),

            TextField(
              controller: CinController,
              decoration: InputDecoration(
                labelText: "CIN",
                labelStyle: TextStyle(color: Colors.grey),
                border: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.grey.shade800),
                ),
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.grey.shade800),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.red),
                ),
                prefixIcon: Icon(Icons.credit_card, color: Colors.red),
                filled: true,
                fillColor: Colors.grey[850],
              ),
              keyboardType: TextInputType.emailAddress,
              textInputAction: TextInputAction.next,
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),

            // Champ de mot de passe
            TextField(
              controller: passwordController,
              decoration: InputDecoration(
                labelText: "Mot de passe",
                labelStyle: TextStyle(color: Colors.grey),
                border: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.grey.shade800),
                ),
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.grey.shade800),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.red),
                ),
                prefixIcon: Icon(Icons.lock, color: Colors.red),
                filled: true,
                fillColor: Colors.grey[850],
              ),
              obscureText: true,
              textInputAction: TextInputAction.done,
              onSubmitted: (_) => _login(),
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 25),

            // Bouton de connexion
            SizedBox(
              height: 50,
              child: _isLoading
                  ? Center(child: CircularProgressIndicator(color: Colors.red))
                  : ElevatedButton(
                      onPressed: _login,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.red,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        elevation: 5,
                      ),
                      child: const Text(
                        "Se connecter",
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                    ),
            ),
            const SizedBox(height: 15),

            // Bouton pour s'inscrire
            TextButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const RegisterScreen()),
                );
              },
              style: TextButton.styleFrom(
                foregroundColor: Colors.red,
              ),
              child: const Text(
                "Pas de compte ? S'inscrire",
                style: TextStyle(
                  fontSize: 14,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}