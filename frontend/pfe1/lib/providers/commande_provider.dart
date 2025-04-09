// lib/providers/commande_provider.dart

import 'package:flutter/foundation.dart';
import '../models/commande.dart';
import '../services/api_service.dart';

class CommandeProvider with ChangeNotifier {
  List<Commande> _commandes = [];
  bool _isLoading = false;
  String? _error;

  List<Commande> get commandes => _commandes;
  bool get isLoading => _isLoading;
  String? get error => _error;

  List<Commande> get pendingCommandes => _commandes
      .where((commande) => commande.status == 'en_attente')
      .toList();

  List<Commande> get inProgressCommandes => _commandes
      .where((commande) => commande.status == 'en_cours')
      .toList();

  List<Commande> get deliveredCommandes => _commandes
      .where((commande) => commande.status == 'livree')
      .toList();

  // Charge les commandes du client
  Future<void> loadCommandes() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _commandes = await ApiService.getClientCommandes();
    } catch (e) {
      _error = "Erreur lors du chargement des commandes: $e";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Récupère les détails d'une commande
  // Récupère les détails d'une commande
Future<Commande?> getCommandeDetails(int commandeId) async {
  try {
    // Assurez-vous que cette méthode retourne un Future<Commande?>
    return await ApiService.getCommandeDetails(commandeId);
  } catch (e) {
    _error = "Erreur lors du chargement des détails de la commande: $e";
    notifyListeners();
    return null;
  }
}

  // Annule une commande
  Future<Map<String, dynamic>> cancelCommande(int commandeId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.cancelCommande(commandeId);
      
      if (result['success']) {
        await loadCommandes(); // Recharge les commandes après annulation
      } else {
        _error = result['message'];
      }
      
      return result;
    } catch (e) {
      _error = "Erreur lors de l'annulation de la commande: $e";
      notifyListeners();
      return {"success": false, "message": _error};
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}