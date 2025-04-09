@foreach($enfants as $enfant)
    <div class="relative mt-3">
        <div class="absolute left-0 top-0 h-full w-px bg-gray-700 -ml-4"></div>
        <div class="bg-deep-metal rounded-lg overflow-hidden">
            <div class="flex items-center p-3 cursor-pointer hover:bg-carbon-fiber/50 transition-colors relative" 
                 onclick="toggleNode('enfant-{{ $enfant->id }}')">
                <div class="absolute left-0 w-3 h-px bg-gray-700 -ml-4"></div>
                <div class="w-5 h-5 mr-3 flex items-center justify-center">
                    @if($enfant->enfants->count() > 0)
                        <svg id="icon-enfant-{{ $enfant->id }}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @else
                        <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                    @endif
                </div>
                <div class="flex-grow">
                    <div class="flex items-center">
                        <span class="text-white font-bold">{{ $enfant->nom }}</span>
                        <span class="text-polished-chrome/70 ml-2 text-sm">({{ $enfant->version }})</span>
                        <span class="text-green-500 ml-2 font-bold">{{ number_format($enfant->price, 2) }} €</span>
                    </div>
                    @if($enfant->moto)
                    <div class="text-xs text-polished-chrome">
                        <span class="text-exhaust-blue">Moto:</span> 
                        {{ $enfant->moto->model->marque }} ({{ $enfant->moto->model->annee }})
                        @if($enfant->moto->client)
                            - {{ $enfant->moto->client->firstname }} {{ $enfant->moto->client->lastname }}
                        @endif
                    </div>
                    @endif
                </div>
                <div class="flex space-x-2">
                    @if($enfant->commandes->count() > 0)
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-engine-red mr-1"></div>
                            <span class="text-polished-chrome text-xs">{{ $enfant->commandes->count() }}</span>
                        </div>
                    @endif
                    @if($enfant->enfants->count() > 0)
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-fuel-yellow mr-1"></div>
                            <span class="text-polished-chrome text-xs">{{ $enfant->enfants->count() }}</span>
                        </div>
                    @endif
                    @if($enfant->moto)
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                        </div>
                    @endif
                    <a href="{{ route('schemas.show', $enfant->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                </div>
            </div>
            @if($enfant->enfants->count() > 0)
                <div id="enfant-{{ $enfant->id }}" class="pl-8 pb-3 border-t border-gray-700 bg-carbon-fiber/50 hidden">
                    @include('schemas.partials.arborescence_enfants', ['enfants' => $enfant->enfants])
                </div>
            @endif
        </div>
    </div>
@endforeach