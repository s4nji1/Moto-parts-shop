<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ __('Modifier la pièce') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Édition de "{{ $schema->nom }}"</h3>
        </div>
        <div class="panel-body p-4">
            @if ($errors->any())
                <div class="bg-engine-red/10 text-engine-red p-4 rounded-md mb-6 border border-engine-red/30">
                    <div class="font-medium">{{ __('Oups! Quelque chose s\'est mal passé.') }}</div>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('schemas.update', $schema->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="nom" class="block text-polished-chrome text-sm font-medium mb-1">Nom de la pièce <span class="text-engine-red">*</span></label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $schema->nom) }}" required class="form-input" placeholder="Ex: Carburateur">
                            <p class="text-polished-chrome/70 text-xs mt-1">Le nom doit être précis et descriptif.</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="serial_number" class="block text-polished-chrome text-sm font-medium mb-1">Numéro de série</label>
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $schema->serial_number ?? '') }}" class="form-input" placeholder="Ex: SN-12345-ABC">
                            <p class="text-polished-chrome/70 text-xs mt-1">Numéro de série unique de la pièce</p>
                        </div>

                        <div class="mb-4">
                            <label for="version" class="block text-polished-chrome text-sm font-medium mb-1">Version <span class="text-engine-red">*</span></label>
                            <input type="text" name="version" id="version" value="{{ old('version', $schema->version) }}" required class="form-input" placeholder="Ex: v1.2 ou 2023-A">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez la version ou le modèle de la pièce.</p>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-polished-chrome text-sm font-medium mb-1">Prix <span class="text-engine-red">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', $schema->price) }}" step="0.01" min="0" required class="form-input" placeholder="0.00">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez le prix unitaire de la pièce (en €).</p>
                        </div>

                        <div class="mb-4">
                            <label for="parent_id" class="block text-polished-chrome text-sm font-medium mb-1">Pièce parente</label>
                            <select name="parent_id" id="parent_id" class="form-input">
                                <option value="">Aucune (pièce racine)</option>
                                @foreach($parentSchemas as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $schema->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->nom }} ({{ $parent->version }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Sélectionnez la pièce parente si cette pièce est un composant d'une autre pièce.</p>
                        </div>

                        <div class="mb-4">
                            <label for="moto_id" class="block text-polished-chrome text-sm font-medium mb-1">Associer à une moto</label>
                            <select name="moto_id" id="moto_id" class="form-input">
                                <option value="">Aucune association</option>
                                @foreach($motos as $moto)
                                    <option value="{{ $moto->id }}" {{ old('moto_id', $schema->moto_id) == $moto->id ? 'selected' : '' }}>
                                        {{ $moto->model->marque }} ({{ $moto->model->annee }})
                                        @if($moto->client)
                                            - {{ $moto->client->firstname }} {{ $moto->client->lastname }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Associez cette pièce à un modèle de moto spécifique si applicable.</p>
                        </div>

                        <div class="mt-6 bg-engine-red/10 border border-engine-red/20 rounded-md p-3">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-engine-red font-bold text-sm">Attention à la relation hiérarchique</h4>
                                    <p class="text-polished-chrome text-xs mt-1">Une pièce ne peut pas être son propre parent, ni avoir comme parent l'une de ses sous-pièces. Cela créerait une relation circulaire.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-carbon-fiber p-4 rounded-lg mb-4">
                            <h4 class="text-white font-bold mb-3">Informations sur cette pièce</h4>
                            <div class="space-y-3 mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">ID</span>
                                        <span class="text-white text-sm">{{ $schema->id }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">Date de création</span>
                                        <span class="text-white text-sm">{{ $schema->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Dernière modification</span>
                                    <span class="text-white text-sm">{{ $schema->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Prix actuel</span>
                                    <span class="text-white text-sm font-bold">{{ number_format($schema->price, 2) }} €</span>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Moto associée actuelle</span>
                                    <span class="text-white text-sm">
                                        @if($schema->moto)
                                            {{ $schema->moto->model->marque }} ({{ $schema->moto->model->annee }})
                                            @if($schema->moto->client)
                                                - {{ $schema->moto->client->firstname }} {{ $schema->moto->client->lastname }}
                                            @endif
                                        @else
                                            Aucune association
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($schema->enfants->count() > 0)
                            <div class="bg-carbon-fiber p-4 rounded-lg mb-4">
                                <h4 class="text-white font-bold mb-3">Sous-pièces ({{ $schema->enfants->count() }})</h4>
                                <ul class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                    @foreach($schema->enfants as $enfant)
                                        <li class="flex items-center justify-between bg-deep-metal p-2 rounded">
                                            <span class="text-polished-chrome text-sm">{{ $enfant->nom }} ({{ $enfant->version }})</span>
                                            <span class="text-green-500 text-sm">{{ number_format($enfant->price, 2) }} €</span>
                                            <a href="{{ route('schemas.show', $enfant->id) }}" class="text-exhaust-blue hover:text-white text-xs transition-colors">
                                                Voir
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($schema->commandes->count() > 0)
                            <div class="bg-carbon-fiber p-4 rounded-lg">
                                <h4 class="text-white font-bold mb-3">Commandes associées</h4>
                                <div class="bg-engine-red/10 border border-engine-red/30 rounded p-2 mb-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-polished-chrome text-sm">Cette pièce est utilisée dans <strong class="text-white">{{ $schema->commandes->count() }}</strong> commande(s).</span>
                                    </div>
                                </div>
                                <div class="bg-yellow-900/20 border border-yellow-900/30 rounded p-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-polished-chrome text-sm">La modification du prix mettra à jour le total de toutes les commandes associées.</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-polished-chrome text-sm font-medium mb-1">Image</label>
                    <input type="file" name="image" id="image" class="form-input">
                    <p class="text-polished-chrome/70 text-xs mt-1">Image de la pièce (JPG, PNG)</p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('schemas.show', $schema->id) }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>