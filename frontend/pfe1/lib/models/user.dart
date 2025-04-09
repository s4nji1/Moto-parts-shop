class User {
  final int id;
  final String firstname;
  final String lastname;
  final String email;
  final DateTime? emailVerifiedAt;
  String? accessToken;

  User({
    required this.id,
    required this.firstname,
    required this.lastname,
    required this.email,
    this.emailVerifiedAt,
    this.accessToken,
  });

  // Get full name
  String get fullName => "$firstname $lastname";

  // Factory constructor to create a User object from JSON
  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      firstname: json['firstname'],
      lastname: json['lastname'],
      email: json['email'],
      emailVerifiedAt: json['email_verified_at'] != null 
          ? DateTime.parse(json['email_verified_at']) 
          : null,
      accessToken: json['access_token'],
    );
  }

  // Method to convert User object to JSON
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'firstname': firstname,
      'lastname': lastname,
      'email': email,
      'email_verified_at': emailVerifiedAt?.toIso8601String(),
    };
  }
}