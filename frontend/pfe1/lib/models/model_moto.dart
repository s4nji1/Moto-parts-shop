// lib/models/model_moto.dart
import 'dart:convert';

class ModelMoto {
  final int id;
  final String marque;
  final int annee;
  final DateTime createdAt;
  final DateTime updatedAt;

  ModelMoto({
    required this.id,
    required this.marque,
    required this.annee,
    required this.createdAt,
    required this.updatedAt,
  });

  factory ModelMoto.fromJson(Map<String, dynamic> json) {
    return ModelMoto(
      id: json['id'],
      marque: json['marque'],
      annee: json['annee'],
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : DateTime.now(),
      updatedAt: json['updated_at'] != null 
          ? DateTime.parse(json['updated_at']) 
          : DateTime.now(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'marque': marque,
      'annee': annee,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }
  
  factory ModelMoto.fromJsonString(String jsonString) {
    return ModelMoto.fromJson(json.decode(jsonString));
  }

  @override
  String toString() {
    return '$marque ($annee)';
  }
}