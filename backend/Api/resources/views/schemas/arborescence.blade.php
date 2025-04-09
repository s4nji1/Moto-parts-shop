<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            {{ __('Arborescence des pièces') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">Structure hiérarchique des pièces</h3>
            <a href="{{ route('schemas.index') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Vue liste
            </a>
        </div>
        <div class="panel-body p-4">
            <div class="mb-6 bg-carbon-fiber p-4 rounded-lg">
                <h4 class="text-white font-bold mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    À propos de l'arborescence
                </h4>
                <p class="text-polished-chrome mb-3">Cette vue présente la structure hiérarchique des pièces. Les pièces racines sont présentées au niveau supérieur, et leurs sous-pièces sont regroupées en dessous. Cliquez sur les flèches pour développer ou réduire les sections.</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="flex items-center bg-deep-metal rounded p-2">
                        <div class="w-4 h-4 rounded-full bg-exhaust-blue mr-2"></div>
                        <span class="text-white text-sm">Pièce racine</span>
                    </div>
                    <div class="flex items-center bg-deep-metal rounded p-2">
                        <div class="w-4 h-4 rounded-full bg-engine-red mr-2"></div>
                        <span class="text-white text-sm">A des commandes</span>
                    </div>
                    <div class="flex items-center bg-deep-metal rounded p-2">
                        <div class="w-4 h-4 rounded-full bg-fuel-yellow mr-2"></div>
                        <span class="text-white text-sm">A des sous-pièces</span>
                    </div>
                    <div class="flex items-center bg-deep-metal rounded p-2">
                        <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-white text-sm">Associée à une moto</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($racines as $racine)
                    <div class="bg-deep-metal rounded-lg overflow-hidden">
                        <div class="flex items-center p-3 cursor-pointer hover:bg-carbon-fiber transition-colors" 
                             onclick="toggleNode('racine-{{ $racine->id }}')">
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg id="icon-racine-{{ $racine->id }}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center">
                                    <span class="text-white font-bold">{{ $racine->nom }}</span>
                                    <span class="text-polished-chrome/70 ml-2 text-sm">({{ $racine->version }})</span>
                                    <span class="text-green-500 ml-2 font-bold">{{ number_format($racine->price, 2) }} €</span>
                                </div>
                                @if($racine->moto)
                                <div class="text-xs text-polished-chrome">
                                    <span class="text-exhaust-blue">Moto:</span> 
                                    {{ $racine->moto->model->marque }} ({{ $racine->moto->model->annee }})
                                    @if($racine->moto->client)
                                        - {{ $racine->moto->client->firstname }} {{ $racine->moto->client->lastname }}
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if($racine->commandes->count() > 0)
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-engine-red mr-1"></div>
                                        <span class="text-polished-chrome text-xs">{{ $racine->commandes->count() }}</span>
                                    </div>
                                @endif
                                @if($racine->enfants->count() > 0)
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-fuel-yellow mr-1"></div>
                                        <span class="text-polished-chrome text-xs">{{ $racine->enfants->count() }}</span>
                                    </div>
                                @endif
                                @if($racine->moto)
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                                    </div>
                                @endif
                                <a href="{{ route('schemas.show', $racine->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @if($racine->enfants->count() > 0)
                            <div id="racine-{{ $racine->id }}" class="pl-8 pb-3 border-t border-gray-700 bg-carbon-fiber hidden">
                                @include('schemas.partials.arborescence_enfants', ['enfants' => $racine->enfants])
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-polished-chrome/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <span class="text-polished-chrome/70">Aucune pièce trouvée</span>
                            <a href="{{ route('schemas.create') }}" class="text-engine-red hover:text-white mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer une pièce
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleNode(id) {
            const node = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (node.classList.contains('hidden')) {
                node.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                node.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
    @endpush
</x-app-layout>