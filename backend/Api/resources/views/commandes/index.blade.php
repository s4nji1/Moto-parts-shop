<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            {{ __('Gestion des Commandes') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Liste des commandes</h3>
            <a href="{{ route('commandes.create') }}" class="moto-button py-2 px-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle commande
            </a>
        </div>
        <div class="panel-body p-4">
            <!-- Filtres -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="col-span-1 lg:col-span-3">
                    <form action="{{ route('commandes.index') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                        <div class="flex-grow sm:min-w-[200px]">
                            <label for="search" class="block text-polished-chrome text-sm font-medium mb-1">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ID, client..." class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="status" class="block text-polished-chrome text-sm font-medium mb-1">Statut</label>
                            <select name="status" id="status" class="form-input">
                                <option value="">Tous les statuts</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="date_from" class="block text-polished-chrome text-sm font-medium mb-1">Date début</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="date_to" class="block text-polished-chrome text-sm font-medium mb-1">Date fin</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="min_total" class="block text-polished-chrome text-sm font-medium mb-1">Montant min</label>
                            <input type="number" step="0.01" name="min_total" id="min_total" value="{{ request('min_total') }}" class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="max_total" class="block text-polished-chrome text-sm font-medium mb-1">Montant max</label>
                            <input type="number" step="0.01" name="max_total" id="max_total" value="{{ request('max_total') }}" class="form-input">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="moto-button py-2 px-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to', 'min_total', 'max_total', 'client_id', 'schema_id']))
                                <a href="{{ route('commandes.index') }}" class="bg-carbon-fiber hover:bg-gray-700 text-polished-chrome py-2 px-4 rounded flex items-center transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Réinitialiser
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-span-1 bg-deep-metal rounded-md p-4 border border-gray-700 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-white mb-3">Statistiques</h4>
                        <div class="grid grid-cols-1 gap-2 mb-3">
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Total commandes</span>
                                <span class="font-bold text-white">{{ $totalCommandes }}</span>
                            </div>
                            @isset($totalRevenu)
                            <div class="flex justify-between items-center p-2 rounded bg-green-900/20 border border-green-900/30">
                                <span class="text-polished-chrome">Revenu total</span>
                                <span class="font-bold text-green-500">{{ number_format($totalRevenu, 2) }} €</span>
                            </div>
                            @endisset
                            @isset($revenuMensuel)
                            <div class="flex justify-between items-center p-2 rounded bg-green-900/20 border border-green-900/30">
                                <span class="text-polished-chrome">Revenu mensuel</span>
                                <span class="font-bold text-green-500">{{ number_format($revenuMensuel, 2) }} €</span>
                            </div>
                            @endisset
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex justify-between items-center p-2 rounded bg-yellow-900/20 border border-yellow-900/30">
                                <span class="text-polished-chrome text-xs">En attente</span>
                                <span class="font-bold text-yellow-500">{{ $commandesEnAttente }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-blue-900/20 border border-blue-900/30">
                                <span class="text-polished-chrome text-xs">En traitement</span>
                                <span class="font-bold text-blue-500">{{ $commandesEnTraitement }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-indigo-900/20 border border-indigo-900/30">
                                <span class="text-polished-chrome text-xs">Expédiées</span>
                                <span class="font-bold text-indigo-500">{{ $commandesExpediees }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-green-900/20 border border-green-900/30">
                                <span class="text-polished-chrome text-xs">Livrées</span>
                                <span class="font-bold text-green-500">{{ $commandesLivrees }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-green-900/20 border border-green-900/30">
                                <span class="text-polished-chrome text-xs">Annulée</span>
                                <span class="font-bold text-green-500">{{ $commandesAnnulees }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 gap-2">
                        <a href="{{ route('commandes.export') }}" class="bg-exhaust-blue hover:bg-blue-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Exporter en CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tableau des commandes -->
            <div class="overflow-x-auto">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th class="text-left">Client</th>
                            <th class="text-left">Pièce</th>
                            <th class="text-center">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commandes as $commande)
                            <tr>
                                <td class="font-medium">#{{ $commande->id }}</td>
                                <td>
                                    <a href="{{ route('clients.show', $commande->client->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $commande->client->firstname }} {{ $commande->client->lastname }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('schemas.show', $commande->schema->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                        {{ $commande->schema->nom }}
                                    </a>
                                    <span class="text-polished-chrome/70 text-xs">({{ $commande->schema->version }})</span>
                                </td>
                                <td class="text-center">{{ number_format($commande->schema->price, 2) }} €</td>
                                <td class="text-center">{{ $commande->quantite }}</td>
                                <td class="text-center font-semibold text-green-500">{{ number_format($commande->total, 2) }} €</td>
                                <td class="text-center">{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
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
                                </td>
                                <td>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('commandes.show', $commande->id) }}" class="p-1.5 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('commandes.edit', $commande->id) }}" class="p-1.5 bg-exhaust-blue hover:bg-blue-700 text-white rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-engine-red hover:bg-red-700 text-white rounded transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucune commande trouvée</span>
                                        @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to', 'min_total', 'max_total', 'client_id', 'schema_id']))
                                            <a href="{{ route('commandes.index') }}" class="text-engine-red hover:text-white mt-2 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Réinitialiser les filtres
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $commandes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>