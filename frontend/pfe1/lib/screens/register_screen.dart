import 'package:flutter/material.dart';
import 'package:pfe1/screens/login_screen.dart';
import '../services/api_service.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final TextEditingController firstNameController = TextEditingController();
  final TextEditingController lastNameController = TextEditingController();
  final TextEditingController cinController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController confirmPasswordController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController addressController = TextEditingController();
 
  bool _isLoading = false;
  String? _errorMessage;

  Future<void> _register() async {
    setState(() {
      _errorMessage = null;
      _isLoading = true;
    });

    String firstName = firstNameController.text.trim();
    String lastName = lastNameController.text.trim();
    String cin = cinController.text.trim();
    String email = emailController.text.trim();
    String password = passwordController.text.trim();
    String confirmPassword = confirmPasswordController.text.trim();
    String phone = phoneController.text.trim();
    String address = addressController.text.trim();
   
    // Client-side validation
    if (firstName.isEmpty || lastName.isEmpty || email.isEmpty || 
        password.isEmpty || confirmPassword.isEmpty || cin.isEmpty) {
      setState(() {
        _errorMessage = "Veuillez remplir tous les champs obligatoires";
        _isLoading = false;
      });
      return;
    }

    if (password != confirmPassword) {
      setState(() {
        _errorMessage = "Les mots de passe ne correspondent pas";
        _isLoading = false;
      });
      return;
    }

    final emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    if (!emailRegex.hasMatch(email)) {
      setState(() {
        _errorMessage = "Format d'email invalide";
        _isLoading = false;
      });
      return;
    }

    try {
      final result = await ApiService.register(
        firstName,
        lastName,
        cin,
        email,
        password,
        phone: phone.isNotEmpty ? phone : null,
        address: address.isNotEmpty ? address : null,
      );

      if (result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text("Inscription réussie"),
            backgroundColor: Colors.green,
            duration: Duration(seconds: 2),
          ),
        );
        await Future.delayed(const Duration(seconds: 2));
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const LoginScreen()),
        );
      } else {
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
          "Créer un compte",
          style: TextStyle(color: Colors.white),
        ),
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.red),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 20),
            
            if (_errorMessage != null)
              ErrorMessageWidget(message: _errorMessage!),
            
            // First Name Field
            TextField(
              controller: firstNameController,
              decoration: _inputDecoration("Prénom *", Icons.person),
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),
            
            // Last Name Field
            TextField(
              controller: lastNameController,
              decoration: _inputDecoration("Nom de famille *", Icons.person_outline),
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),
            
            // CIN Field
            TextField(
              controller: cinController,
              decoration: _inputDecoration("Numéro CIN *", Icons.credit_card),
              // keyboardType: TextInputType.number,
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),
            
            // Email Field
            TextField(
              controller: emailController,
              decoration: _inputDecoration("Email ", Icons.email),
              keyboardType: TextInputType.emailAddress,
              style: TextStyle(color: Colors.white),
            ),  
            const SizedBox(height: 15),

            // Password Field
            PasswordField(
              controller: passwordController,
              label: "Mot de passe *",
            ),
            const SizedBox(height: 15),
            
            // Confirm Password Field
            PasswordField(
              controller: confirmPasswordController,
              label: "Confirmer le mot de passe *",
              onSubmitted: (_) => _register(),
            ),
            const SizedBox(height: 25),

            // Phone Field
            TextField(
              controller: phoneController,
              decoration: _inputDecoration("Téléphone", Icons.phone),
              keyboardType: TextInputType.phone,
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),
            
            // Address Field
            TextField(
              controller: addressController,
              decoration: _inputDecoration("Adresse", Icons.location_on),
              keyboardType: TextInputType.streetAddress,
              style: TextStyle(color: Colors.white),
            ),
            const SizedBox(height: 15),
            
            RegisterButton(
              isLoading: _isLoading,
              onPressed: _register,
            ),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  InputDecoration _inputDecoration(String label, IconData icon) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: Color.fromARGB(255, 255, 255, 255)),
      border: OutlineInputBorder(
        borderSide: BorderSide(color: Colors.grey.shade800),
      ),
      enabledBorder: OutlineInputBorder(
        borderSide: BorderSide(color: Colors.grey.shade800),
      ),
      focusedBorder: const OutlineInputBorder(
        borderSide: BorderSide(color: Colors.red),
      ),
      prefixIcon: Icon(icon, color: Colors.red),
      filled: true,
      fillColor: Colors.grey[850],
    );
  }
}

class PasswordField extends StatefulWidget {
  final TextEditingController controller;
  final String label;
  final void Function(String)? onSubmitted;

  const PasswordField({
    super.key,
    required this.controller,
    required this.label,
    this.onSubmitted,
  });

  @override
  State<PasswordField> createState() => _PasswordFieldState();
}

class _PasswordFieldState extends State<PasswordField> {
  bool _obscureText = true;

  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: widget.controller,
      decoration: InputDecoration(
        labelText: widget.label,
        labelStyle: const TextStyle(color: Color.fromARGB(255, 255, 255, 255)),
        border: OutlineInputBorder(
          borderSide: BorderSide(color: Colors.grey.shade800),
        ),
        enabledBorder: OutlineInputBorder(
          borderSide: BorderSide(color: Colors.grey.shade800),
        ),
        focusedBorder: const OutlineInputBorder(
          borderSide: BorderSide(color: Colors.red),
        ),
        prefixIcon: const Icon(Icons.lock, color: Colors.red),
        suffixIcon: IconButton(
          icon: Icon(
            _obscureText ? Icons.visibility_off : Icons.visibility,
            color: Colors.grey,
          ),
          onPressed: () {
            setState(() {
              _obscureText = !_obscureText;
            });
          },
        ),
        filled: true,
        fillColor: Colors.grey[850],
      ),
      obscureText: _obscureText,
      onSubmitted: widget.onSubmitted,
      style: const TextStyle(color: Colors.white),
    );
  }
}

class ErrorMessageWidget extends StatelessWidget {
  final String message;

  const ErrorMessageWidget({super.key, required this.message});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(10),
      margin: const EdgeInsets.only(bottom: 20),
      decoration: BoxDecoration(
        color: Colors.red.shade900.withOpacity(0.3),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.red.shade800),
      ),
      child: Text(
        message,
        style: TextStyle(color: Colors.red.shade200),
      ),
    );
  }
}

class RegisterButton extends StatelessWidget {
  final bool isLoading;
  final VoidCallback onPressed;

  const RegisterButton({
    super.key,
    required this.isLoading,
    required this.onPressed,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 50,
      child: isLoading
          ? const Center(child: CircularProgressIndicator(color: Colors.red))
          : ElevatedButton(
              onPressed: onPressed,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                elevation: 5,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              child: const Text(
                "S'inscrire",
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
            ),
    );
  }
}