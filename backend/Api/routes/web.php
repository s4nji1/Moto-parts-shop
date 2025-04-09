<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PiecesDetacheesController;
use App\Http\Controllers\MotosController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\CommandesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\SchemaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('models', ModelsController::class);
    Route::get('/models-export', [ModelsController::class, 'export'])
    ->name('models.export');
    Route::get('/models-search', [ModelsController::class, 'search'])
    ->name('models.search');

    // Routes pour les motos
    Route::resource('motos', MotosController::class);
    Route::get('/motos-export', [MotosController::class, 'export'])
    ->name('motos.export');
    Route::get('/motos-search', [MotosController::class, 'search'])
    ->name('motos.search');

    // Routes pour les schemas (pièces détachées)
    Route::resource('schemas', SchemaController::class);
    Route::get('/schemas-arborescence', [SchemaController::class, 'arborescence'])
        ->name('schemas.arborescence');
    Route::get('/schemas-search', [SchemaController::class, 'search'])
        ->name('schemas.search');
    
    // Routes pour les clients
    Route::resource('clients', ClientsController::class);
    Route::resource('clients', ClientsController::class);
    Route::get('/clients-export', [ClientsController::class, 'export'])
        ->name('clients.export');
    
    // Routes pour les commandes
    Route::resource('commandes', CommandesController::class);
    Route::get('/commandes', [CommandesController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{commande}', [CommandesController::class, 'show'])->name('commandes.show');
    Route::get('/commandes/{commande}/edit', [CommandesController::class, 'edit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [CommandesController::class, 'update'])->name('commandes.update');
    Route::post('/commandes/{commande}/update-status', [CommandesController::class, 'updateStatus'])
        ->name('commandes.updateStatus');
    Route::get('/commandes-export', [CommandesController::class, 'export'])
        ->name('commandes.export');
    Route::get('/commandes-dashboard', [CommandesController::class, 'dashboard'])
        ->name('commandes.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
