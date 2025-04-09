// lib/models/moto.dart
import 'dart:convert';
import 'model_moto.dart';
import 'package:pfe1/services/api_service.dart';

class Moto {
  final int id;
  final int modelId;
  final int? clientId;
  final String? image;
  final DateTime createdAt;
  final DateTime updatedAt;
  final ModelMoto? model;

  Moto({
    required this.id,
    required this.modelId,
    this.clientId,
    this.image,
    required this.createdAt,
    required this.updatedAt,
    this.model,
  });

  factory Moto.fromJson(Map<String, dynamic> json) {
  // If direct marque and annee properties exist but no model object,
  // create a synthetic model object
  ModelMoto? modelObject;
  
  if (json['model'] != null) {
    // Use the model object if it exists
    modelObject = ModelMoto.fromJson(json['model']);
  } else if (json['marque'] != null) {
    // Create a synthetic model from flattened properties
    modelObject = ModelMoto(
      id: json['model_id'] ?? 0, // Default or infer from elsewhere
      marque: json['marque'] ?? 'Inconnue',
      annee: json['annee'] ?? 'Inconnue',
      createdAt: DateTime.now(),
      updatedAt: DateTime.now(),
    );
  }
  
  return Moto(
    id: json['id'],
    modelId: json['model_id'] ?? 0, // You might need to handle missing model_id
    clientId: json['client_id'],
    image: json['image'],
    createdAt: json['created_at'] != null 
        ? DateTime.parse(json['created_at']) 
        : DateTime.now(),
    updatedAt: json['updated_at'] != null 
        ? DateTime.parse(json['updated_at']) 
        : DateTime.now(),
    model: modelObject,
  );
}

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'model_id': modelId,
      'client_id': clientId,
      'image': image,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'model': model?.toJson(),
    };
  }
  
  factory Moto.fromJsonString(String jsonString) {
    return Moto.fromJson(json.decode(jsonString));
  }

  String getImageUrl() {
  if (image == null || image!.isEmpty) {
    return ''; 
  }
  
  try {
    return ApiService.getImageUrl(image!);
  } catch (e) {
    print("Erreur dans getImageUrl: $e");
    return ''; 
  }
}

}