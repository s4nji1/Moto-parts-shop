import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'search_page.dart';
import 'profile_screen.dart';
import 'mes_motos_screen.dart';
import 'mes_commandes_screen.dart';
import 'cart_page.dart';
import 'login_screen.dart';
import '../models/moto.dart';
import '../services/api_service.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'moto_details_screen.dart'; // Importez le nouvel écran de détails

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _selectedIndex = 0;
  bool _showSearchBar = false;
  final TextEditingController _searchController = TextEditingController();
  bool _isSearching = false;
  List<Moto> _motos = []; // Liste pour stocker les motos
  bool _isLoading = true; // État de chargement

  final List<Widget> _pages = [
    const HomeContent(), // Page d'accueil
    MesMotosScreen(), // Page Mes Motos
    ProfileScreen(), // Page du profile
    MesCommandesScreen(), // Page Mes Commandes
  ];

  @override
  void initState() {
    super.initState();
    testImageAccess('http://10.0.2.2:8000/storage/motos/1742657296.jpg');
    _fetchMotos(); // Récupérer les motos lors de l'initialisation de la page
  }

  // Fonction pour tester l'accès aux images
  Future<void> testImageAccess(String url) async {
    try {
      final response = await http.get(Uri.parse(url));
      print('Test d\'accès à l\'image: $url');
      print('Code de statut: ${response.statusCode}');
      print('Taille de la réponse: ${response.bodyBytes.length} octets');
    } catch (e) {
      print('Erreur de test d\'accès à l\'image: $e');
    }
  }

  // Méthode pour récupérer les motos depuis l'API
  Future<void> _fetchMotos() async {
    try {
      final motos = await ApiService.getAllMotos();
      print('Motos récupérées: ${motos.length}');
      setState(() {
        _motos = motos;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      print('Error fetching motorcycles: $e');
    }
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;

      // Si la barre de recherche est ouverte, la fermer lors d'un changement d'onglet
      if (_showSearchBar) {
        _toggleSearch();
      }
    });

    if (index == 4) {
      _logout();
    }
  }

  void _toggleSearch() {
    setState(() {
      _showSearchBar = !_showSearchBar;
      _isSearching = !_isSearching;
      if (!_showSearchBar) {
        _searchController.clear();
      }
    });
  }

  void _navigateToSearch() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => SearchPage()),
    );
  }

  void _navigateToCart() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => CartPage()),
    );
  }

  void _logout() {
    ApiService.logout().then((_) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => LoginScreen()),
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.grey[900],
        title:
            _showSearchBar
                ? AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  width: double.infinity,
                  height: 40,
                  decoration: BoxDecoration(
                    color: Colors.grey[800],
                    borderRadius: BorderRadius.circular(20),
                    boxShadow: const [
                      BoxShadow(
                        color: Colors.black38,
                        blurRadius: 5,
                        offset: Offset(0, 2),
                      ),
                    ],
                  ),
                  child: TextField(
                    controller: _searchController,
                    decoration: InputDecoration(
                      hintText: 'Rechercher des pièces...',
                      hintStyle: TextStyle(color: Colors.grey[400]),
                      border: InputBorder.none,
                      prefixIcon: const Icon(Icons.search, color: Colors.red),
                      suffixIcon:
                          _isSearching
                              ? IconButton(
                                icon: const Icon(
                                  Icons.close,
                                  color: Colors.red,
                                ),
                                onPressed: _toggleSearch,
                              )
                              : null,
                    ),
                    style: const TextStyle(color: Colors.white),
                    autofocus: true,
                    onChanged: (value) {
                      // Ajoutez ici la logique de recherche
                    },
                    onSubmitted: (value) {
                      if (value.isNotEmpty) {
                        _navigateToSearch();
                      }
                    },
                  ),
                )
                : const Text(
                  'Moto Parts Shopping',
                  style: TextStyle(color: Colors.white),
                ),
        iconTheme: const IconThemeData(color: Colors.red),
        actions: [
          IconButton(
            icon: const Icon(Icons.search, color: Colors.red),
            onPressed: _showSearchBar ? null : _toggleSearch,
          ),
          Stack(
            alignment: Alignment.center,
            children: [
              IconButton(
                icon: const Icon(Icons.shopping_cart, color: Colors.red),
                onPressed: _navigateToCart,
              ),
              Positioned(
                top: 8,
                right: 8,
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(
                    color: Colors.red,
                    shape: BoxShape.circle,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
      body:
          _selectedIndex == 0
              ? _isLoading
                  ? const Center(
                    child: CircularProgressIndicator(color: Colors.red),
                  )
                  : HomeContent(motos: _motos)
              : _pages[_selectedIndex],
      bottomNavigationBar: BottomNavigationBar(
        backgroundColor: Colors.grey[900],
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
        selectedItemColor: Colors.red,
        unselectedItemColor: Colors.grey,
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Accueil'),
          BottomNavigationBarItem(
            icon: Icon(Icons.motorcycle),
            label: 'Mes Motos',
          ),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
          BottomNavigationBarItem(
            icon: Icon(Icons.receipt_long),
            label: 'Mes Commandes',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.logout),
            label: 'Déconnexion',
          ),
        ],
      ),
    );
  }
}

