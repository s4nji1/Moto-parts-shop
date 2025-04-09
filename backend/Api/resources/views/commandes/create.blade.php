<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Créer une nouvelle commande') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Informations de la commande</h3>
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

            <form action="{{ route('commandes.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="client_id" class="block text-polished-chrome text-sm font-medium mb-1">Client <span class="text-engine-red">*</span></label>
                            <select name="client_id" id="client_id" required class="form-input">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>
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
                                    <option value="{{ $schema->id }}" data-price="{{ $schema->price }}" {{ old('schema_id', request('schema_id')) == $schema->id ? 'selected' : '' }}>
                                        {{ $schema->nom }} ({{ $schema->version }}) - {{ number_format($schema->price, 2) }} €
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Sélectionnez la pièce de rechange commandée.</p>
                        </div>

                        <div class="mb-4">
                            <label for="quantite" class="block text-polished-chrome text-sm font-medium mb-1">Quantité <span class="text-engine-red">*</span></label>
                            <input type="number" name="quantite" id="quantite" value="{{ old('quantite', 1) }}" min="1" required class="form-input" onchange="updatePriceInfo()" onkeyup="updatePriceInfo()">
                            <p class="text-polished-chrome/70 text-xs mt-1">Indiquez la quantité commandée.</p>
                        </div>

                        <div class="mb-4 bg-carbon-fiber p-4 rounded-lg">
                            <h5 class="text-white font-medium mb-2">Informations de prix</h5>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-polished-chrome">Prix unitaire :</span>
                                <span id="price-display" class="text-white font-medium">0.00 €</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-polished-chrome">Total estimé :</span>
                                <span id="total-display" class="text-green-500 font-bold">0.00 €</span>
                            </div>
                            <p class="text-polished-chrome/70 text-xs mt-3">Le total sera automatiquement calculé en fonction du prix de la pièce et de la quantité.</p>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-polished-chrome text-sm font-medium mb-1">Statut <span class="text-engine-red">*</span></label>
                            <select name="status" id="status" required class="form-input">
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : ($key == 'en_attente' ? 'selected' : '') }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Statut initial de la commande.</p>
                        </div>
                    </div>

                    <div class="bg-carbon-fiber p-4 rounded-lg">
                        <h4 class="text-white font-bold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informations sur la commande
                        </h4>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Assurez-vous de sélectionner le <strong class="text-white">bon client</strong> et la <strong class="text-white">bonne pièce</strong> pour la commande.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">La <strong class="text-white">quantité</strong> doit être au minimum de 1.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Le <strong class="text-white">total</strong> est calculé automatiquement (prix unitaire × quantité).</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Le <strong class="text-white">statut</strong> initial est généralement "En attente" mais peut être modifié selon le processus interne.</span>
                            </li>
                        </ul>

                        <div class="bg-deep-metal rounded p-3 border border-gray-700">
                            <h5 class="font-bold text-white mb-2">Processus de commande</h5>
                            <p class="text-polished-chrome text-sm mb-2">Étapes typiques d'une commande :</p>
                            <ol class="list-decimal list-inside text-polished-chrome text-xs space-y-1 pl-2">
                                <li>Création de la commande (<span class="text-yellow-500">En attente</span>)</li>
                                <li>Vérification et préparation (<span class="text-blue-500">En Cours</span>)</li>
                                <li>Envoi au client (<span class="text-indigo-500">Confirmée</span>)</li>
                                <li>Réception confirmée (<span class="text-green-500">Livrée</span>)</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('commandes.index') }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Créer la commande</button>
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