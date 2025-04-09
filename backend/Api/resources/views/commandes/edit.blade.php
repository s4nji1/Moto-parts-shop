<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ __('Modifier la commande') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Édition de la commande #{{ $commande->id }}</h3>
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

            <form action="{{ route('commandes.update', $commande->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="client_id" class="block text-polished-chrome text-sm font-medium mb-1">Client <span class="text-engine-red">*</span></label>
                            <select name="client_id" id="client_id" required class="form-input">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $commande->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->firstname }} {{ $client->lastname }} - {{ $client->email }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Sélectionnez le client qui passe la commande.</p>
                        </div>

                        <div class="mb-4">
                            <label for="schema_id" class="block text-polished-chrome text-sm font-medium mb-1">Pièce <span class="text-engine-red">*</span></label>
                            <select name="schema_id" id="schema_id" required class="form-input" onchange="updatePriceInfo()">
                                <option value="">Sélectionner une pièce</option>
                                @foreach($schemas as $schema)
                                    <option value="{{ $schema->id }}" data-price="{{ $schema->price }}" {{ old('schema_id', $commande->schema_id) == $schema->id ? 'selected' : '' }}>
                                        {{ $schema->nom }} ({{ $schema->version }}) - {{ number_format($schema->price, 2) }} €
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Sélectionnez la pièce de rechange commandée.</p>
                        </div>

                        <div class="mb-4">
                            <label for="quantite" class="block text-polished-chrome text-sm font-medium mb-1">Quantité <span class="text-engine-red">*</span></label>
                            <input type="number" name="quantite" id="quantite" value="{{ old('quantite', $commande->quantite) }}" min="1" required class="form-input" onchange="updatePriceInfo()" onkeyup="updatePriceInfo()">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez la quantité commandée.</p>
                        </div>

                        <div class="mb-4 bg-carbon-fiber p-4 rounded-lg">
                            <h5 class="text-white font-medium mb-2">Informations de prix</h5>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-polished-chrome">Prix unitaire :</span>
                                <span id="price-display" class="text-white font-medium">{{ number_format($commande->schema->price, 2) }} €</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-polished-chrome">Total estimé :</span>
                                <span id="total-display" class="text-green-500 font-bold">{{ number_format($commande->total, 2) }} €</span>
                            </div>
                            <p class="text-polished-chrome/70 text-xs mt-3">Le total sera automatiquement calculé en fonction du prix de la pièce et de la quantité.</p>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-polished-chrome text-sm font-medium mb-1">Statut <span class="text-engine-red">*</span></label>
                            <select name="status" id="status" required class="form-input">
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ old('status', $commande->status) == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Statut actuel de la commande.</p>
                        </div>
                    </div>

                    <div>
                        <div class="bg-carbon-fiber p-4 rounded-lg mb-4">
                            <h4 class="text-white font-bold mb-3">Informations sur cette commande</h4>
                            <div class="space-y-3 mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">ID de commande</span>
                                        <span class="text-white text-sm">#{{ $commande->id }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-polished-chrome/70 text-xs">Date de création</span>
                                        <span class="text-white text-sm">{{ $commande->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Dernière modification</span>
                                    <span class="text-white text-sm">{{ $commande->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Client actuel</span>
                                    <span class="text-white text-sm">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</span>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Pièce actuelle</span>
                                    <span class="text-white text-sm">{{ $commande->schema->nom }} ({{ $commande->schema->version }})</span>
                                </div>
                                <div>
                                    <span class="block text-polished-chrome/70 text-xs">Total actuel</span>
                                    <span class="text-green-500 font-bold">{{ number_format($commande->total, 2) }} €</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-deep-metal rounded-lg p-4 mb-4">
                            <h4 class="text-white font-bold mb-3">Guide des statuts</h4>
                            <div class="space-y-2">
                                <div class="flex items-center p-2 rounded bg-yellow-900/20 border border-yellow-900/30">
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                    <span class="text-polished-chrome text-sm">En attente - La commande vient d'être créée</span>
                                </div>
                                <div class="flex items-center p-2 rounded bg-blue-900/20 border border-blue-900/30">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                    <span class="text-polished-chrome text-sm">En cours - Préparation de la commande</span>
                                </div>
                                <div class="flex items-center p-2 rounded bg-indigo-900/20 border border-indigo-900/30">
                                    <div class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></div>
                                    <span class="text-polished-chrome text-sm">Confirmée - La commande a été envoyée</span>
                                </div>
                                <div class="flex items-center p-2 rounded bg-green-900/20 border border-green-900/30">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-polished-chrome text-sm">Livrée - Le client a reçu sa commande</span>
                                </div>
                                <div class="flex items-center p-2 rounded bg-red-900/20 border border-red-900/30">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                    <span class="text-polished-chrome text-sm">Annulée - La commande a été annulée</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-engine-red/10 border border-engine-red/20 rounded-md p-3">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-engine-red mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-engine-red font-bold text-sm">Note importante</h4>
                                    <p class="text-polished-chrome text-xs mt-1">La modification de la pièce ou de la quantité recalculera automatiquement le total. Le nouveau montant sera utilisé pour toutes les statistiques financières.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('commandes.show', $commande->id) }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function updatePriceInfo() {
            // Récupérer la pièce sélectionnée et son prix
            const schemaSelect = document.getElementById('schema_id');
            const selectedOption = schemaSelect.options[schemaSelect.selectedIndex];
            const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
            
            // Récupérer la quantité
            const quantite = document.getElementById('quantite').value || 0;
            
            // Mettre à jour l'affichage du prix unitaire
            const priceDisplay = document.getElementById('price-display');
            priceDisplay.textContent = parseFloat(price).toFixed(2) + ' €';
            
            // Calculer et mettre à jour l'affichage du total
            const total = parseFloat(price) * parseInt(quantite);
            const totalDisplay = document.getElementById('total-display');
            totalDisplay.textContent = total.toFixed(2) + ' €';
        }
        
        // Initialiser les informations de prix au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updatePriceInfo();
        });
    </script>
    @endpush
</x-app-layout>