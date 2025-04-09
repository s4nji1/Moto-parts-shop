<?php



return [
    // Framework Service Providers...
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    // ... autres providers...
    
    // Package Service Providers...
    
    // Application Service Providers...
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,  // Ajoutez cette ligne
];

