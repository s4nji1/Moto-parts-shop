<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('Détails de la moto') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Moto #{{ $moto->id }} - {{ $moto->model->marque }} {{ $moto->model->annee }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('motos.edit', $moto->id) }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('motos.destroy', $moto->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette moto ?');">
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
                        <h4 class="text-white font-bold text-lg mb-3">Informations de la moto</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">ID</span>
                                    <span class="text-white font-medium">{{ $moto->id }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Marque</span>
                                    <span class="text-white font-medium">{{ $moto->model->marque }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Année</span>
                                    <span class="text-white font-medium">{{ $moto->model->annee }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Date d'ajout</span>
                                    <span class="text-white font-medium">{{ $moto->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Dernière mise à jour</span>
                                    <span class="text-white font-medium">{{ $moto->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Client propriétaire</span>
                                    @if($moto->client)
                                        <a href="{{ route('clients.show', $moto->client->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                            {{ $moto->client->firstname }} {{ $moto->client->lastname }}
                                        </a>
                                        <span class="block text-polished-chrome/50 text-xs mt-1">{{ $moto->client->email }}</span>
                                    @else
                                        <span class="text-polished-chrome">Aucun client assigné</span>
                                    @endif
                                </div>
                                <!-- Ajoutez ici d'autres attributs pertinents de votre modèle Moto -->
                            </div>
                        </div>
                    </div>

                    <div class="content-panel mb-6">
                        <div class="panel-header">
                            <h4 class="panel-title text-base">Spécifications techniques du modèle</h4>
                        </div>
                        <div class="panel-body p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-deep-metal rounded-lg p-3">
                                    <span class="block text-polished-chrome/70 text-sm">Marque</span>
                                    <span class="text-white font-medium">{{ $moto->model->marque }}</span>
                                </div>
                                <div class="bg-deep-metal rounded-lg p-3">
                                    <span class="block text-polished-chrome/70 text-sm">Année de fabrication</span>
                                    <span class="text-white font-medium">{{ $moto->model->annee }}</span>
                                </div>
                                <!-- Vous pouvez ajouter ici d'autres informations techniques du modèle si vous étendez votre modèle MotoModel -->
                            </div>
                        </div>
                    </div>

                    <!-- Affichage des pièces compatibles ou associées à cette moto -->
                    <div class="content-panel">
                        <div class="panel-header">
                            <h4 class="panel-title text-base">Pièces compatibles</h4>
                        </div>
                        <div class="panel-body p-4">
                            @if(isset($schemas) && count($schemas) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($schemas as $schema)
                                        <div class="bg-deep-metal rounded-lg p-3 flex items-center">
                                            <div class="w-10 h-10 bg-carbon-fiber rounded-md flex items-center justify-center mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-exhaust-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <a href="{{ route('schemas.show', $schema->id) }}" class="block text-white font-medium hover:text-exhaust-blue transition-colors">
                                                    {{ $schema->nom }}
                                                </a>
                                                <span class="text-polished-chrome/70 text-sm">v{{ $schema->version }} - {{ number_format($schema->price, 2) }} €</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-polished-chrome">Les pièces compatibles avec ce modèle de moto seront affichées ici. Vous pouvez établir une relation entre votre modèle de moto et les schémas de pièces pour afficher les pièces compatibles.</p>
                                
                                <div class="mt-4">
                                    <a href="{{ route('schemas.create') }}?moto_id={{ $moto->id }}" class="text-exhaust-blue hover:text-white flex items-center w-fit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Ajouter une pièce compatible
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-4">Photo de la moto</h4>
                        <div class="bg-carbon-fiber rounded-lg p-3 flex justify-center items-center h-60 overflow-hidden">
                            @if($moto->image)
                                <img src="{{ asset('storage/'.$moto->image) }}" alt="Photo de la moto {{ $moto->model->marque }} {{ $moto->model->annee }}" class="max-h-full max-w-full object-contain">
                            @else
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-polished-chrome/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-polished-chrome/50 text-sm mt-2">Aucune image disponible</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-polished-chrome">{{ $moto->model->marque }} - {{ $moto->model->annee }}</p>
                            <a href="{{ route('motos.edit', $moto->id) }}" class="text-exhaust-blue hover:text-white text-sm flex items-center justify-center mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $moto->image ? 'Modifier l\'image' : 'Ajouter une image' }}
                            </a>
                        </div>
                    </div>

                    @if($moto->client)
                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-4">Informations du client</h4>
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-carbon-fiber flex items-center justify-center mr-3">
                                <span class="text-white font-bold">{{ substr($moto->client->firstname, 0, 1) }}{{ substr($moto->client->lastname, 0, 1) }}</span>
                            </div>
                            <div>
                                <h5 class="text-white font-medium">{{ $moto->client->firstname }} {{ $moto->client->lastname }}</h5>
                                <span class="text-polished-chrome/70 text-sm">{{ $moto->client->email }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="bg-carbon-fiber rounded p-2 flex justify-between items-center">
                                <span class="text-polished-chrome text-sm">Téléphone</span>
                                <span class="text-white">{{ $moto->client->phone }}</span>
                            </div>
                            @if($moto->client->address)
                            <div class="bg-carbon-fiber rounded p-2">
                                <span class="block text-polished-chrome text-sm mb-1">Adresse</span>
                                <span class="text-white text-sm">{{ $moto->client->address }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('clients.show', $moto->client->id) }}" class="block w-full bg-exhaust-blue hover:bg-blue-700 text-white rounded py-2 px-3 text-sm text-center transition-colors">
                                Voir le profil complet
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="bg-deep-metal rounded-lg p-4">
                        <h4 class="text-white font-bold text-lg mb-4">Actions rapides</h4>
                        <div class="space-y-3">
                            <a href="{{ route('motos.edit', $moto->id) }}" class="block w-full p-3 bg-exhaust-blue hover:bg-blue-700 text-white rounded-md text-center transition-colors">
                                Modifier cette moto
                            </a>
                            <a href="{{ route('schemas.create') }}?moto_id={{ $moto->id }}" class="block w-full p-3 bg-fuel-yellow hover:bg-yellow-600 text-white rounded-md text-center transition-colors">
                                Ajouter une pièce compatible
                            </a>
                            <form action="{{ route('motos.destroy', $moto->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette moto ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full p-3 bg-engine-red hover:bg-red-700 text-white rounded-md text-center transition-colors">
                                    Supprimer cette moto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('motos.index') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
</x-app-layout>