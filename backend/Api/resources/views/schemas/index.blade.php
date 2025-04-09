<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
            {{ __('Pièces Détachées') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Liste des pièces</h3>
            <div class="flex space-x-2">
                <a href="{{ route('schemas.create') }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle pièce
                </a>
                <a href="{{ route('schemas.arborescence') }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    Voir l'arborescence
                </a>
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="col-span-1 lg:col-span-3">
                    <form action="{{ route('schemas.index') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                        <div class="flex-grow sm:min-w-[200px]">
                            <label for="search" class="block text-polished-chrome text-sm font-medium mb-1">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom ou référence..." class="form-input">
                        </div>
                        <div class="sm:w-[150px]">
                            <label for="version" class="block text-polished-chrome text-sm font-medium mb-1">Version</label>
                            <select name="version" id="version" class="form-input">
                                <option value="">Toutes les versions</option>
                                @foreach($versions as $version)
                                    <option value="{{ $version }}" {{ request('version') == $version ? 'selected' : '' }}>{{ $version }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[200px]">
                            <label for="parent_id" class="block text-polished-chrome text-sm font-medium mb-1">Pièce parente</label>
                            <select name="parent_id" id="parent_id" class="form-input">
                                <option value="">Toutes les pièces</option>
                                <option value="null" {{ request('parent_id') === 'null' ? 'selected' : '' }}>Pièces racines</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[200px]">
                            <label for="moto_id" class="block text-polished-chrome text-sm font-medium mb-1">Moto</label>
                            <select name="moto_id" id="moto_id" class="form-input">
                                <option value="">Toutes les motos</option>
                                <option value="null" {{ request('moto_id') === 'null' ? 'selected' : '' }}>Sans association</option>
                                @foreach($motos as $moto)
                                    <option value="{{ $moto->id }}" {{ request('moto_id') == $moto->id ? 'selected' : '' }}>
                                        {{ $moto->model->marque }} ({{ $moto->model->annee }})
                                        @if($moto->client)
                                            - {{ $moto->client->firstname }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[150px]">
                            <label for="min_price" class="block text-polished-chrome text-sm font-medium mb-1">Prix min</label>
                            <input type="number" step="0.01" name="min_price" id="min_price" value="{{ request('min_price') }}" class="form-input">
                        </div>
                        <div class="sm:w-[150px]">
                            <label for="max_price" class="block text-polished-chrome text-sm font-medium mb-1">Prix max</label>
                            <input type="number" step="0.01" name="max_price" id="max_price" value="{{ request('max_price') }}" class="form-input">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="moto-button py-2 px-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrer
                            </button>
                            @if(request()->has('search') || request()->has('version') || request()->has('parent_id') || request()->has('moto_id') || request()->has('min_price') || request()->has('max_price'))
                                <a href="{{ route('schemas.index') }}" class="moto-button py-2 px-4 flex items-center bg-carbon-fiber hover:bg-gray-700">
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
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Total des pièces</span>
                                <span class="font-bold text-white">{{ $totalSchemas }}</span>
                            </div>
                            @if(count($topSchemas) > 0)
                                <div class="p-2 rounded bg-carbon-fiber">
                                    <span class="text-polished-chrome block mb-2">Pièces les plus demandées</span>
                                    <ul class="space-y-1 ml-2">
                                        @foreach($topSchemas as $piece)
                                            <li class="text-sm">
                                                <span class="text-white">{{ $piece->nom }}</span>
                                                <span class="text-exhaust-blue ml-1">({{ $piece->total_commandes }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th class="text-left">Nom</th>
                            <th class="text-left">Version</th>
                            <th class="text-center">Prix</th>
                            <th class="text-left">Pièce parente</th>
                            <th class="text-left">Moto associée</th>
                            <th class="text-center">Sous-pièces</th>
                            <th class="text-center">Commandes</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schemas as $schema)
                            <tr>
                                <td class="font-medium">{{ $schema->nom }}</td>
                                <td>{{ $schema->version }}</td>
                                <td class="text-center text-green-500 font-semibold">{{ number_format($schema->price, 2) }} €</td>
                                <td>
                                    @if($schema->parent)
                                        <a href="{{ route('schemas.show', $schema->parent->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                            {{ $schema->parent->nom }}
                                        </a>
                                    @else
                                        <span class="text-polished-chrome/50 text-sm">Pas de parent</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schema->moto)
                                        <span class="text-white">
                                            {{ $schema->moto->model->marque }} ({{ $schema->moto->model->annee }})
                                            @if($schema->moto->client)
                                                <span class="text-xs text-polished-chrome/70">- {{ $schema->moto->client->firstname }} {{ $schema->moto->client->lastname }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-polished-chrome/50 text-sm">Non associée</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($schema->enfants->count() > 0)
                                        <span class="px-2 py-1 bg-exhaust-blue text-white text-xs font-bold rounded-full">
                                            {{ $schema->enfants->count() }}
                                        </span>
                                    @else
                                        <span class="text-polished-chrome/50">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $commandesCount = $schema->commandes->count();
                                    @endphp
                                    @if($commandesCount > 0)
                                        <span class="px-2 py-1 bg-engine-red text-white text-xs font-bold rounded-full">
                                            {{ $commandesCount }}
                                        </span>
                                    @else
                                        <span class="text-polished-chrome/50">0</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('schemas.show', $schema->id) }}" class="p-1.5 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('schemas.edit', $schema->id) }}" class="p-1.5 bg-exhaust-blue hover:bg-blue-700 text-white rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if($schema->enfants->count() == 0 && $schema->commandes->count() == 0)
                                            <form action="{{ route('schemas.destroy', $schema->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-engine-red hover:bg-red-700 text-white rounded transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucune pièce trouvée</span>
                                        @if(request()->has('search') || request()->has('version') || request()->has('parent_id') || request()->has('moto_id') || request()->has('min_price') || request()->has('max_price'))
                                            <a href="{{ route('schemas.index') }}" class="text-engine-red hover:text-white mt-2 flex items-center">
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
                {{ $schemas->links() }}
            </div>
        </div>
    </div>

    <div class="content-panel mt-6">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Guide d'utilisation des pièces</h3>
        </div>
        <div class="panel-body p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-exhaust-blue rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Créer une pièce</h4>
                    </div>
                    <p class="text-polished-chrome">Utilisez le bouton "Nouvelle pièce" pour ajouter une pièce. Vous pouvez créer des pièces racines ou les associer à des pièces parentes existantes.</p>
                </div>
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-fuel-yellow rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Organisation hiérarchique</h4>
                    </div>
                    <p class="text-polished-chrome">Utilisez la vue "Arborescence" pour voir la structure hiérarchique des pièces et leurs relations parent/enfant.</p>
                </div>
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-green-600 rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Prix et facturations</h4>
                    </div>
                    <p class="text-polished-chrome">Le prix défini pour chaque pièce sera automatiquement utilisé pour calculer le montant des commandes associées.</p>
                </div>
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-engine-red rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Association aux motos</h4>
                    </div>
                    <p class="text-polished-chrome">Associez les pièces à des motos spécifiques pour faciliter le filtrage et la recherche des pièces compatibles.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>