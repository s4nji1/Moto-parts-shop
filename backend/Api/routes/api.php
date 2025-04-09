<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MotoController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\SchemaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);

    Route::get('/client/motos', [MotoController::class, 'getClientMotos']);
    Route::get('/schemas', [SchemaController::class, 'getAllSchemas']);
    Route::get('/schemas/{id}', [SchemaController::class, 'getSchema']);
    Route::get('/schemas/search', [SchemaController::class, 'searchSchemas']);
    Route::get('/categories', [SchemaController::class, 'getCategories']);

    // Motos
    Route::get('/client/motos', [MotoController::class, 'getClientMotos']);
    Route::post('/client/motos', [MotoController::class, 'addMoto']);
    Route::delete('/client/motos/{id}', [MotoController::class, 'deleteMoto']);
    Route::get('/motos/{id}/schemas', [MotoController::class, 'getCompatibleSchemas']);
    Route::get('/models', [MotoController::class, 'getAllModels']);

    // Panier
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/remove', [CartController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);

    // Commandes
    Route::get('/commandes', [CommandeController::class, 'getClientCommandes']);
    Route::get('/commandes/{id}', [CommandeController::class, 'getCommande']);
    Route::post('/commandes/create-from-cart', [CommandeController::class, 'createCommandeFromCart']);
    Route::put('/commandes/{id}/cancel', [CommandeController::class, 'cancelCommande']);
});

Route::get('/motos', [MotoController::class, 'getAllMotos']);