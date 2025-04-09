// lib/providers/cart_provider.dart

import 'package:flutter/foundation.dart';
import '../models/cart_item.dart';
import '../models/schema.dart';
import '../services/api_service.dart';

class CartProvider with ChangeNotifier {
  List<CartItem> _items = [];
  bool _isLoading = false;
  String? _error;

  List<CartItem> get items => _items;
  int get itemCount => _items.length;
  bool get isLoading => _isLoading;
  String? get error => _error;

  double get totalAmount {
    return _items.fold(0.0, (sum, item) => sum + (item.price * item.quantity));
  }

  // Récupère le panier depuis l'API
  // Ajoutez ces logs dans votre CartProvider.loadCart()
  Future<void> loadCart() async {
    print("CartProvider - loadCart started");
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      if (!ApiService.isLoggedIn()) {
        print("CartProvider - User not logged in");
        _items = [];
        return;
      }

      final result = await ApiService.getCart();
      print("CartProvider - API response: $result");

      if (result['success']) {
        final List<dynamic> cartItems = result['items'];
        print("CartProvider - Received ${cartItems.length} items");
        _items = cartItems.map((item) => CartItem.fromJson(item)).toList();
      } else {
        _error = result['message'];
        print("CartProvider - Error message: $_error");
      }
    } catch (e) {
      _error = 'Erreur lors du chargement du panier: $e';
      print("CartProvider - Exception: $_error");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Ajoute un schéma au panier via l'API
  Future<void> addItem(Schema schema, {int quantity = 1}) async {
    print(
      "CartProvider - Adding schema ${schema.id} (${schema.nom}) to cart, quantity: $quantity",
    );
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      print("CartProvider - Calling API to add item to cart");
      final result = await ApiService.addToCart(schema.id, quantity);
      print("CartProvider - API response: $result");

      if (result['success']) {
        print("CartProvider - Item added successfully, reloading cart");
        await loadCart(); // Recharge le panier depuis l'API
      } else {
        _error = result['message'];
        print("CartProvider - Error adding item: $_error");
        notifyListeners();
      }
    } catch (e) {
      _error = 'Erreur lors de l\'ajout au panier: $e';
      print("CartProvider - Exception adding item: $_error");
      notifyListeners();
    }
  }

  // Met à jour la quantité d'un schéma dans le panier via l'API
  Future<void> updateQuantity(int schemaId, int newQuantity) async {
    if (newQuantity < 1) return;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.updateCartItemQuantity(
        schemaId,
        newQuantity,
      );

      if (result['success']) {
        if (result['item'] != null) {
          // Update with the returned item data
          final CartItem updatedItem = CartItem.fromJson(result['item']);
          final index = _items.indexWhere((item) => item.schema?.id == schemaId);
          if (index != -1) {
            _items[index] = updatedItem;
          }
        } else {
          // Fallback to just updating the quantity locally
          final index = _items.indexWhere((item) => item.schema?.id == schemaId);
          if (index != -1) {
            _items[index].quantity = newQuantity;
          }
        }
      } else {
        _error = result['message'];
      }
    } catch (e) {
      _error = 'Erreur lors de la mise à jour du panier: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Supprime un schéma du panier via l'API
  Future<void> removeItem(int schemaId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      print("CartProvider - Removing schema with ID: $schemaId");
      final result = await ApiService.removeFromCart(schemaId);

      if (result['success']) {
        // Supprimer localement l'élément
        print("CartProvider - Item removed successfully, updating local state");
        _items.removeWhere((item) => item.schema?.id == schemaId);
        print("CartProvider - New item count: ${_items.length}");
      } else {
        _error = result['message'];
        print("CartProvider - Error removing item: $_error");
      }
    } catch (e) {
      _error = 'Erreur lors de la suppression du panier: $e';
      print("CartProvider - Exception removing item: $_error");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Vide le panier via l'API
  Future<void> clearCart() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.clearCart();

      if (result['success']) {
        _items = [];
      } else {
        _error = result['message'];
      }
    } catch (e) {
      _error = 'Erreur lors du vidage du panier: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
