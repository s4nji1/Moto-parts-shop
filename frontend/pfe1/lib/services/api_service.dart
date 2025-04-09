import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/model_moto.dart';
import '../models/moto.dart';
import '../models/schema.dart';
import '../models/commande.dart';

class ApiService {
  // Replace with your PC's IP
  static const String baseUrl = "http://192.168.0.104:8000/api";

  static Map<String, dynamic>? currentUser;
  static String? accessToken;

  // Improved login function that returns a result
  static Future<Map<String, dynamic>> login(
    // ignore: non_constant_identifier_names
    String Cin,
    String password,
  ) async {
    try {
      // Assurez-vous que currentUser est nul avant la connexion
      // Cette ligne est importante pour effacer les données d'un utilisateur précédent
      currentUser = null;
      accessToken = null;

      final response = await http.post(
        Uri.parse("$baseUrl/login"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({"cin": Cin, "password": password}),
      );

      if (response.statusCode == 200) {
        // Parse the JSON response
        Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true) {
          currentUser = data['user'];
          accessToken = data['access_token'];

          return {
            "success": true,
            "message": data['message'],
            "user": data['user'],
            "token": data['access_token'],
          };
        } else {
          return {
            "success": false,
            "message": data['message'] ?? "Login failed",
          };
        }
      } else {
        String message = "Login error: ${response.statusCode}";
        try {
          Map<String, dynamic> errorData = jsonDecode(response.body);
          message = errorData['message'] ?? message;
        } catch (e) {
          // If the body is not valid JSON, use the default message
        }
        return {"success": false, "message": message};
      }
    } catch (e) {
      return {"success": false, "message": "Connection error: $e"};
    }
  }

  static Map<String, dynamic>? getCurrentUser() {
    return currentUser;
  }

  // Méthode pour vérifier si un utilisateur est connecté
  static bool isLoggedIn() {
    return currentUser != null && accessToken != null;
  }

  static Future<void> logout() async {
    // Appel à l'API pour déconnecter l'utilisateur côté serveur (si nécessaire)
    if (accessToken != null) {
      try {
        await http.post(
          Uri.parse("$baseUrl/logout"),
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer $accessToken",
          },
        );
      } catch (e) {
        // Gérer les erreurs silencieusement, l'important est de supprimer les données locales
        print("Error during logout API call: $e");
      }
    }

