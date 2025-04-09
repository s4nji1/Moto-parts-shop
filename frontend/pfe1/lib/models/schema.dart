import 'package:pfe1/models/moto.dart';
import 'package:pfe1/services/api_service.dart';

class Schema {
  final int id;
  final String nom;
  final int? parentId;
  final String version;
  final double price;
  final int? motoId;
  final String? image;
  final String? serialNumber;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final Moto? moto;
  final Schema? parent;

  Schema({
    required this.id,
    required this.nom,
    this.parentId,
    required this.version,
    required this.price,
    this.motoId,
    this.image,
    this.serialNumber,
    this.createdAt,
    this.updatedAt,
    this.moto,
    this.parent,
  });

  factory Schema.fromJson(Map<String, dynamic> json) {
    double parsePrice(dynamic value) {
      if (value == null) return 0.0;
      if (value is double) return value;
      if (value is int) return value.toDouble();
      if (value is String) {
        try {
          return double.parse(value);
        } catch (e) {
          print("Erreur lors de la conversion du prix: $e");
          return 0.0;
        }
      }
      return 0.0;
    }

    return Schema(
      id: json['id'],
      nom: json['nom'] ?? 'Sans nom',
      parentId: json['parent_id'],
      version: json['version'] ?? '1.0',
      price: parsePrice(json['price']),
      motoId: json['moto_id'],
      image: json['image'],
      serialNumber: json['serial_number'],
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : null,
      updatedAt: json['updated_at'] != null 
          ? DateTime.parse(json['updated_at']) 
          : null,
      moto: json['moto'] != null ? Moto.fromJson(json['moto']) : null,
      parent: json['parent'] != null ? Schema.fromJson(json['parent']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nom': nom,
      'parent_id': parentId,
      'version': version,
      'price': price,
      'moto_id': motoId,
      'image': image,
      'serial_number': serialNumber,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
      'moto': moto?.toJson(),
      'parent': parent?.toJson(),
    };
  }
  
  // Méthode pour obtenir l'URL complète de l'image
  String? getImageUrl() {
    if (image == null || image!.isEmpty) {
      return null;
    }
    
    return ApiService.getImageUrl(image!);
  }
}