// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import '../services/api_service.dart'; // Import your ApiService

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  // ignore: library_private_types_in_public_api
  _ProfileScreenState createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  // User information
  String _firstName = "";
  String _lastName = "";
  String _userEmail = "";
  String _userPhone = "";
  String _userAddress = "";
  String _userCni = "";
  bool _isLoading = false;

  // Controllers for profile update
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _cniController = TextEditingController();

  // Controllers for password change
  final TextEditingController _currentPasswordController = TextEditingController();
  final TextEditingController _newPasswordController = TextEditingController();

  // Define the dark theme colors
  final Color _primaryColor = Colors.red;
  final Color _backgroundColor = Colors.grey[900]!;
  final Color _cardColor = Colors.grey[850]!;
  final Color _textColor = Colors.white;
  // ignore: deprecated_member_use
  final Color _dividerColor = Colors.red.withOpacity(0.5);

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    // Recharger les données à chaque fois que l'écran est affiché
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    if (!mounted) return;
    
    setState(() {
      _isLoading = true;
    });

    try {
      // Toujours récupérer les données les plus récentes depuis l'API
      Map<String, dynamic>? currentUser = ApiService.getCurrentUser();
      
      if (currentUser != null) {
        int userId = currentUser['id'] ?? 1;
        final userData = await ApiService.getUserProfile(userId);
        
        if (userData.containsKey("error")) {
          throw Exception(userData['error']);
        }
        
        // Mettre à jour l'état avec les données récentes
        if (mounted) {
          setState(() {
            _firstName = userData['firstname'] ?? "";
            _lastName = userData['lastname'] ?? "";
            _userCni = userData['cin'] ?? "";
            _userEmail = userData['email'] ?? "";
            _userPhone = userData['phone'] ?? "";
            _userAddress = userData['address'] ?? "";

            // Pré-remplir les contrôleurs
            _firstNameController.text = _firstName;
            _lastNameController.text = _lastName;
            _emailController.text = _userEmail;
            _phoneController.text = _userPhone;
            _addressController.text = _userAddress;
            _cniController.text = _userCni;
          });
        }
      } else {
        // L'utilisateur n'est pas connecté, gérer cette situation
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text("Aucun utilisateur connecté"),
              backgroundColor: Colors.orange,
            ),
          );
          
          // Réinitialiser tous les champs
          setState(() {
            _firstName = "";
            _lastName = "";
            _userCni = "";
            _userEmail = "";
            _userPhone = "";
            _userAddress = "";
            
            _firstNameController.clear();
            _lastNameController.clear();
            _emailController.clear();
            _phoneController.clear();
            _addressController.clear();
            _cniController.clear();
          });
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Erreur lors du chargement du profil: $e"),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Theme(
      data: ThemeData.dark().copyWith(
        primaryColor: _primaryColor,
        colorScheme: ColorScheme.dark(
          primary: _primaryColor,
          secondary: _primaryColor,
          surface: _cardColor,
          // ignore: deprecated_member_use
          background: _backgroundColor,
        ),
        scaffoldBackgroundColor: _backgroundColor,
        dividerColor: _dividerColor,
        appBarTheme: AppBarTheme(
          backgroundColor: _backgroundColor,
          foregroundColor: _textColor,
          elevation: 0,
        ),
        dialogTheme: DialogTheme(
          backgroundColor: _cardColor,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        inputDecorationTheme: InputDecorationTheme(
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          labelStyle: TextStyle(color: Colors.grey[400]),
        ),
      ),
      child: Scaffold(
        appBar: AppBar(
          title: const Text("Profil"),
          centerTitle: true,
          actions: [
            // Bouton de rafraîchissement pour forcer le rechargement des données
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: _loadUserData,
            ),
          ],
        ),
        body: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // User profile header
                    _buildUserProfileHeader(),
                    const SizedBox(height: 24),
                    _buildSectionTitle("Informations Personnelles"),
                    _buildUserInfoCard(
                      icon: Icons.person,
                      title: "Prénom",
                      value: _firstName,
                    ),
                    _buildUserInfoCard(
                      icon: Icons.person_outline,
                      title: "Nom",
                      value: _lastName,
                    ),
                    _buildUserInfoCard(
                      icon: Icons.credit_card,
                      title: "CNI",
                      value: _userCni,
                    ),
                    _buildUserInfoCard(
                      icon: Icons.email,
                      title: "Email",
                      value: _userEmail,
                    ),
                    _buildUserInfoCard(
                      icon: Icons.phone,
                      title: "Téléphone",
                      value: _userPhone,
                    ),
                    _buildUserInfoCard(
                      icon: Icons.home,
                      title: "Adresse",
                      value: _userAddress,
                    ),
                    const SizedBox(height: 16),
                    _buildSectionTitle("Actions"),
                    _buildSettingItem(
                      icon: Icons.edit,
                      title: "Modifier le profil",
                      onTap: _showUpdateProfileDialog,
                    ),
                    _buildSettingItem(
                      icon: Icons.lock,
                      title: "Changer le mot de passe",
                      onTap: _showChangePasswordDialog,
                    ),
                    
                  ],
                ),
              ),
      ),
    );
  }

  Widget _buildUserProfileHeader() {
    return Center(
      child: Column(
        children: [
          CircleAvatar(
            radius: 50,
            backgroundColor: _primaryColor,
            child: Text(
              _firstName.isNotEmpty
                  ? _firstName.substring(0, 1).toUpperCase()
                  : "?",
              style: const TextStyle(
                fontSize: 40,
                color: Colors.white,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            "$_firstName $_lastName", // Display full name
            style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            _userEmail,
            style: TextStyle(
              fontSize: 16,
              color: Colors.grey[400],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildUserInfoCard({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Card(
      color: _cardColor,
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(icon, color: _primaryColor),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: 14,
                      color: Colors.grey[400],
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    value.isEmpty ? "Non renseigné" : value,
                    style: const TextStyle(fontSize: 16),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Text(
        title,
        style: TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
          color: _primaryColor,
        ),
      ),
    );
  }

  Widget _buildSettingItem({
    required IconData icon,
    required String title,
    Widget? trailing,
    VoidCallback? onTap,
  }) {
    return Card(
      color: _cardColor,
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      child: ListTile(
        leading: Icon(icon, color: _primaryColor),
        title: Text(title),
        trailing: trailing ??
            Icon(
              Icons.arrow_forward_ios,
              size: 16,
              color: Colors.grey[600],
            ),
        onTap: onTap,
      ),
    );
  }

  // Show dialog for updating profile
  void _showUpdateProfileDialog() {
    // Pre-fill with current values
    _firstNameController.text = _firstName;
    _lastNameController.text = _lastName;
    _emailController.text = _userEmail;
    _phoneController.text = _userPhone;
    _addressController.text = _userAddress;
    _cniController.text = _userCni;

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(
            "Modifier le profil",
            style: TextStyle(color: _primaryColor),
          ),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: _firstNameController,
                  decoration: const InputDecoration(labelText: 'Prénom'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _lastNameController,
                  decoration: const InputDecoration(labelText: 'Nom de famille'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _emailController,
                  decoration: const InputDecoration(labelText: 'Email'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _cniController,
                  decoration: const InputDecoration(labelText: 'CIN'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _phoneController,
                  decoration: const InputDecoration(labelText: 'Téléphone'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _addressController,
                  decoration: const InputDecoration(labelText: 'Adresse'),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text("Annuler", style: TextStyle(color: Colors.grey[400])),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: _primaryColor,
              ),
              onPressed: () async {
                // Récupérer l'ID de l'utilisateur actuel
                final currentUser = ApiService.getCurrentUser();
                final userId = currentUser?['id'] ?? 1;
                
                final result = await ApiService.updateProfile(
                  userId,
                  _firstNameController.text,
                  _lastNameController.text,
                  _emailController.text,
                  phone: _phoneController.text,
                  address: _addressController.text,
                  // cin: _cniController.text,
                );

                if (result["success"]) {
                  // Recharger les données du profil depuis l'API
                  await _loadUserData();
                  
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text("Profil mis à jour avec succès"),
                      backgroundColor: Colors.green,
                    ),
                  );
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text(
                        "Échec de la mise à jour: ${result["message"]}",
                      ),
                      backgroundColor: Colors.red,
                    ),
                  );
                }
                Navigator.of(context).pop();
              },
              child: const Text("Enregistrer"),
            ),
          ],
        );
      },
    );
  }

  // Show dialog for changing password
  void _showChangePasswordDialog() {
    _currentPasswordController.clear();
    _newPasswordController.clear();

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(
            "Changer le mot de passe",
            style: TextStyle(color: _primaryColor),
          ),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: _currentPasswordController,
                  decoration: const InputDecoration(
                    labelText: 'Mot de passe actuel',
                  ),
                  obscureText: true,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _newPasswordController,
                  decoration: const InputDecoration(
                    labelText: 'Nouveau mot de passe',
                  ),
                  obscureText: true,
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text("Annuler", style: TextStyle(color: Colors.grey[400])),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: _primaryColor,
              ),
              onPressed: () async {
                final currentUser = ApiService.getCurrentUser();
                final userId = currentUser?['id'] ?? 1;

                final result = await ApiService.changePassword(
                  userId,
                  _currentPasswordController.text,
                  _newPasswordController.text,
                );

                if (result["success"]) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text("Mot de passe changé avec succès"),
                      backgroundColor: Colors.green,
                    ),
                  );
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text(
                        "Échec du changement: ${result["message"]}",
                      ),
                      backgroundColor: Colors.red,
                    ),
                  );
                }

                Navigator.of(context).pop();
              },
              child: const Text("Enregistrer"),
            ),
          ],
        );
      },
    );
  }
}
  
