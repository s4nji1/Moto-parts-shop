// lib/models/cart_item.dart
import 'dart:convert';
import 'schema.dart';

class CartItem {
  final int id;
  final String name;
  final double price;
  final String imageUrl;
  int quantity;
  final Schema? schema;

  CartItem({
    required this.id,
    required this.name,
    required this.price,
    required this.imageUrl,
    this.quantity = 1,
    this.schema,
  });

  double get total => price * quantity;

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'price': price,
      'imageUrl': imageUrl,
      'quantity': quantity,
      'schema': schema?.toJson(),
    };
  }

  factory CartItem.fromJson(Map<String, dynamic> json) {
  // Fonction utilitaire pour analyser les prix
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

  return CartItem(
    id: json['id'],
    name: json['name'] ?? json['nom'] ?? '',
    price: parsePrice(json['price']), // Utilisation de parsePrice ici
    imageUrl: json['imageUrl'] ?? '',
    quantity: json['quantity'] ?? 1,
    schema: json['schema'] != null ? Schema.fromJson(json['schema']) : null,
  );
}

  factory CartItem.fromJsonString(String jsonString) {
    return CartItem.fromJson(json.decode(jsonString));
  }
}