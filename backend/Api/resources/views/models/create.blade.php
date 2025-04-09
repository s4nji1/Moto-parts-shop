<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Ajouter un nouveau modèle de moto') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Informations du modèle</h3>
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

            <form action="{{ route('models.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="marque" class="block text-polished-chrome text-sm font-medium mb-1">Marque <span class="text-engine-red">*</span></label>
                            <input type="text" name="marque" id="marque" value="{{ old('marque') }}" required class="form-input" placeholder="Ex: Honda, Yamaha, BMW">
                            <p class="text-polished-chrome/70 text-xs mt-1">Saisissez le nom complet de la marque de moto.</p>
                        </div>

                        <div class="mb-4">
                            <label for="annee" class="block text-polished-chrome text-sm font-medium mb-1">Année <span class="text-engine-red">*</span></label>
                            <input type="number" name="annee" id="annee" value="{{ old('annee', date('Y')) }}" required class="form-input" min="1900" max="{{ date('Y') }}">
                            <p class="text-polished-chrome/70 text-xs mt-1">Année de fabrication du modèle.</p>
                        </div>
                    </div>

                    <div class="bg-carbon-fiber p-4 rounded-lg">
                        <h4 class="text-white font-bold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informations importantes
                        </h4>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">La <strong class="text-white">marque</strong> doit être le nom officiel du constructeur.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">L'<strong class="text-white">année</strong> doit correspondre à l'année de fabrication du modèle.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Soyez précis pour une meilleure gestion des modèles.</span>
                            </li>
                        </ul>

                        <div class="bg-deep-metal rounded p-3 border border-gray-700">
                            <h5 class="font-bold text-white mb-2">Conseil</h5>
                            <p class="text-polished-chrome text-sm">Un modèle représente une configuration spécifique de moto pour une marque et une année données.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('models.index') }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Créer le modèle</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>