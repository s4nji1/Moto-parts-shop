<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Créer une nouvelle pièce') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Informations de la pièce</h3>
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

            <form action="{{ route('schemas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="nom" class="block text-polished-chrome text-sm font-medium mb-1">Nom de la pièce <span class="text-engine-red">*</span></label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="form-input" placeholder="Ex: Carburateur">
                            <p class="text-polished-chrome/70 text-xs mt-1">Le nom doit être précis et descriptif.</p>
                        </div>

                        <div class="mb-4">
                            <label for="serial_number" class="block text-polished-chrome text-sm font-medium mb-1">Numéro de série</label>
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $schema->serial_number ?? '') }}" class="form-input" placeholder="Ex: SN-12345-ABC">
                            <p class="text-polished-chrome/70 text-xs mt-1">Numéro de série unique de la pièce</p>
                        </div>

                        <div class="mb-4">
                            <label for="version" class="block text-polished-chrome text-sm font-medium mb-1">Version <span class="text-engine-red">*</span></label>
                            <input type="text" name="version" id="version" value="{{ old('version') }}" required class="form-input" placeholder="Ex: v1.2 ou 2023-A">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez la version ou le modèle de la pièce.</p>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-polished-chrome text-sm font-medium mb-1">Prix <span class="text-engine-red">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', 0) }}" step="0.01" min="0" required class="form-input" placeholder="0.00">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez le prix unitaire de la pièce (en €).</p>
                        </div>

                        <div class="mb-4">
                            <label for="parent_id" class="block text-polished-chrome text-sm font-medium mb-1">Pièce parente</label>
                            <select name="parent_id" id="parent_id" class="form-input">
                                <option value="">Aucune (pièce racine)</option>
                                @foreach($parentSchemas as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->nom }} ({{ $parent->version }}) - {{ number_format($parent->price, 2) }} €
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
                                    <option value="{{ $moto->id }}" {{ old('moto_id') == $moto->id ? 'selected' : '' }}>
                                        {{ $moto->model->marque }} ({{ $moto->model->annee }})
                                        @if($moto->client)
                                            - {{ $moto->client->firstname }} {{ $moto->client->lastname }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Associez cette pièce à un modèle de moto spécifique si applicable.</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-polished-chrome text-sm font-medium mb-1">Image</label>
                        <input type="file" name="image" id="image" class="form-input">
                        <p class="text-polished-chrome/70 text-xs mt-1">Image de la pièce (JPG, PNG)</p>
                    </div>

                    <div class="bg-carbon-fiber p-4 rounded-lg">
                        <h4 class="text-white font-bold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Guide pour l'ajout de pièces
                        </h4>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Le <strong class="text-white">nom</strong> doit être unique et descriptif pour faciliter l'identification.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">La <strong class="text-white">version</strong> aide à distinguer les variations d'une même pièce pour différents modèles.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Le <strong class="text-white">prix</strong> sera utilisé pour calculer automatiquement le total des commandes.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Une pièce peut être un <strong class="text-white">composant d'une autre pièce</strong> en sélectionnant une pièce parente.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">L'association à une <strong class="text-white">moto</strong> permet de filtrer les pièces compatibles avec un modèle spécifique.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Attention à ne pas créer de <strong class="text-white">relations circulaires</strong> (une pièce ne peut pas être son propre composant).</span>
                            </li>
                        </ul>

                        <div class="bg-deep-metal rounded p-3 border border-gray-700">
                            <h5 class="font-bold text-white mb-2">Structure recommandée</h5>
                            <p class="text-polished-chrome text-sm">Pour une moto, commencez par les pièces principales (moteur, cadre, système électrique), puis ajoutez leurs composants.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('schemas.index') }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Créer la pièce</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>