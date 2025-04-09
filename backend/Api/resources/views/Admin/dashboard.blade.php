<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            {{ __('Tableau de Bord') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Ventes Totales -->
        <div class="bg-gray-900 border-l-4 border-engine-red">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">VENTES TOTALES</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalSales }} commandes</div>
                    <div class="flex items-center text-sm mt-1">
                        @if($salesGrowth > 0)
                            <span class="text-green-500 flex items-center mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                {{ $salesGrowth }}%
                            </span>
                        @elseif($salesGrowth < 0)
                            <span class="text-red-500 flex items-center mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                </svg>
                                {{ $salesGrowth }}%
                            </span>
                        @else
                            <span class="text-gray-400 flex items-center mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                </svg>
                                0%
                            </span>
                        @endif
                        <span class="text-gray-400">depuis le mois dernier</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Clients -->
        <div class="bg-gray-900 border-l-4 border-exhaust-blue">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">CLIENTS</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalCustomers }}</div>
                    <div class="flex items-center text-sm mt-1">
                        @if($newCustomers > 0)
                            <span class="text-green-500 flex items-center mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </span>
                        @else
                            <span class="text-gray-400">0</span>
                        @endif
                        <span class="text-gray-400">{{ $newCustomers }} nouveaux ce mois</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Commandes -->
        <div class="bg-gray-900 border-l-4 border-fuel-yellow">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">COMMANDES</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalOrders }}</div>
                    <div class="flex items-center text-sm mt-1">
                        <span class="text-yellow-500 flex items-center mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="text-gray-400">{{ $pendingOrders }} en attente</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Produits (Schémas) -->
        <div class="bg-gray-900 border-l-4 border-leather-brown">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">PIÈCES</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalProducts }}</div>
                    <div class="flex items-center text-sm mt-1">
                        <span class="text-gray-400">{{ $totalProducts }} pièces disponibles</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Motos -->
        <div class="bg-gray-900 border-l-4 border-exhaust-blue">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">MOTOS</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3zm-7-7h4v4H4v-4z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalMotos }}</div>
                    <div class="flex items-center text-sm mt-1">
                        <span class="text-exhaust-blue flex items-center mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </span>
                        <span class="text-gray-400">{{ $newMotos }} ajoutées ce mois</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Modèles -->
        <div class="bg-gray-900 border-l-4 border-engine-red">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">MODÈLES</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-bold text-white">{{ $totalModels }}</div>
                    <div class="flex items-center text-sm mt-1">
                        <span class="text-engine-red flex items-center mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                        <span class="text-gray-400">{{ $topBrand }} marque principale</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commandes récentes -->
    <div class="content-panel mt-6">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Commandes récentes</h3>
            <a href="{{ route('commandes.index') }}" class="moto-button py-1 px-4 text-sm">VOIR TOUT</a>
        </div>
        <div class="panel-body p-0">
            <div class="overflow-x-auto">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th>COMMANDE</th>
                            <th>CLIENT</th>
                            <th>PIÈCE</th>
                            <th>DATE</th>
                            <th>STATUT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $commande)
                            <tr>
                                <td class="font-semibold">#{{ $commande->id }}</td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-carbon-fiber flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold">{{ substr($commande->client->firstname, 0, 1) }}{{ substr($commande->client->lastname, 0, 1) }}</span>
                                        </div>
                                        <span>{{ $commande->client->firstname }} {{ $commande->client->lastname }}</span>
                                    </div>
                                </td>
                                <td>{{ $commande->schema->nom }}</td>
                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($commande->status == 'en_attente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                            En attente
                                        </span>
                                    @elseif($commande->status == 'en_traitement')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                                            En traitement
                                        </span>
                                    @elseif($commande->status == 'expediee')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-200 text-indigo-800">
                                            Expédiée
                                        </span>
                                    @elseif($commande->status == 'livree')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-200 text-green-800">
                                            Livrée
                                        </span>
                                    @elseif($commande->status == 'annulee')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-200 text-red-800">
                                            Annulée
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('commandes.show', $commande->id) }}" class="text-engine-red hover:text-white transition-colors duration-200">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucune commande récente</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Derniers clients inscrits -->
        <div class="content-panel">
            <div class="panel-header flex justify-between items-center">
                <h3 class="panel-title">Derniers clients inscrits</h3>
                <a href="{{ route('clients.index') }}" class="moto-button py-1 px-4 text-sm">VOIR TOUT</a>
            </div>
            <div class="panel-body p-0">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th>CLIENT</th>
                            <th>EMAIL</th>
                            <th>DATE D'INSCRIPTION</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCustomers as $client)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-carbon-fiber flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold">{{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold">{{ $client->firstname }} {{ $client->lastname }}</div>
                                            <div class="text-xs text-polished-chrome/70">{{ $client->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('clients.show', $client->id) }}" class="text-engine-red hover:text-white transition-colors duration-200">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucun client récent</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Motos récemment ajoutées -->
