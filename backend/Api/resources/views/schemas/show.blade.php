<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('Détails de la pièce') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">{{ $schema->nom }} <span
                    class="text-polished-chrome/70 text-sm font-normal ml-2">(Version: {{ $schema->version }})</span>
            </h3>
            <div class="flex space-x-2">
                <a href="{{ route('schemas.edit', $schema->id) }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                @if ($schema->enfants->count() == 0 && $schema->commandes->count() == 0)
                    <form action="{{ route('schemas.destroy', $schema->id) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-engine-red hover:bg-red-700 text-white py-2 px-4 rounded flex items-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                        <h4 class="text-white font-bold text-lg mb-3">Informations de la pièce</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">ID</span>
                                    <span class="text-white font-medium">{{ $schema->id }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Nom</span>
                                    <span class="text-white font-medium">{{ $schema->nom }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Numéro de série</span>
                                    <span
                                        class="text-white font-medium">{{ $schema->serial_number ?? 'Non renseigné' }}</span>
                                </div>

                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Version</span>
                                    <span class="text-white font-medium">{{ $schema->version }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Prix</span>
                                    <span
                                        class="text-green-500 font-bold text-xl">{{ number_format($schema->price, 2) }}
                                        €</span>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Date de création</span>
                                    <span
                                        class="text-white font-medium">{{ $schema->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Dernière mise à jour</span>
                                    <span
                                        class="text-white font-medium">{{ $schema->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Pièce parente</span>
                                    @if ($schema->parent)
                                        <a href="{{ route('schemas.show', $schema->parent->id) }}"
                                            class="text-exhaust-blue hover:text-white transition-colors">
                                            {{ $schema->parent->nom }} ({{ $schema->parent->version }})
                                        </a>
                                    @else
                                        <span class="text-polished-chrome">Aucune (pièce racine)</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Moto associée</span>
                                    @if ($schema->moto)
                                        <span class="text-white">
                                            {{ $schema->moto->model->marque }} ({{ $schema->moto->model->annee }})
                                            @if ($schema->moto->client)
                                                <br>
                                                <span class="text-xs text-polished-chrome">Client:
                                                    {{ $schema->moto->client->firstname }}
                                                    {{ $schema->moto->client->lastname }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-polished-chrome">Aucune association</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($schema->enfants->count() > 0)
                        <div class="content-panel mb-6">
                            <div class="panel-header">
                                <h4 class="panel-title text-base">Sous-pièces ({{ $schema->enfants->count() }})</h4>
                            </div>
                            <div class="panel-body p-0">
                                <table class="moto-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Nom</th>
                                            <th class="text-left">Version</th>
                                            <th class="text-center">Prix</th>
                                            <th class="text-center">Sous-pièces</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schema->enfants as $enfant)
                                            <tr>
                                                <td class="font-medium">{{ $enfant->nom }}</td>
                                                <td>{{ $enfant->version }}</td>
                                                <td class="text-center text-green-500 font-semibold">
                                                    {{ number_format($enfant->price, 2) }} €</td>
                                                <td class="text-center">
                                                    @if ($enfant->enfants->count() > 0)
                                                        <span
                                                            class="px-2 py-1 bg-exhaust-blue text-white text-xs font-bold rounded-full">
                                                            {{ $enfant->enfants->count() }}
                                                        </span>
                                                    @else
                                                        <span class="text-polished-chrome/50">0</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <a href="{{ route('schemas.show', $enfant->id) }}"
                                                        class="text-exhaust-blue hover:text-white transition-colors">
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($commandesRecentes && $commandesRecentes->count() > 0)
                        <div class="content-panel">
                            <div class="panel-header flex justify-between items-center">
                                <h4 class="panel-title text-base">Commandes récentes</h4>
                                @if ($totalCommandes > 5)
                                    <span class="text-polished-chrome/70 text-sm">Affichant 5 sur
                                        {{ $totalCommandes }}</span>
                                @endif
                            </div>
                            <div class="panel-body p-0">
                                <table class="moto-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">ID</th>
                                            <th class="text-left">Client</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-left">Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($commandesRecentes as $commande)
                                            <tr>
                                                <td class="font-medium">#{{ $commande->id }}</td>
                                                <td>
                                                    <a href="{{ route('clients.show', $commande->client->id) }}"
                                                        class="text-exhaust-blue hover:text-white transition-colors">
                                                        {{ $commande->client->firstname }}
                                                        {{ $commande->client->lastname }}
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ $commande->quantite }}</td>
                                                <td class="text-center text-green-500 font-semibold">
                                                    {{ number_format($commande->total, 2) }} €</td>
                                                <td class="text-center">
                                                    @if ($commande->status == 'en_attente')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                                            En attente
                                                        </span>
                                                    @elseif($commande->status == 'en_traitement')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                                                            En traitement
                                                        </span>
                                                    @elseif($commande->status == 'expediee')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-200 text-indigo-800">
                                                            Expédiée
                                                        </span>
                                                    @elseif($commande->status == 'livree')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-200 text-green-800">
                                                            Livrée
                                                        </span>
                                                    @elseif($commande->status == 'annulee')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-200 text-red-800">
                                                            Annulée
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('commandes.show', $commande->id) }}"
                                                        class="text-exhaust-blue hover:text-white transition-colors">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div>

                    <!-- Pour l'image -->
                    @if ($schema->image)
                        <div class="mt-4">
                            <span class="block text-polished-chrome/70 text-sm mb-2">Image</span>
                            <img src="{{ asset('storage/' . $schema->image) }}" alt="{{ $schema->nom }}"
                                class="rounded-lg max-w-full h-auto">
                        </div>
                    @endif

                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-4">Statistiques</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Commandes totales</span>
                                <span class="font-bold text-white">{{ $totalCommandes }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Sous-pièces</span>
                                <span class="font-bold text-white">{{ $schema->enfants->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Prix unitaire</span>
                                <span class="font-bold text-green-500">{{ number_format($schema->price, 2) }} €</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Revenue généré</span>
                                <span class="font-bold text-green-500">
                                    {{ number_format($totalCommandes > 0 ? $schema->commandes->sum('total') : 0, 2) }}
                                    €
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($schema->moto)
                        <div class="bg-deep-metal rounded-lg p-4 mb-6">
                            <h4 class="text-white font-bold text-lg mb-4">Détails moto associée</h4>
                            <div class="space-y-3">
                                <div class="p-3 rounded bg-carbon-fiber">
                                    <p class="text-polished-chrome mb-1">Marque & Modèle</p>
                                    <p class="text-white font-medium">{{ $schema->moto->model->marque }}</p>
                                    <p class="text-white text-sm">Année: {{ $schema->moto->model->annee }}</p>
                                </div>
                                @if ($schema->moto->client)
                                    <div class="p-3 rounded bg-carbon-fiber">
                                        <p class="text-polished-chrome mb-1">Propriétaire</p>
                                        <a href="{{ route('clients.show', $schema->moto->client->id) }}"
                                            class="text-exhaust-blue hover:text-white transition-colors">
                                            {{ $schema->moto->client->firstname }}
                                            {{ $schema->moto->client->lastname }}
                                        </a>
                                        <p class="text-polished-chrome/70 text-xs mt-1">
                                            {{ $schema->moto->client->email }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($schema->parent)
                        <div class="bg-deep-metal rounded-lg p-4 mb-6">
                            <h4 class="text-white font-bold text-lg mb-4">Hiérarchie</h4>
                            <div class="space-y-3">
                                <div class="p-3 rounded bg-carbon-fiber">
                                    <span class="block text-polished-chrome mb-2">Pièce parente</span>
                                    <a href="{{ route('schemas.show', $schema->parent->id) }}"
                                        class="flex items-center text-exhaust-blue hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                        {{ $schema->parent->nom }} ({{ $schema->parent->version }})
                                    </a>
                                    <p class="text-green-500 font-medium text-sm mt-1">
                                        {{ number_format($schema->parent->price, 2) }} €</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-deep-metal rounded-lg p-4">
                        <h4 class="text-white font-bold text-lg mb-4">Actions rapides</h4>
                        <div class="space-y-3">
                            <a href="{{ route('schemas.edit', $schema->id) }}"
                                class="block w-full p-3 bg-exhaust-blue hover:bg-blue-700 text-white rounded-md text-center transition-colors">
                                Modifier cette pièce
                            </a>
                            <a href="{{ route('commandes.create') }}?schema_id={{ $schema->id }}"
                                class="block w-full p-3 bg-fuel-yellow hover:bg-yellow-600 text-white rounded-md text-center transition-colors">
                                Créer une commande
                            </a>
                            <a href="{{ route('schemas.create') }}?parent_id={{ $schema->id }}"
                                class="block w-full p-3 bg-engine-red hover:bg-red-700 text-white rounded-md text-center transition-colors">
                                Ajouter une sous-pièce
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('schemas.index') }}"
            class="text-exhaust-blue hover:text-white transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
</x-app-layout>
