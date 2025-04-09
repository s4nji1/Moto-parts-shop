// lib/providers/moto_provider.dart

import 'package:flutter/foundation.dart';
import '../models/moto.dart';
import '../models/model_moto.dart';
import '../services/api_service.dart';

class MotoProvider with ChangeNotifier {
  List<Moto> _motos = [];
  List<ModelMoto> _availableModels = [];
  bool _isLoading = false;
  String? _error;

  List<Moto> get motos => _motos;
  List<ModelMoto> get availableModels => _availableModels;
  bool get isLoading => _isLoading;
  String? get error => _error;

  // Charge les motos du client
  Future<void> loadClientMotos() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _motos = await ApiService.getClientMotos();
    } catch (e) {
      _error = "Erreur lors du chargement des motos: $e";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Charge les modèles disponibles
  Future<void> loadAvailableModels() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _availableModels = await ApiService.getAllModels();
    } catch (e) {
      _error = "Erreur lors du chargement des modèles: $e";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Ajoute une moto
  Future<Map<String, dynamic>> addMoto(int modelId, String? image) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.addMoto(modelId, image);
      
      if (result['success']) {
        await loadClientMotos(); // Recharge les motos après ajout
      } else {
        _error = result['message'];
      }
      
      return result;
    } catch (e) {
      _error = "Erreur lors de l'ajout de la moto: $e";
      return {"success": false, "message": _error};
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Supprime une moto
  Future<Map<String, dynamic>> deleteMoto(int motoId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.deleteMoto(motoId);
      
      if (result['success']) {
        _motos.removeWhere((moto) => moto.id == motoId);
      } else {
        _error = result['message'];
      }
      
      return result;
    } catch (e) {
      _error = "Erreur lors de la suppression de la moto: $e";
      return {"success": false, "message": _error};
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}