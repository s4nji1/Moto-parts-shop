<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Ajouter un nouveau client') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Informations du client</h3>
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

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="firstname" class="block text-polished-chrome text-sm font-medium mb-1">Prénom <span class="text-engine-red">*</span></label>
                                <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" required class="form-input" placeholder="Prénom">
                            </div>
                            <div class="mb-4">
                                <label for="lastname" class="block text-polished-chrome text-sm font-medium mb-1">Nom <span class="text-engine-red">*</span></label>
                                <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required class="form-input" placeholder="Nom">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-polished-chrome text-sm font-medium mb-1">Email <span class="text-engine-red">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input" placeholder="exemple@email.com">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-polished-chrome text-sm font-medium mb-1">Téléphone <span class="text-engine-red">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="form-input" placeholder="0xxxxxxxxx">
                        </div>

                        <div class="mb-4">
                            <label for="cin" class="block text-polished-chrome text-sm font-medium mb-1">CIN</label>
                            <input type="text" name="cin" id="cin" value="{{ old('cin') }}" class="form-input" placeholder="XX123456">
                            <p class="text-polished-chrome/70 text-xs mt-1">Numéro de carte d'identité nationale.</p>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-polished-chrome text-sm font-medium mb-1">Adresse</label>
                            <textarea name="address" id="address" rows="3" class="form-input" placeholder="Adresse complète">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="password" class="block text-polished-chrome text-sm font-medium mb-1">Mot de passe <span class="text-engine-red">*</span></label>
                            <input type="password" name="password" id="password" required class="form-input" placeholder="••••••••">
                            <p class="text-polished-chrome/70 text-xs mt-1">Minimum 8 caractères. Sera utilisé pour la connexion à l'application mobile.</p>
                        </div>

                        <div class="bg-carbon-fiber p-4 rounded-lg mt-6">
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
                                    <span class="text-polished-chrome text-sm">Les champs marqués d'un <strong class="text-engine-red">*</strong> sont obligatoires.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="text-polished-chrome text-sm">L'<strong class="text-white">email</strong> doit être unique et sera utilisé comme identifiant de connexion.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="text-polished-chrome text-sm">Le <strong class="text-white">mot de passe</strong> doit contenir au minimum 8 caractères.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="text-polished-chrome text-sm">Le <strong class="text-white">numéro CIN</strong> doit être unique pour chaque client.</span>
                                </li>
                            </ul>

                            <div class="bg-deep-metal rounded p-3 border border-gray-700">
                                <h5 class="font-bold text-white mb-2">Note sur la protection des données</h5>
                                <p class="text-polished-chrome text-sm">Les informations collectées sont utilisées uniquement dans le cadre de la gestion des clients et des commandes.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Créer le client</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>