    // Réinitialiser les données de l'utilisateur actuel
    currentUser = null;
    accessToken = null;
  }

  // Autres méthodes existantes...

  // Update profile function
  static Future<Map<String, dynamic>> updateProfile(
    int userId,
    String firstName,
    String lastName,
    String email, {
    String? phone,
    String? address,
    String? cin,
  }) async {
    try {
      final Map<String, dynamic> requestBody = {
        "userId": userId,
        "firstname": firstName,
        "lastname": lastName,
        "email": email,
        "phone": phone,
        "address": address,
      };

      // Ajoutez le CNI seulement s'il est fourni
      if (cin != null) {
        requestBody["cin"] = cin;
      }

      final response = await http.put(
        Uri.parse("$baseUrl/updateProfile"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
        body: jsonEncode(requestBody),
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        // Mettre à jour les données de l'utilisateur en cache
        if (data['success'] == true && data['user'] != null) {
          currentUser = data['user'];
        }

        return {
          "success": data['success'] ?? false,
          "message": data['message'] ?? "Profile updated successfully",
        };
      } else {
        return {
          "success": false,
          "message": "Failed to update profile: ${response.statusCode}",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Error updating profile: $e"};
    }
  }

  static Future<Map<String, dynamic>> getUserProfile(int userId) async {
    try {
      // Toujours faire une requête API fraîche pour obtenir les dernières données
      final response = await http.get(
        Uri.parse("$baseUrl/profile?userId=$userId"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        if (data['success'] == true && data['user'] != null) {
          // Mettre à jour les informations en cache
          currentUser = data['user'];
          return data['user'];
        } else {
          return {
            "error": data['message'] ?? "Échec de récupération du profil",
          };
        }
      } else {
        return {"error": "Échec de récupération: ${response.statusCode}"};
      }
    } catch (e) {
      return {"error": "Erreur lors de la récupération du profil: $e"};
    }
  }

  // Updated registration method to include first name, last name, CNI, phone, and address
  static Future<Map<String, dynamic>> register(
    String firstName,
    String lastName,
    String cin,
    String email,
    String password, {
    String? phone,
    String? address,
  }) async {
    try {
      // Debug information
      // print("Attempting registration with: $firstName $lastName / $email");

      // Create the HTTP request
      final response = await http.post(
        Uri.parse("$baseUrl/register"),
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: jsonEncode({
          "firstname": firstName,
          "lastname": lastName,
          "cin": cin,
          "email": email,
          "password": password,
          "phone": phone,
          "address": address,
        }),
      );

      // Debug response information
      // print("API Response - StatusCode: ${response.statusCode}");
      // print("API Response - Headers: ${response.headers}");
      // print("API Response - Body: '${response.body}'");

      // Check if the response is empty
      if (response.body.isEmpty) {
        return {
          "success": false,
          "message":
              "Empty response from server (code: ${response.statusCode})",
        };
      }

      // Check if the response is valid JSON
      try {
        final Map<String, dynamic> data = jsonDecode(response.body);

        // Return structured data
        return {
          "success": data['success'] ?? false,
          "message": data['message'] ?? "Server response without message",
          "data": data,
        };
      } catch (e) {
        // JSON parsing error
        return {
          "success": false,
          "message":
              "Response format error: ${e.toString()}. Content: ${response.body.substring(0, min(100, response.body.length))}...",
        };
      }
    } catch (e) {
      // Connection or other error
      print("Full error: $e");
      return {"success": false, "message": "Connection error: ${e.toString()}"};
    }
  }

  // Utility function to limit string length
  static int min(int a, int b) {
    return (a < b) ? a : b;
  }

  static Future<Map<String, dynamic>> changePassword(
    int userId,
    String currentPassword,
    String newPassword,
  ) async {
    try {
      final response = await http.put(
        Uri.parse("$baseUrl/change-password"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "userId": userId,
          "currentPassword": currentPassword,
          "newPassword": newPassword,
        }),
      );

      // print("API Response - StatusCode: ${response.statusCode}");
      // print("API Response - Body: ${response.body}");

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": data['success'] ?? false,
          "message": data['message'] ?? "Password changed successfully",
        };
      } else {
        return {
          "success": false,
          "message": "Failed to change password: ${response.statusCode}",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Error changing password: $e"};
    }
  }

  Future<Map<String, dynamic>> createCommande({
    required int schemaId,
    required int quantite,
    required int clientId,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/commandes'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'schema_id': schemaId,
          'quantite': quantite,
          'client_id': clientId,
        }),
      );

      final responseData = jsonDecode(response.body);

      if (response.statusCode == 201) {
        return {
          'success': true,
          'message': 'Commande creee avec succes',
          'data': responseData['data'],
        };
      } else {
        return {
          'success': false,
          'message':
              responseData['message'] ??
              'Erreur lors de la création de la commande',
          'errors': responseData['errors'] ?? {},
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Erreur de connexion: ${e.toString()}',
        'errors': {},
      };
    }
  }

  // lib/services/api_service.dart (méthode à ajouter)

  static Future<List<Moto>> getClientMotos() async {
    try {
      // Vérifier si l'utilisateur est connecté
      if (!isLoggedIn() || currentUser == null) {
        return [];
      }

      final response = await http.get(
        Uri.parse("$baseUrl/client/motos"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true && data['motos'] != null) {
          List<dynamic> motosJson = data['motos'];
          return motosJson.map((json) => Moto.fromJson(json)).toList();
        } else {
          return [];
        }
      } else {
        print(
          "Erreur lors de la récupération des motos: ${response.statusCode}",
        );
        return [];
      }
    } catch (e) {
      print("Exception lors de la récupération des motos: $e");
      return [];
    }
  }

  static Future<Map<String, dynamic>> addMoto(
    int modelId,
    String? image,
  ) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      final response = await http.post(
        Uri.parse("$baseUrl/client/motos"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
        body: jsonEncode({'model_id': modelId, 'image': image}),
      );

      if (response.statusCode == 201) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Moto ajoutée avec succès",
          "moto": data['moto'],
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message": data['message'] ?? "Erreur lors de l'ajout de la moto",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  static Future<Map<String, dynamic>> deleteMoto(int motoId) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      final response = await http.delete(
        Uri.parse("$baseUrl/client/motos/$motoId"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Moto supprimée avec succès",
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message":
              data['message'] ?? "Erreur lors de la suppression de la moto",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  static Future<List<ModelMoto>> getAllModels() async {
    try {
      print("Appel API: GET $baseUrl/models");
      final response = await http.get(
        Uri.parse("$baseUrl/models"),
        headers: {
          "Content-Type": "application/json",
          "Accept":
              "application/json", // Très important - force le serveur à renvoyer du JSON
          "Authorization":
              accessToken != null
                  ? "Bearer $accessToken"
                  : "", // S'assurer que le token est envoyé
        },
      );

      print("Statut de réponse: ${response.statusCode}");
      print("Type de contenu: ${response.headers['content-type']}");

      // Si le serveur renvoie une réponse non-JSON, gérer l'erreur
      if (!response.headers['content-type']!.contains('application/json')) {
        print("Réponse non-JSON reçue: ${response.body.substring(0, 100)}...");
        return [];
      }

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true && data['models'] != null) {
          List<dynamic> modelsJson = data['models'];
          return modelsJson.map((json) => ModelMoto.fromJson(json)).toList();
        } else {
          print("Réponse API invalide: ${response.body}");
          return [];
        }
      } else {
        print(
          "Erreur HTTP ${response.statusCode} lors de la récupération des modèles",
        );
        return [];
      }
    } catch (e) {
      print("Exception lors de la récupération des modèles: $e");
      return [];
    }
  }

  // lib/services/api_service.dart (méthodes à ajouter)

  // Récupère le contenu du panier
  static Future<Map<String, dynamic>> getCart() async {
    try {
      print("ApiService - getCart called");

      if (!isLoggedIn() || currentUser == null) {
        print("ApiService - User not logged in");
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      print("ApiService - Sending GET request to $baseUrl/cart");

      final response = await http.get(
        Uri.parse("$baseUrl/cart"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      print("ApiService - Response status: ${response.statusCode}");
      print("ApiService - Response body: ${response.body}");

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {"success": true, "items": data['items'] ?? []};
      } else {
        print("ApiService - Error in getCart: ${response.statusCode}");
        return {
          "success": false,
          "message":
              "Erreur lors de la récupération du panier: ${response.statusCode}",
        };
      }
    } catch (e) {
      print("ApiService - Exception in getCart: $e");
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Ajoute un produit au panier
  static Future<Map<String, dynamic>> addToCart(
    int schemaId,
    int quantity,
  ) async {
    try {
      print(
        "ApiService - addToCart called with schemaId: $schemaId, quantity: $quantity",
      );

      if (!isLoggedIn() || currentUser == null) {
        print("ApiService - User not logged in");
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      print("ApiService - Sending POST request to $baseUrl/cart/add");
      print(
        "ApiService - Request body: ${jsonEncode({'schema_id': schemaId, 'quantity': quantity})}",
      );

      final response = await http.post(
        Uri.parse("$baseUrl/cart/add"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
        body: jsonEncode({'schema_id': schemaId, 'quantity': quantity}),
      );

      print("ApiService - Response status: ${response.statusCode}");
      print("ApiService - Response body: ${response.body}");

      if (response.statusCode == 200 || response.statusCode == 201) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Produit ajouté au panier",
          "item": data['item'],
        };
      } else {
        final Map<String, dynamic> data =
            response.body.isNotEmpty
                ? jsonDecode(response.body)
                : {"message": "Erreur inconnue"};
        return {
          "success": false,
          "message": data['message'] ?? "Erreur lors de l'ajout au panier",
        };
      }
    } catch (e) {
      print("ApiService - Exception in addToCart: $e");
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Met à jour la quantité d'un produit dans le panier
  static Future<Map<String, dynamic>> updateCartItemQuantity(
    int schemaId,
    int newQuantity,
  ) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      final response = await http.put(
        Uri.parse("$baseUrl/cart/update"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
        body: jsonEncode({"schema_id": schemaId, "quantity": newQuantity}),
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Quantité mise à jour",
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message": data['message'] ?? "Erreur lors de la mise à jour",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Supprime un produit du panier
  static Future<Map<String, dynamic>> removeFromCart(int schemaId) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      final response = await http.delete(
        Uri.parse("$baseUrl/cart/remove"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
        body: jsonEncode({"schema_id": schemaId}),
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Produit retiré du panier",
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message": data['message'] ?? "Erreur lors de la suppression",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Vide le panier
  static Future<Map<String, dynamic>> clearCart() async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      final response = await http.delete(
        Uri.parse("$baseUrl/cart/clear"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Panier vidé avec succès",
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message": data['message'] ?? "Erreur lors du vidage du panier",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Récupère les pièces compatibles avec une moto
  static Future<List<Schema>> getCompatibleSchemas(int motoId) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        print("Utilisateur non connecté");
        return [];
      }

      print("Appel API: GET $baseUrl/motos/$motoId/schemas");

      final response = await http.get(
        Uri.parse("$baseUrl/motos/$motoId/schemas"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
        },
      );

      print("Statut de réponse: ${response.statusCode}");
      print("Corps de réponse: ${response.body}");

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true && data['schemas'] != null) {
          List<dynamic> schemasJson = data['schemas'];
          print("Nombre de schémas reçus: ${schemasJson.length}");

          // Imprimer les détails de chaque schéma pour le débogage
          for (var schema in schemasJson) {
            print("Schema ID: ${schema['id']}, Nom: ${schema['nom']}");
          }

          return schemasJson.map((json) => Schema.fromJson(json)).toList();
        } else {
          print("Aucun schéma dans la réponse ou succès = false");
          if (data['message'] != null) {
            print("Message d'erreur: ${data['message']}");
          }
          return [];
        }
      } else {
        print(
          "Erreur lors de la récupération des schémas: ${response.statusCode}",
        );
        if (response.body.isNotEmpty) {
          print("Message d'erreur: ${response.body}");
        }
        return [];
      }
    } catch (e) {
      print("Exception lors de la récupération des schémas: $e");
      return [];
    }
  }

  // Dans lib/services/api_service.dart

  static Future<Map<String, dynamic>> createCommandeFromCart() async {
    try {
      print("ApiService - createCommandeFromCart called");

      if (!isLoggedIn() || currentUser == null) {
        print("ApiService - User not logged in");
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      print(
        "ApiService - Sending POST request to $baseUrl/commandes/create-from-cart",
      );

      final response = await http.post(
        Uri.parse("$baseUrl/commandes/create-from-cart"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
          "Accept": "application/json", // Important: force JSON response
        },
      );

      print("ApiService - Response status: ${response.statusCode}");
      print(
        "ApiService - Response body: ${response.body.substring(0, min(100, response.body.length))}...",
      );

      // Vérifiez si la réponse est du JSON valide
      if (response.body.startsWith('{') || response.body.startsWith('[')) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        if (response.statusCode == 201 || response.statusCode == 200) {
          return {
            "success": true,
            "message": data['message'] ?? "Commandes créées avec succès",
            "commandes": data['commandes'],
          };
        } else {
          return {
            "success": false,
            "message":
                data['message'] ?? "Erreur lors de la création des commandes",
          };
        }
      } else {
        // Si ce n'est pas du JSON, retournez une erreur formatée
        print("ApiService - Response is not JSON!");
        return {
          "success": false,
          "message":
              "Le serveur a renvoyé une réponse non-JSON. Vérifiez les logs pour plus de détails.",
        };
      }
    } catch (e) {
      print("ApiService - Exception in createCommandeFromCart: $e");
      return {"success": false, "message": "Exception: $e"};
    }
  }

  // Récupère toutes les commandes du client
  static Future<List<Commande>> getClientCommandes() async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        print("ApiService - User not logged in");
        return [];
      }

      print("ApiService - Fetching client commandes");
      final response = await http.get(
        Uri.parse("$baseUrl/commandes"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
          "Accept": "application/json",
        },
      );

      print("ApiService - Response status: ${response.statusCode}");

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true && data['commandes'] != null) {
          List<dynamic> commandesJson = data['commandes'];
          print("ApiService - Received ${commandesJson.length} commandes");
          return commandesJson.map((json) => Commande.fromJson(json)).toList();
        } else {
          print("ApiService - No commandes in response or success = false");
          return [];
        }
      } else {
        print("ApiService - Error fetching commandes: ${response.statusCode}");
        return [];
      }
    } catch (e) {
      print("ApiService - Exception fetching commandes: $e");
      return [];
    }
  }

  // Récupère les détails d'une commande spécifique
  static Future<Commande?> getCommandeDetails(int commandeId) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        print("ApiService - User not logged in");
        return null;
      }

      print("ApiService - Fetching commande details for ID: $commandeId");
      final response = await http.get(
        Uri.parse("$baseUrl/commandes/$commandeId"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
          "Accept": "application/json",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);

        if (data['success'] == true && data['commande'] != null) {
          return Commande.fromJson(data['commande']);
        } else {
          print("ApiService - No commande in response or success = false");
          return null;
        }
      } else {
        print(
          "ApiService - Error fetching commande details: ${response.statusCode}",
        );
        return null;
      }
    } catch (e) {
      print("ApiService - Exception fetching commande details: $e");
      return null;
    }
  }

  // Annule une commande
  static Future<Map<String, dynamic>> cancelCommande(int commandeId) async {
    try {
      if (!isLoggedIn() || currentUser == null) {
        return {"success": false, "message": "Utilisateur non connecté"};
      }

      print("ApiService - Cancelling commande ID: $commandeId");
      final response = await http.put(
        Uri.parse("$baseUrl/commandes/$commandeId/cancel"),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $accessToken",
          "Accept": "application/json",
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": true,
          "message": data['message'] ?? "Commande annulée avec succès",
          "commande": data['commande'],
        };
      } else {
        final Map<String, dynamic> data = jsonDecode(response.body);
        return {
          "success": false,
          "message":
              data['message'] ?? "Erreur lors de l'annulation de la commande",
        };
      }
    } catch (e) {
      return {"success": false, "message": "Exception: $e"};
    }
  }

  static List<Moto> adaptMotosFromApi(List<dynamic> apiData) {
    return apiData.map((item) {
      // Check if this is a flattened response
      if (item['marque'] != null && item['model'] == null) {
        // Create a synthetic model object
        final modelData = {
          'id': item['model_id'] ?? 0,
          'marque': item['marque'],
          'annee': item['annee'] ?? 'Inconnue',
        };

        // Add the model object to the item
        item['model'] = modelData;
      }

      return Moto.fromJson(item);
    }).toList();
  }

  // Get all motorcycles
  static Future<List<Moto>> getAllMotos() async {
    try {
      print('Appel API: GET $baseUrl/motos');
      final response = await http.get(
        Uri.parse('$baseUrl/motos'),
        headers:
            accessToken != null ? {"Authorization": "Bearer $accessToken"} : {},
      );

      print('Statut de réponse: ${response.statusCode}');
      print('Corps de réponse: ${response.body}');

      if (response.statusCode == 200) {
        final jsonData = jsonDecode(response.body);

        if (jsonData['success'] == true && jsonData['data'] != null) {
          // Use the adapter method instead of direct mapping
          final List<Moto> motos = adaptMotosFromApi(jsonData['data']);

          // Afficher les URL des images en utilisant model.marque au lieu de moto.marque
          for (var moto in motos) {
            print('Moto récupérée: ${moto.id} - ${moto.model?.marque}');
            print('Chemin de l\'image: ${moto.image}');
          }

          return motos;
        } else {
          print('Format de données incorrect: ${jsonData['success']}');
          return [];
        }
      } else {
        print('Échec de chargement des motos: ${response.statusCode}');
        return [];
      }
    } catch (e) {
      print('Erreur lors de la récupération des motos: $e');
      return [];
    }
  }

  static String getImageUrl(String imagePath) {
    // Éviter la duplication des segments de chemin
    return 'http://192.168.0.104:8000/storage/$imagePath';
  }
}