<div class="content-panel">
    <div class="panel-header flex justify-between items-center">
        <h3 class="panel-title">Motos récemment ajoutées</h3>
        <a href="{{ route('motos.index') }}" class="moto-button py-1 px-4 text-sm">VOIR TOUT</a>
    </div>
    <div class="panel-body p-0">
        <table class="moto-table">
            <thead>
                <tr>
                    <th>MOTO</th>
                    <th>MARQUE</th>
                    <th>ANNÉE</th>
                    <th>PROPRIÉTAIRE</th>
                    <th>DATE D'AJOUT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMotos as $moto)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 48px; height: 48px; border-radius: 6px; background-color: var(--carbon-fiber); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if($moto->image)
                                        <img src="{{ asset('storage/' . $moto->image) }}" alt="Moto" style="width: 100px; height: 50px; object-fit: cover;" >
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 32px; width: 32px; color: rgba(var(--polished-chrome), 0.5);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3zm-7-7h4v4H4v-4z" />
                                        </svg>
                                    @endif
                                </div>
                                <div style="margin-left: 30px;">
                                    <div style="font-weight: 600;">ID #{{ $moto->id }}</div>
                                    <div style="font-size: 0.75rem; color: rgba(var(--polished-chrome), 0.7);">
                                        @if($moto->client)
                                            Client: {{ $moto->client->firstname }}
                                        @else
                                            Sans client
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $moto->model->marque }}</td>
                        <td>{{ $moto->model->annee }}</td>
                        <td>
                            @if($moto->client)
                                <a href="{{ route('clients.show', $moto->client->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                    {{ $moto->client->firstname }} {{ $moto->client->lastname }}
                                </a>
                            @else
                                <span class="text-polished-chrome/50">Non assigné</span>
                            @endif
                        </td>
                        <td>{{ $moto->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('motos.show', $moto->id) }}" class="text-engine-red hover:text-white transition-colors duration-200">
                                Détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3zm-7-7h4v4H4v-4z" />
                                </svg>
                                <span class="text-polished-chrome/70">Aucune moto récente</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Composant pour les modèles récents -->
<div class="content-panel">
    <div class="panel-header flex justify-between items-center">
        <h3 class="panel-title">Modèles populaires</h3>
        <a href="{{ route('models.index') }}" class="moto-button py-1 px-4 text-sm">VOIR TOUT</a>
    </div>
    <div class="panel-body p-0">
        <table class="moto-table">
            <thead>
                <tr>
                    <th>MODÈLE</th>
                    <th>MARQUE</th>
                    <th>ANNÉE</th>
                    <th>MOTOS</th>
                    <th>DATE D'AJOUT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularModels as $model)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-md bg-engine-red/20 flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-engine-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                </div>
                                <div class="font-semibold">ID #{{ $model->id }}</div>
                            </div>
                        </td>
                        <td>{{ $model->marque }}</td>
                        <td>{{ $model->annee }}</td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-exhaust-blue/20 text-exhaust-blue">
                                {{ $model->motos_count }}
                            </span>
                        </td>
                        <td>{{ $model->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('models.show', $model->id) }}" class="text-engine-red hover:text-white transition-colors duration-200">
                                Détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                <span class="text-polished-chrome/70">Aucun modèle disponible</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>  
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration des couleurs
            const colors = {
                red: '#D12026', 
                blue: '#2B6CC4',
                yellow: '#F9A602',
                gray: '#2F2F2F',
                chrome: '#E8E8E8'
            };

            // Fonction pour créer un dégradé
            function createGradient(ctx, startColor, endColor) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, startColor);
                gradient.addColorStop(1, endColor);
                return gradient;
            }

            // Graphique des commandes mensuelles
            const salesCtx = document.getElementById('sales-chart').getContext('2d');
            const salesGradient = createGradient(salesCtx, 'rgba(209, 32, 38, 0.8)', 'rgba(209, 32, 38, 0.1)');
            
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlySalesLabels) !!},
                    datasets: [{
                        label: 'Commandes',
                        data: {!! json_encode($monthlySalesData) !!},
                        fill: true,
                        backgroundColor: salesGradient,
                        borderColor: colors.red,
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: colors.red,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: 'rgba(232, 232, 232, 0.7)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: 'rgba(232, 232, 232, 0.7)'
                            }
                        }
                    }
                }
            });
            
            // Graphique des pièces les plus demandées
            const categoryCtx = document.getElementById('category-pie-chart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($topCategoriesLabels) !!},
                    datasets: [{
                        data: {!! json_encode($topCategoriesData) !!},
                        backgroundColor: [
                            colors.red,
                            colors.blue,
                            colors.yellow,
                            '#844C2C',
                            '#5E35B1'
                        ],
                        borderWidth: 2,
                        borderColor: '#1E1E1E'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: 'rgba(232, 232, 232, 0.7)',
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>