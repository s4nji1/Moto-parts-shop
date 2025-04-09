<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            {{ __('Gestion des Clients') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Liste des clients</h3>
            <a href="{{ route('clients.create') }}" class="moto-button py-2 px-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau client
            </a>
        </div>
        <div class="panel-body p-4">
            <!-- Filtres -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="col-span-1 lg:col-span-3">
                    <form action="{{ route('clients.index') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                        <div class="flex-grow sm:min-w-[200px]">
                            <label for="search" class="block text-polished-chrome text-sm font-medium mb-1">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="date_from" class="block text-polished-chrome text-sm font-medium mb-1">Date début</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
                        </div>
                        <div class="sm:w-[180px]">
                            <label for="date_to" class="block text-polished-chrome text-sm font-medium mb-1">Date fin</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="moto-button py-2 px-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'date_from', 'date_to']))
                                <a href="{{ route('clients.index') }}" class="bg-carbon-fiber hover:bg-gray-700 text-polished-chrome py-2 px-4 rounded flex items-center transition-colors">
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
                                <span class="text-polished-chrome">Total clients</span>
                                <span class="font-bold text-white">{{ $totalClients }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Nouveaux ce mois</span>
                                <span class="font-bold text-exhaust-blue">{{ $newClientsThisMonth }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Clients actifs</span>
                                <span class="font-bold text-engine-red">{{ $activeClients }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 gap-2">
                        <a href="{{ route('clients.export') }}" class="bg-exhaust-blue hover:bg-blue-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Exporter en CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tableau des clients -->
            <div class="overflow-x-auto">
                <table class="moto-table">
                    <thead>
                        <tr>
                            <th class="text-left">Nom</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">Téléphone</th>
                            <th class="text-center">Commandes</th>
                            <th class="text-center">Date d'inscription</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td class="font-medium">{{ $client->firstname }} {{ $client->lastname }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone }}</td>
                                <td class="text-center">
                                    @php
                                        $commandesCount = $client->commandes->count();
                                    @endphp
                                    @if($commandesCount > 0)
                                        <span class="px-2 py-1 bg-engine-red text-white text-xs font-bold rounded-full">
                                            {{ $commandesCount }}
                                        </span>
                                    @else
                                        <span class="text-polished-chrome/50">0</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $client->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('clients.show', $client->id) }}" class="p-1.5 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="p-1.5 bg-exhaust-blue hover:bg-blue-700 text-white rounded transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if($client->commandes->count() == 0)
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
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
                                <td colspan="6" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="text-polished-chrome/70">Aucun client trouvé</span>
                                        @if(request()->anyFilled(['search', 'date_from', 'date_to']))
                                            <a href="{{ route('clients.index') }}" class="text-engine-red hover:text-white mt-2 flex items-center">
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
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>