<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ __('Modifier le client') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Édition de {{ $client->firstname }} {{ $client->lastname }}</h3>
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

            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="firstname" class="block text-polished-chrome text-sm font-medium mb-1">Prénom <span class="text-engine-red">*</span></label>
                                <input type="text" name="firstname" id="firstname" value="{{ old('firstname', $client->firstname) }}" required class="form-input" placeholder="Prénom">
                            </div>
                            <div class="mb-4">
                                <label for="lastname" class="block text-polished-chrome text-sm font-medium mb-1">Nom <span class="text-engine-red">*</span></label>
                                <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $client->lastname) }}" required class="form-input" placeholder="Nom">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-polished-chrome text-sm font-medium mb-1">Email <span class="text-engine-red">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" required class="form-input" placeholder="exemple@email.com">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-polished-chrome text-sm font-medium mb-1">Téléphone <span class="text-engine-red">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" required class="form-input" placeholder="0xxxxxxxxx">
                        </div>

                        <div class="mb-4">
                            <label for="cin" class="block text-polished-chrome text-sm font-medium mb-1">CIN</label>
                            <input type="text" name="cin" id="cin" value="{{ old('cin', $client->cin) }}" class="form-input" placeholder="XX123456">
                            <p class="text-polished-chrome/70 text-xs mt-1">Numéro de carte d'identité nationale.</p>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-polished-chrome text-sm font-medium mb-1">Adresse</label>
                            <textarea name="address" id="address" rows="3" class="form-input" placeholder="Adresse complète">{{ old('address', $client->address) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="password" class="block text-polished-chrome text-sm font-medium mb-1">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" class="form-input" placeholder="••••••••">
                            <p class="text-polished-chrome/70 text-xs mt-1">Laissez vide pour conserver le mot de passe actuel. Minimum 8 caractères si modifié.</p>
                        </div>

                        <div class="bg-carbon-fiber p-4 rounded-lg">
                            <h4 class="text-white font-bold mb-3">Informations sur ce client</h4>
                            <div class="space-y-3 mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">ID</span>
                                        <span class="text-white text-sm">{{ $client->id }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">Date d'inscription</span>
                                        <span class="text-white text-sm">{{ $client->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Dernière mise à jour</span>
                                    <span class="text-white text-sm">{{ $client->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($client->commandes->count() > 0)
                            <div class="bg-carbon-fiber p-4 rounded-lg mt-4">
                                <h4 class="text-white font-bold mb-3">Activité du client</h4>
                                <div class="flex justify-between items-center p-2 rounded bg-deep-metal">
                                    <span class="text-polished-chrome">Commandes effectuées</span>
                                    <span class="font-bold text-white">{{ $client->commandes->count() }}</span>
                                </div>
                                <p class="text-polished-chrome/70 text-sm mt-3">Ce client a des commandes actives dans le système. Certaines modifications peuvent impacter son expérience sur l'application mobile.</p>
                            </div>
                        @endif

                        <div class="bg-engine-red/10 border border-engine-red/20 rounded-md p-3 mt-4">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-engine-red font-bold text-sm">Attention</h4>
                                    <p class="text-polished-chrome text-xs mt-1">La modification de l'adresse email peut affecter la connexion du client à l'application mobile.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('clients.show', $client->id) }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>