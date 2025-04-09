<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('Détails du modèle de moto') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header flex justify-between items-center">
            <h3 class="panel-title">{{ $model->marque }} {{ $model->annee }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('models.edit', $model->id) }}" class="moto-button py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                @if($model->motos->count() == 0)
                    <form action="{{ route('models.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-engine-red hover:bg-red-700 text-white py-2 px-4 rounded flex items-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2">
                    <div class="bg-carbon-fiber rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-3">Informations du modèle</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">ID</span>
                                    <span class="text-white font-medium">{{ $model->id }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Marque</span>
                                    <span class="text-white font-medium">{{ $model->marque }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Année</span>
                                    <span class="text-white font-medium">{{ $model->annee }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Date de création</span>
                                    <span class="text-white font-medium">{{ $model->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-polished-chrome/70 text-sm">Dernière modification</span>
                                    <span class="text-white font-medium">{{ $model->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($motos->count() > 0)
                        <div class="content-panel mb-6">
                            <div class="panel-header flex justify-between items-center">
                                <h4 class="panel-title text-base">Motos de ce modèle</h4>
                                <a href="{{ route('motos.index') }}?model_id={{ $model->id }}" class="text-exhaust-blue hover:text-white transition-colors text-sm">
                                    Voir toutes les motos
                                </a>
                            </div>
                            <div class="panel-body p-0">
                                <table class="moto-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">ID</th>
                                            <th class="text-center">Date d'ajout</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($motos as $moto)
                                            <tr>
                                                <td class="font-medium">#{{ $moto->id }}</td>
                                                <td class="text-center">{{ $moto->created_at->format('d/m/Y') }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('motos.show', $moto->id) }}" class="text-exhaust-blue hover:text-white transition-colors">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="bg-deep-metal rounded-lg p-4 mb-6">
                        <h4 class="text-white font-bold text-lg mb-4">Statistiques</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Motos total</span>
                                <span class="font-bold text-white">{{ $motos->total() }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded bg-carbon-fiber">
                                <span class="text-polished-chrome">Date première moto</span>
                                <span class="font-bold text-white">
                                    {{ $motos->count() > 0 ? $motos->min('created_at')->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-deep-metal rounded-lg p-4">
                        <h4 class="text-white font-bold text-lg mb-4">Actions rapides</h4>
                        <div class="space-y-3">
                            <a href="{{ route('models.edit', $model->id) }}" class="block w-full p-3 bg-exhaust-blue hover:bg-blue-700 text-white rounded-md text-center transition-colors">
                                Modifier ce modèle
                            </a>
                            <a href="{{ route('motos.create') }}?model_id={{ $model->id }}" class="block w-full p-3 bg-fuel-yellow hover:bg-yellow-600 text-white rounded-md text-center transition-colors">
                                Ajouter une moto
                            </a>
                            @if($model->motos->count() == 0)
                                <form action="{{ route('models.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full p-3 bg-engine-red hover:bg-red-700 text-white rounded-md text-center transition-colors">
                                        Supprimer ce modèle
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('models.index') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
</x-app-layout>