class HomeContent extends StatelessWidget {
  final List<Moto> motos;

  const HomeContent({Key? key, this.motos = const []}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: double.infinity,
              height: 150,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(10),
                gradient: LinearGradient(
                  colors: [Colors.red.shade900, Colors.red.shade700],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                color: Colors.red.shade800,
              ),
              child: const Center(
                child: Text(
                  'Bienvenue sur Moto Parts',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    shadows: [
                      Shadow(
                        color: Colors.black,
                        offset: Offset(1, 1),
                        blurRadius: 3,
                      ),
                    ],
                  ),
                ),
              ),
            ),

            // Section principale - Affichage des motos
            const SizedBox(height: 20),
            const Text(
              'Recommandé pour vous',
              style: TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 16),

            // Affichage vertical des motos
            motos.isEmpty
                ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      children: [
                        Icon(
                          Icons.motorcycle_outlined,
                          size: 60,
                          color: Colors.grey[600],
                        ),
                        const SizedBox(height: 16),
                        Text(
                          'Aucune moto disponible',
                          style: TextStyle(
                            color: Colors.grey[500],
                            fontSize: 18,
                          ),
                        ),
                      ],
                    ),
                  ),
                )
                : ListView.builder(
                    shrinkWrap: true,
                    physics: NeverScrollableScrollPhysics(),
                    itemCount: motos.length,
                    itemBuilder: (context, index) {
                      return _buildMotoCard(context, motos[index]);
                    },
                  ),
          ],
        ),
      ),
    );
  }

  // Méthode modifiée pour construire une carte de moto en utilisant le model
  // avec layout vertical et un bouton détails fonctionnel
  Widget _buildMotoCard(BuildContext context, Moto moto) {
    String imageUrl = moto.getImageUrl();

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
      ),
      color: Colors.grey[800],
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image de la moto
          ClipRRect(
            borderRadius: const BorderRadius.vertical(top: Radius.circular(10)),
            child: SizedBox(
              height: 180,
              width: double.infinity,
              child: CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                placeholder: (context, url) => Center(
                  child: CircularProgressIndicator(color: Colors.red),
                ),
                errorWidget: (context, url, error) => Container(
                  color: Colors.grey[700],
                  child: const Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.broken_image,
                          size: 40,
                          color: Colors.red,
                        ),
                        SizedBox(height: 4),
                        Text(
                          'Image indisponible',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),

          // Informations de la moto
          Padding(
            padding: const EdgeInsets.all(12.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      moto.model?.marque ?? 'Marque inconnue',
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      'Année: ${moto.model?.annee ?? 'Inconnue'}',
                      style: TextStyle(
                        color: Colors.grey[400],
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
                
                // Bouton Détails fonctionnel
                ElevatedButton(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => MotoDetailsScreen(moto: moto),
                      ),
                    );
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: const Text(
                    'Détails',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}