// lib/models/commande.dart
import 'dart:convert';
import 'schema.dart';
import 'client.dart';

class Commande {
  final int id;
  final int schemaId;
  final int quantite;
  final double total;
  final int clientId;
  final String status;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Schema? schema; // Relation avec le schéma
  final Client? client; // Relation avec le client

  Commande({
    required this.id,
    required this.schemaId,
    required this.quantite,
    required this.total,
    required this.clientId,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
    this.schema,
    this.client,
  });

  factory Commande.fromJson(Map<String, dynamic> json) {
    // Fonction pour analyser les prix
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

    return Commande(
      id: json['id'],
      schemaId: json['schema_id'],
      quantite: json['quantite'],
      total: parsePrice(json['total']),
      clientId: json['client_id'],
      status: json['status'] ?? 'en_attente',
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : DateTime.now(),
      updatedAt: json['updated_at'] != null 
          ? DateTime.parse(json['updated_at']) 
          : DateTime.now(),
      schema: json['schema'] != null ? Schema.fromJson(json['schema']) : null,
      client: json['client'] != null ? Client.fromJson(json['client']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'schema_id': schemaId,
      'quantite': quantite,
      'total': total,
      'client_id': clientId,
      'status': status,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'schema': schema?.toJson(),
      'client': client?.toJson(),
    };
  }
  
  factory Commande.fromJsonString(String jsonString) {
    return Commande.fromJson(json.decode(jsonString));
  }
  
  bool get isEnAttente => status == 'en_attente';
  bool get isEnCours => status == 'en_cours';
  bool get isLivree => status == 'livree';
  bool get isAnnulee => status == 'annulee';
}