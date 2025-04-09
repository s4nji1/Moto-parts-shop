<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client; // Changé de User à Client
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'cin' => 'required|string|unique:clients', // Changé de users à clients
            'email' => 'string|email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $client = Client::create([ // Changé de User à Client
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'cin' => $request->cin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = $client->createToken('auth_token')->plainTextToken; // Changé de $user à $client

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user' => $client, // Vous pouvez garder 'user' comme clé ou changer à 'client'
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Connexion d'un utilisateur
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cin' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Configurer le garde auth pour utiliser la table clients
        config(['auth.providers.users.model' => Client::class]);
        
        // Vérification des identifiants
        if (!Auth::attempt($request->only('cin', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $client = Client::where('cin', $request->cin)->first(); // Changé de User à Client
        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $client, // Vous pouvez garder 'user' comme clé ou changer à 'client'
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Déconnexion d'un utilisateur
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Récupérer le profil de l'utilisateur connecté
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }
    
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|exists:clients,id', // Changé de users à clients
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            // Find the user
            $client = Client::findOrFail($request->userId); // Changé de User à Client
            
            // Check if current password matches
            if (!Hash::check($request->currentPassword, $client->password)) { // Changé de $user à $client
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 400);
            }
            
            // Update password
            $client->password = Hash::make($request->newPassword); // Changé de $user à $client
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|exists:clients,id', // Changé de users à clients
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $request->userId, // Changé de users à clients
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:20|unique:clients,cin,' . $request->userId, // Changé de users à clients
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $client = Client::findOrFail($request->userId); // Changé de User à Client
            
            // Update user information
            $client->firstname = $request->firstname; // Changé de $user à $client
            $client->lastname = $request->lastname;
            $client->email = $request->email;
            
            // Mettre à jour les champs optionnels seulement s'ils sont présents
            if ($request->has('phone')) {
                $client->phone = $request->phone;
            }
            
            if ($request->has('address')) {
                $client->address = $request->address;
            }
            
            if ($request->has('cin')) {
                $client->cin = $request->cin;
            }
            
            $client->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $client // Vous pouvez garder 'user' comme clé ou changer à 'client'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Récupérer le profil d'un utilisateur
     */
    public function getProfile(Request $request)
    {
        // Récupérer l'ID de l'utilisateur soit depuis la requête, soit depuis l'utilisateur authentifié
        $userId = $request->userId ?? $request->user()->id ?? null;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID not provided'
            ], 400);
        }

        try {
            $client = Client::findOrFail($userId); // Changé de User à Client
            
            return response()->json([
                'success' => true,
                'user' => $client // Vous pouvez garder 'user' comme clé ou changer à 'client'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching profile: ' . $e->getMessage()
            ], 500);
        }
    }
}