<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3zm-7-7h4v4H4v-4z" />
            </svg>
            {{ __('Gestion des Motos') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Liste des motos</h3>
            <a href="{{ route('motos.create') }}" class="moto-button py-2 px-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle moto
            </a>
        </div>
        <div class="panel-body p-4">
            <!-- Filtres -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="col-span-1 lg:col-span-3">
                    <form action="{{ route('motos.index') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                        <div class="flex-grow sm:min-w-[200px]">
                            <label for="search" class="block text-polished-chrome text-sm font-medium mb-1">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Marque..." class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="marque" class="block text-polished-chrome text-sm font-medium mb-1">Marque</label>
                            <select name="marque" id="marque" class="form-input">
                                <option value="">Toutes les marques</option>
                                @foreach($marques as $marque)
                                    <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="annee" class="block text-polished-chrome text-sm font-medium mb-1">Année</label>
                            <select name="annee" id="annee" class="form-input">
                                <option value="">Toutes les années</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[200px]">
                            <label for="model_id" class="block text-polished-chrome text-sm font-medium mb-1">Modèle</label>
                            <select name="model_id" id="model_id" class="form-input">
                                <option value="">Tous les modèles</option>
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>{{ $model->marque }} ({{ $model->annee }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:w-[200px]">
                            <label for="client_id" class="block text-polished-chrome text-sm font-medium mb-1">Client</label>
                            <select name="client_id" id="client_id" class="form-input">
                                <option value="">Tous les clients</option>
                                <option value="none" {{ request('client_id') === 'none' ? 'selected' : '' }}>Sans client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->firstname }} {{ $client->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="moto-button py-2 px-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'marque', 'annee', 'model_id', 'client_id']))
                                <a href="{{ route('motos.index') }}" class="bg-carbon-fiber hover:bg-gray-700 text-polished-chrome py-2 px-4 rounded flex items-center transition-colors">
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
                                <span class="text-polished-chrome">Total motos</span>
                                <span class="font-bold text-white">{{ $totalMotos }}</span>
                            </div>
                        </div>
                        <div class="p-2 rounded bg-carbon-fiber">
                            <span class="text-polished-chrome block mb-2">Répartition par marque</span>
                            <ul class="space-y-1">
                                @foreach($motosByMarque as $stat)
                                    <li class="flex justify-between text-sm">
                                        <span class="text-polished-chrome">{{ $stat->marque }}</span>
                                        <span class="text-white font-medium">{{ $stat->total }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 gap-2">
                        <a href="{{ route('motos.export') }}" class="bg-exhaust-blue hover:bg-blue-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Exporter en CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tableau des motos -->
            <div class="overflow-x-auto">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th class="text-left">Image</th>
                            <th class="text-left">ID</th>
                            <th class="text-left">Marque</th>
                            <th class="text-left">Année</th>
                            <th class="text-left">Client</th>
                            <th class="text-center">Date d'ajout</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($motos as $moto)
                            <tr>
                                <td>
                                    <div class="w-12 h-12 bg-carbon-fiber rounded-md flex items-center justify-center overflow-hidden">
                                        @if($moto->image)
                                            <img src="{{ asset('storage/' . $moto->image) }}" alt="Moto" style="width: 100px; height: 50px; object-fit: cover;">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-polished-chrome/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-medium">#{{ $moto->id }}</td>
                                <td>{{ $moto->model->marque }}</td>
                                <td>{{ $moto->model->annee }}</td>
                                <td>
                                    @if($moto->client)
                                        <a href="{{ route('clients.show', $moto->client->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                            {{ $moto->client->firstname }} {{ $moto->client->lastname }}
                                        </a>
                                    @else
                                        <span class="text-polished-chrome/50 text-sm">Non assigné</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $moto->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('motos.show', $moto->id) }}" class="p-1.5 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('motos.edit', $moto->id) }}" class="p-1.5 bg-exhaust-blue hover:bg-blue-700 text-white rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('motos.destroy', $moto->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette moto ?');">
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
                                <td colspan="7" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1m-6 0a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2h-6a2 2 0 00-2 2v3zm-7-7h4v4H4v-4z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucune moto trouvée</span>
                                        @if(request()->anyFilled(['search', 'marque', 'annee', 'model_id', 'client_id']))
                                            <a href="{{ route('motos.index') }}" class="text-engine-red hover:text-white mt-2 flex items-center">
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
                {{ $motos->links() }}
            </div>
        </div>
    </div>

    <div class="content-panel mt-6">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Guide d'utilisation des motos</h3>
        </div>
        <div class="panel-body p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-exhaust-blue rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Créer une moto</h4>
                    </div>
                    <p class="text-polished-chrome">Utilisez le bouton "Nouvelle moto" pour ajouter une moto au catalogue. Vous devrez sélectionner un modèle existant.</p>
                </div>
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-fuel-yellow rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Filtrage avancé</h4>
                    </div>
                    <p class="text-polished-chrome">Utilisez les filtres pour retrouver rapidement les motos par marque, année, modèle ou client spécifique.</p>
                </div>
                <div class="bg-carbon-fiber p-4 rounded-lg">
                    <div class="flex items-start mb-3">
                        <div class="bg-engine-red rounded-full p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-white text-lg font-bold">Gestion des images</h4>
                    </div>
                    <p class="text-polished-chrome">Ajoutez des photos de motos pour une meilleure identification. Les images apparaîtront dans la liste et les fiches détaillées.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>