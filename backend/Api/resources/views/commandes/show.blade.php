<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('Détails de la commande') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Commande #{{ $commande->id }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('commandes.edit', $commande->id) }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-engine-red hover:bg-red-700 text-white py-2 px-4 rounded flex items-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2">
                    <div class="bg-carbon-fiber rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-3">Informations de la commande</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">ID de commande</span>
                                    <span class="text-white font-medium">#{{ $commande->id }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Pièce commandée</span>
                                    <a href="{{ route('schemas.show', $commande->schema->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $commande->schema->nom }} <span class="text-polished-chrome">({{ $commande->schema->version }})</span>
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Prix unitaire</span>
                                    <span class="text-white font-medium">{{ number_format($commande->schema->price, 2) }} €</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Quantité</span>
                                    <span class="text-white font-medium">{{ $commande->quantite }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Montant total</span>
                                    <span class="text-green-500 font-bold text-xl">{{ number_format($commande->total, 2) }} €</span>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Date de commande</span>
                                    <span class="text-white font-medium">{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Dernière mise à jour</span>
                                    <span class="text-white font-medium">{{ $commande->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Statut</span>
                                    @if($commande->status == 'en_attente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                            En attente
                                        </span>
                                    @elseif($commande->status == 'en_cours')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                                            En cours
                                        </span>
                                    @elseif($commande->status == 'confirmee')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-200 text-indigo-800">
                                            Confirmée
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
                                </div>
                                @if($commande->schema->moto)
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Moto associée à la pièce</span>
                                    <span class="text-white font-medium">
                                        {{ $commande->schema->moto->model->marque }} ({{ $commande->schema->moto->model->annee }})
                                        @if($commande->schema->moto->client)
                                            - {{ $commande->schema->moto->client->firstname }} {{ $commande->schema->moto->client->lastname }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="content-panel mb-6">
                        <div class="panel-header">
                            <h4 class="panel-title text-base">Informations client</h4>
                        </div>
                        <div class="panel-body p-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 rounded-full bg-carbon-fiber flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="text-white font-bold">{{ substr($commande->client->firstname, 0, 1) }}{{ substr($commande->client->lastname, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h5 class="font-bold text-white">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</h5>
                                    <a href="mailto:{{ $commande->client->email }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $commande->client->email }}
                                    </a>
                                    <p class="text-polished-chrome mt-1">{{ $commande->client->phone }}</p>
                                    @if($commande->client->address)
                                        <p class="text-polished-chrome/70 mt-2 text-sm">{{ $commande->client->address }}</p>
                                    @endif
                                    <a href="{{ route('clients.show', $commande->client->id) }}" class="text-engine-red hover:text-white text-sm flex items-center mt-3 w-fit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Voir profil complet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-panel">
                        <div class="panel-header">
                            <h4 class="panel-title text-base">Mise à jour du statut</h4>
                        </div>
                        <div class="panel-body p-4">
                            <form action="{{ route('commandes.updateStatus', $commande->id) }}" method="POST">
                                @csrf
                                <div class="flex flex-wrap gap-4">
                                    <select name="status" class="form-input w-full sm:w-auto">
                                        <option value="en_attente" {{ $commande->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="en_cours" {{ $commande->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="confirmee" {{ $commande->status == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                        <option value="livree" {{ $commande->status == 'livree' ? 'selected' : '' }}>Livrée</option>
                                        <option value="annulee" {{ $commande->status == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    <button type="submit" class="moto-button py-2">Mettre à jour le statut</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-4">Détails de la pièce</h4>
                        <div class="mb-4">
                            <img src="/api/placeholder/300/200" alt="Illustration de la pièce" class="w-full h-40 object-contain bg-carbon-fiber rounded-lg mb-3">
                            <h5 class="font-bold text-white">{{ $commande->schema->nom }}</h5>
                            <p class="text-polished-chrome/70 text-sm">Version: {{ $commande->schema->version }}</p>
                            <p class="text-green-500 font-bold mt-2">Prix: {{ number_format($commande->schema->price, 2) }} €</p>
                        </div>
                        <a href="{{ route('schemas.show', $commande->schema->id) }}" class="bg-exhaust-blue hover:bg-blue-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center transition-colors w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir les détails de la pièce
                        </a>
                    </div>

                    <div class="bg-deep-metal rounded-lg p-4">
                        <h4 class="text-white font-bold text-lg mb-4">Actions rapides</h4>
                        <div class="space-y-3">
                            <a href="{{ route('commandes.edit', $commande->id) }}" class="block w-full p-3 bg-exhaust-blue hover:bg-blue-700 text-white rounded-md text-center transition-colors">
                                Modifier cette commande
                            </a>
                            <a href="{{ route('commandes.create') }}?client_id={{ $commande->client->id }}" class="block w-full p-3 bg-fuel-yellow hover:bg-yellow-600 text-white rounded-md text-center transition-colors">
                                Nouvelle commande pour ce client
                            </a>
                            <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full p-3 bg-engine-red hover:bg-red-700 text-white rounded-md text-center transition-colors">
                                    Supprimer cette commande
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('commandes.index') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
</x-app-layout>