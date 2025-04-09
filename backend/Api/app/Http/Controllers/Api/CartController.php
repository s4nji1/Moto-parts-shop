<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Récupère le panier du client
     */
    public function getCart()
    {
        $client = Auth::user();
        
        // Récupérer ou créer le panier du client
        $cart = Cart::firstOrCreate(['client_id' => $client->id]);
        
        // Récupérer les éléments du panier avec les schémas associés
        $cartItems = CartItem::with('schema')
            ->where('cart_id', $cart->id)
            ->get();
            
        return response()->json([
            'success' => true,
            'items' => $cartItems
        ]);
    }
    
    /**
     * Ajoute un schéma au panier
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schema_id' => 'required|exists:schemas,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $client = Auth::user();
        $schemaId = $request->schema_id;
        $quantity = $request->quantity;
        
        // Récupérer le schéma
        $schema = Schema::find($schemaId);
        if (!$schema) {
            return response()->json([
                'success' => false,
                'message' => 'Schéma non trouvé'
            ], 404);
        }
        
        // Récupérer ou créer le panier
        $cart = Cart::firstOrCreate(['client_id' => $client->id]);
        
        // Vérifier si le schéma est déjà dans le panier
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('schema_id', $schemaId)
            ->first();
            
        if ($cartItem) {
            // Mettre à jour la quantité
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Créer un nouvel élément
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->schema_id = $schemaId;
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Schéma ajouté au panier',
            'item' => $cartItem
        ]);
    }
    
    /**
     * Met à jour la quantité d'un schéma dans le panier
     */
    public function updateCartItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schema_id' => 'required|exists:schemas,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $client = Auth::user();
        $schemaId = $request->schema_id;
        $quantity = $request->quantity;
        
        // Récupérer le panier
        $cart = Cart::where('client_id', $client->id)->first();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Panier non trouvé'
            ], 404);
        }
        
        // Récupérer l'élément du panier
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('schema_id', $schemaId)
            ->first();
            
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Schéma non trouvé dans le panier'
            ], 404);
        }
        
        // Mettre à jour la quantité
        $cartItem->quantity = $quantity;
        $cartItem->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour',
            'item' => $cartItem
        ]);
    }
    
    /**
     * Supprime un schéma du panier
     */
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schema_id' => 'required|exists:schemas,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $client = Auth::user();
        $schemaId = $request->schema_id;
        
        // Récupérer le panier
        $cart = Cart::where('client_id', $client->id)->first();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Panier non trouvé'
            ], 404);
        }
        
        // Supprimer l'élément du panier
        $deleted = CartItem::where('cart_id', $cart->id)
            ->where('schema_id', $schemaId)
            ->delete();
            
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Schéma non trouvé dans le panier'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Schéma retiré du panier'
        ]);
    }
    
    /**
     * Vide le panier
     */
    public function clearCart()
    {
        $client = Auth::user();
        
        // Récupérer le panier
        $cart = Cart::where('client_id', $client->id)->first();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Panier non trouvé'
            ], 404);
        }
        
        // Supprimer tous les éléments du panier
        CartItem::where('cart_id', $cart->id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Panier vidé avec succès'
        ]);
    }
}