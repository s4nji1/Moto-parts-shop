// lib/models/client.dart
class Client {
  final int id;
  final String firstname;
  final String lastname;
  final String cin;
  final String email;
  final String phone;
  final String? address;
  final DateTime createdAt;
  final DateTime updatedAt;

  Client({
    required this.id,
    required this.firstname,
    required this.lastname,
    required this.cin,
    required this.email,
    required this.phone,
    this.address,
    required this.createdAt,
    required this.updatedAt,
  });

  String get fullName => '$firstname $lastname';
  
  String get initials => '${firstname.isNotEmpty ? firstname[0] : ''}${lastname.isNotEmpty ? lastname[0] : ''}';

  Client copyWith({
    int? id,
    String? firstname,
    String? lastname,
    String? cin,
    String? email,
    String? phone,
    String? address,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return Client(
      id: id ?? this.id,
      firstname: firstname ?? this.firstname,
      lastname: lastname ?? this.lastname,
      cin: cin ?? this.cin,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      address: address ?? this.address,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  factory Client.fromJson(Map<String, dynamic> json) {
    return Client(
      id: json['id'],
      firstname: json['firstname'],
      lastname: json['lastname'],
      cin: json['cin'],
      email: json['email'],
      phone: json['phone'] ?? '',
      address: json['address'],
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
      'firstname': firstname,
      'lastname': lastname,
      'cin': cin,
      'email': email,
      'phone': phone,
      'address': address,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  @override
  String toString() {
    return 'Client(id: $id, name: $fullName, email: $email)';
  }
}