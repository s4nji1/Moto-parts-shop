<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ __('Modifier le modèle de moto') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Édition du modèle "{{ $model->marque }} {{ $model->annee }}"</h3>
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

            <form action="{{ route('models.update', $model->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="marque" class="block text-polished-chrome text-sm font-medium mb-1">Marque <span class="text-engine-red">*</span></label>
                            <input type="text" name="marque" id="marque" value="{{ old('marque', $model->marque) }}" required class="form-input" placeholder="Ex: Honda, Yamaha, BMW">
                            <p class="text-polished-chrome/70 text-xs mt-1">Saisissez le nom complet de la marque de moto.</p>
                        </div>

                        <div class="mb-4">
                            <label for="annee" class="block text-polished-chrome text-sm font-medium mb-1">Année <span class="text-engine-red">*</span></label>
                            <input type="number" name="annee" id="annee" value="{{ old('annee', $model->annee) }}" required class="form-input" min="1900" max="{{ date('Y') }}">
                            <p class="text-polished-chrome/70 text-xs mt-1">Année de fabrication du modèle.</p>
                        </div>
                    </div>

                    <div>
                        <div class="bg-carbon-fiber p-4 rounded-lg mb-4">
                            <h4 class="text-white font-bold mb-3">Informations sur ce modèle</h4>
                            <div class="space-y-3 mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">ID</span>
                                        <span class="text-white text-sm">{{ $model->id }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">Date de création</span>
                                        <span class="text-white text-sm">{{ $model->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Dernière modification</span>
                                    <span class="text-white text-sm">{{ $model->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($model->motos->count() > 0)
                            <div class="bg-carbon-fiber p-4 rounded-lg">
                                <h4 class="text-white font-bold mb-3">Motos associées ({{ $model->motos->count() }})</h4>
                                <div class="bg-engine-red/10 border border-engine-red/30 rounded p-2 mb-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-polished-chrome text-sm">Ce modèle a <strong class="text-white">{{ $model->motos->count() }}</strong> moto(s) associée(s).</span>
                                    </div>
                                </div>
                                <p class="text-polished-chrome/70 text-xs mt-1">Toute modification affectera les motos existantes. Assurez-vous que les changements sont appropriés.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('models.show', $model->id) }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>