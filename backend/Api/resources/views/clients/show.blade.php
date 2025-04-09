<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('Détails du client') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">{{ $client->firstname }} {{ $client->lastname }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('clients.edit', $client->id) }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                @if($client->commandes->count() == 0)
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-engine-red hover:bg-red-700 text-white py-2 px-4 rounded flex items-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2">
                    <div class="bg-carbon-fiber rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-3">Informations personnelles</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Prénom</span>
                                    <span class="text-white font-medium">{{ $client->firstname }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Nom</span>
                                    <span class="text-white font-medium">{{ $client->lastname }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Email</span>
                                    <a href="mailto:{{ $client->email }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $client->email }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Téléphone</span>
                                    <a href="tel:{{ $client->phone }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $client->phone }}
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">CIN</span>
                                    <span class="text-white font-medium">{{ $client->cin ?? 'Non spécifié' }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Adresse</span>
                                    <span class="text-white font-medium">{{ $client->address ?? 'Non spécifiée' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-panel mb-6">
                        <div class="panel-header flex justify-between items-center">
                            <h4 class="panel-title text-base">Commandes du client</h4>
                            @if($commandes->count() > 0)
                                <a href="{{ route('commandes.index') }}?client_id={{ $client->id }}" class="text-exhaust-blue hover:text-white transition-colors text-sm">
                                    Voir toutes les commandes
                                </a>
                            @endif
                        </div>
                        <div class="panel-body p-0">
                            @if($commandes->count() > 0)
                                <table class="moto-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">ID</th>
                                            <th class="text-left">Pièce</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Statut</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commandes as $commande)
                                            <tr>
                                                <td class="font-medium">#{{ $commande->id }}</td>
                                                <td>
                                                    <a href="{{ route('schemas.show', $commande->schema->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                                        {{ $commande->schema->nom }}
                                                    </a>
                                                    <span class="text-polished-chrome/70 text-xs">({{ $commande->schema->version }})</span>
                                                </td>
                                                <td class="text-center">{{ $commande->quantite }}</td>
                                                <td class="text-center">{{ $commande->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    @if($commande->status == 'en_attente')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                                            En attente
                                                        </span>
                                                    @elseif($commande->status == 'confirmee')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                                                            Confirmée
                                                        </span>
                                                    @elseif($commande->status == 'en_cours')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-200 text-indigo-800">
                                                            En cours
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
                                                    <a href="{{ route('commandes.show', $commande->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucune commande pour ce client</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-20 h-20 rounded-full bg-carbon-fiber flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <h4 class="text-white font-bold text-lg">{{ $client->firstname }} {{ $client->lastname }}</h4>
                            <p class="text-polished-chrome">Client depuis {{ $client->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Commandes totales</span>
                                <span class="font-bold text-white">{{ $stats['total_commandes'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Première commande</span>
                                <span class="font-bold text-white">{{ $stats['premiere_commande'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Dernière commande</span>
                                <span class="font-bold text-white">{{ $stats['derniere_commande'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-deep-metal rounded-lg p-4">
                        <h4 class="text-white font-bold text-lg mb-4">Actions rapides</h4>
                        <div class="space-y-3">
                            <a href="{{ route('clients.edit', $client->id) }}" class="block w-full p-3 bg-exhaust-blue hover:bg-blue-700 text-white rounded-md text-center transition-colors">
                                Modifier ce client
                            </a>
                            @if($client->commandes->count() == 0)
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full p-3 bg-engine-red hover:bg-red-700 text-white rounded-md text-center transition-colors">
                                        Supprimer ce client
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('clients.index') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
</x-app-layout>