import 'package:flutter/material.dart';

class ProductItem {
  final int id;
  final String name;
  final String category;
  final double price;
  final double rating;
  final String imageUrl;

  ProductItem({
    required this.id,
    required this.name,
    required this.category,
    required this.price,
    required this.rating,
    required this.imageUrl,
  });
}

class SearchPage extends StatefulWidget {
  const SearchPage({super.key});

  @override
  _SearchPageState createState() => _SearchPageState();
}

class _SearchPageState extends State<SearchPage> {
  final TextEditingController _searchController = TextEditingController();
  String _searchQuery = '';
  List<ProductItem> _searchResults = [];
  List<String> _recentSearches = [
    'Product0',
    'Product1',
    'Product2',
    'Product3',
  ];
  bool _isLoading = false;
  bool _hasSearched = false;
  
  // Define filter options
  final List<String> _categories = ['Tous'];
  final List<String> _sortOptions = ['Pertinence', 'Prix croissant', 'Prix décroissant', 'Mieux notés'];
  String _selectedCategory = 'Tous';
  String _selectedSortOption = 'Pertinence';
  
  // Define the dark theme colors
  final Color _primaryColor = Colors.red;
  final Color _backgroundColor = Colors.grey[900]!;
  final Color _cardColor = Colors.grey[850]!;
  final Color _textColor = Colors.white;
  final Color _dividerColor = Colors.red.withOpacity(0.5);

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _performSearch(String query) async {
    if (query.trim().isEmpty) return;

    setState(() {
      _isLoading = true;
      _hasSearched = true;
      _searchQuery = query;
    });

    try {
      // Replace with your actual API call
      // final results = await ApiService.searchProducts(
      //   query: query,
      //   category: _selectedCategory != 'Tous' ? _selectedCategory : null,
      //   sortBy: _selectedSortOption,
      // );
      
      // For demo purposes, we'll use sample data
      await Future.delayed(const Duration(seconds: 1));
      final sampleResults = [
        ProductItem(
          id: 1,
          name: "Product0",
          category: "Parts",
          price: 59.99,
          rating: 4.5,
          imageUrl: "assets/images/Product0.jpg",
        ),
        ProductItem(
          id: 2,
          name: "Product1",
          category: "Parts",
          price: 49.99,
          rating: 4.2,
          imageUrl: "assets/images/Product1.jpg",
        ),
        ProductItem(
          id: 3,
          name: "Product2",
          category: "Parts",
          price: 39.99,
          rating: 4.0,
          imageUrl: "assets/images/Product2.jpg",
        ),
        ProductItem(
          id: 4,
          name: "Product3",
          category: "Parts",
          price: 29.99,
          rating: 3.8,
          imageUrl: "assets/images/Product3.jpg",
        ),
      ];

      // Save to recent searches if not already there
      if (!_recentSearches.contains(query)) {
        setState(() {
          _recentSearches.insert(0, query);
          if (_recentSearches.length > 5) {
            _recentSearches.removeLast();
          }
        });
      }

      setState(() {
        _searchResults = sampleResults;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("Erreur lors de la recherche: $e"),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _clearSearch() {
    setState(() {
      _searchController.clear();
      _searchQuery = '';
      _hasSearched = false;
    });
  }

  void _showFilterModal() {
    showModalBottomSheet(
      context: context,
      backgroundColor: _cardColor,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      builder: (context) => StatefulBuilder(
        builder: (context, setModalState) {
          return Container(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  children: [
                    const Text(
                      "Filtres",
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const Spacer(),
                    IconButton(
                      icon: const Icon(Icons.close),
                      onPressed: () => Navigator.of(context).pop(),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                const Text(
                  "Catégories",
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  children: _categories.map((category) {
                    return ChoiceChip(
                      label: Text(category),
                      selected: _selectedCategory == category,
                      onSelected: (selected) {
                        if (selected) {
                          setModalState(() {
                            _selectedCategory = category;
                          });
                        }
                      },
                      backgroundColor: _backgroundColor,
                      selectedColor: _primaryColor,
                    );
                  }).toList(),
                ),
                const SizedBox(height: 16),
                const Text(
                  "Trier par",
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  children: _sortOptions.map((option) {
                    return ChoiceChip(
                      label: Text(option),
                      selected: _selectedSortOption == option,
                      onSelected: (selected) {
                        if (selected) {
                          setModalState(() {
                            _selectedSortOption = option;
                          });
                        }
                      },
                      backgroundColor: _backgroundColor,
                      selectedColor: _primaryColor,
                    );
                  }).toList(),
                ),
                const SizedBox(height: 24),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          side: BorderSide(color: _primaryColor),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        onPressed: () {
                          setModalState(() {
                            _selectedCategory = 'Tous';
                            _selectedSortOption = 'Pertinence';
                          });
                        },
                        child: const Text("Réinitialiser"),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: _primaryColor,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        onPressed: () {
                          Navigator.of(context).pop();
                          if (_searchQuery.isNotEmpty) {
                            _performSearch(_searchQuery);
                          }
                        },
                        child: const Text("Appliquer"),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          );
        },
      ),
    );
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
          background: _backgroundColor,
        ),
        scaffoldBackgroundColor: _backgroundColor,
        dividerColor: _dividerColor,
        appBarTheme: AppBarTheme(
          backgroundColor: _backgroundColor,
          foregroundColor: _textColor,
          elevation: 0,
        ),
        inputDecorationTheme: InputDecorationTheme(
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8),
            borderSide: BorderSide(color: Colors.grey[800]!),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8),
            borderSide: BorderSide(color: _primaryColor, width: 2),
          ),
          filled: true,
          fillColor: Colors.grey[850],
          labelStyle: TextStyle(color: Colors.grey[400]),
          hintStyle: TextStyle(color: Colors.grey[500]),
        ),
      ),
      child: Scaffold(
        appBar: AppBar(
          title: const Text("Recherche"),
          centerTitle: true,
        ),
        body: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _searchController,
                      decoration: InputDecoration(
                        hintText: "Que recherchez-vous ?",
                        prefixIcon: const Icon(Icons.search),
                        suffixIcon: _searchController.text.isNotEmpty
                            ? IconButton(
                                icon: const Icon(Icons.clear),
                                onPressed: _clearSearch,
                              )
                            : null,
                        contentPadding: const EdgeInsets.symmetric(
                          vertical: 16,
                          horizontal: 16,
                        ),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      onSubmitted: _performSearch,
                      textInputAction: TextInputAction.search,
                      onChanged: (value) {
                        setState(() {});
                      },
                    ),
                  ),
                  const SizedBox(width: 8),
                  IconButton(
                    icon: Icon(
                      Icons.filter_list,
                      color: _primaryColor,
                    ),
                    onPressed: _showFilterModal,
                    style: IconButton.styleFrom(
                      backgroundColor: Colors.grey[800],
                      padding: const EdgeInsets.all(12),
                    ),
                  ),
                ],
              ),
            ),
            if (_hasSearched && _selectedCategory != 'Tous' ||
                _selectedSortOption != 'Pertinence')
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  children: [
                    if (_selectedCategory != 'Tous')
                      Chip(
                        label: Text(_selectedCategory),
                        backgroundColor: _primaryColor.withOpacity(0.2),
                        deleteIcon: Icon(Icons.close, size: 16),
                        onDeleted: () {
                          setState(() {
                            _selectedCategory = 'Tous';
                          });
                          _performSearch(_searchQuery);
                        },
                      ),
                    if (_selectedSortOption != 'Pertinence')
                      Padding(
                        padding: const EdgeInsets.only(left: 8),
                        child: Chip(
                          label: Text(_selectedSortOption),
                          backgroundColor: _primaryColor.withOpacity(0.2),
                          deleteIcon: Icon(Icons.close, size: 16),
                          onDeleted: () {
                            setState(() {
                              _selectedSortOption = 'Pertinence';
                            });
                            _performSearch(_searchQuery);
                          },
                        ),
                      ),
                  ],
                ),
              ),
            if (_isLoading)
              const Padding(
                padding: EdgeInsets.all(16),
                child: CircularProgressIndicator(),
              )
            else if (_searchResults.isEmpty && _hasSearched)
              Padding(
                padding: const EdgeInsets.all(16),
                child: Text(
                  "Aucun résultat trouvé pour '$_searchQuery'",
                  style: TextStyle(color: _textColor),
                ),
              )
            else if (_searchResults.isNotEmpty)
              Expanded(
                child: ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _searchResults.length,
                  itemBuilder: (context, index) {
                    final product = _searchResults[index];
                    return Card(
                      color: _cardColor,
                      child: ListTile(
                        leading: Image.asset(
                          product.imageUrl,
                          width: 50,
                          height: 50,
                          fit: BoxFit.cover,
                        ),
                        title: Text(product.name, style: TextStyle(color: _textColor)),
                        subtitle: Text(
                          "${product.category} - ${product.price}€",
                          style: TextStyle(color: Colors.grey[400]),
                        ),
                        trailing: Icon(
                          Icons.star,
                          color: Colors.amber,
                          size: 16,
                        ),
                        onTap: () {
                          // Navigate to product details
                        },
                      ),
                    );
                  },
                ),
              )
            else
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.all(16),
                      child: Text(
                        "Recherches récentes",
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: _textColor,
                        ),
                      ),
                    ),
                    Expanded(
                      child: ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: _recentSearches.length,
                        itemBuilder: (context, index) {
                          final search = _recentSearches[index];
                          return ListTile(
                            leading: Icon(Icons.history, color: _textColor),
                            title: Text(search, style: TextStyle(color: _textColor)),
                            onTap: () {
                              _searchController.text = search;
                              _performSearch(search);
                            },
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),
          ],
        ),
      ),
    );
  }
}