<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Ajouter une nouvelle moto') }}
        </h2>
    </x-slot>

    <div class="content-panel">
        <div class="panel-header">
            <h3 class="panel-title">Informations de la moto</h3>
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

            <form action="{{ route('motos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="model_id" class="block text-polished-chrome text-sm font-medium mb-1">Modèle de moto <span class="text-engine-red">*</span></label>
                            <select name="model_id" id="model_id" required class="form-input">
                                <option value="">Sélectionner un modèle</option>
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}" {{ old('model_id') == $model->id ? 'selected' : '' }}>
                                        {{ $model->marque }} ({{ $model->annee }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-polished-chrome/70 text-xs mt-1">Sélectionnez le modèle de la moto.</p>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="block text-polished-chrome text-sm font-medium mb-1">Image de la moto</label>
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <input type="file" name="image" id="image" class="form-input" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg">
                                </div>
                                <div class="w-16 h-16 flex items-center justify-center bg-carbon-fiber rounded">
                                    <img id="image-preview" src="#" alt="Aperçu" class="max-h-full max-w-full rounded hidden">
                                    <svg id="image-placeholder" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-polished-chrome/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-polished-chrome/70 text-xs mt-1">Format acceptés: JPG, PNG, GIF, SVG (max 2MB)</p>
                        </div>
                        
                        <div class="mt-6">
                            <p class="text-polished-chrome text-sm">Les champs marqués d'un <span class="text-engine-red">*</span> sont obligatoires.</p>
                        </div>
                    </div>

                    <div class="bg-carbon-fiber p-4 rounded-lg">
                        <h4 class="text-white font-bold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informations sur l'ajout de motos
                        </h4>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Vous devez d'abord sélectionner un <strong class="text-white">modèle de moto</strong> existant.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Si le modèle souhaité n'existe pas encore, vous devez d'abord <strong class="text-white">créer un nouveau modèle</strong> dans la section des modèles.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Une moto ajoutée pourra ensuite être <strong class="text-white">associée à des pièces détachées</strong> spécifiques.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Vous pourrez <strong class="text-white">associer cette moto à un client</strong> après sa création depuis la page de détails ou la liste des motos.</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-exhaust-blue mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-polished-chrome text-sm">Une <strong class="text-white">image</strong> de la moto permet une meilleure identification visuelle pour vous et vos clients.</span>
                            </li>
                        </ul>

                        <div class="bg-deep-metal rounded p-3 border border-gray-700">
                            <h5 class="font-bold text-white mb-2">Avez-vous besoin d'ajouter un nouveau modèle ?</h5>
                            <p class="text-polished-chrome text-sm mb-3">Si le modèle de moto que vous souhaitez ajouter n'existe pas dans la liste, créez d'abord un nouveau modèle.</p>
                            <a href="{{ route('models.create') }}" class="text-exhaust-blue hover:text-white transition-colors flex items-center w-fit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer un nouveau modèle
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('motos.index') }}" class="px-4 py-2 bg-carbon-fiber hover:bg-gray-700 text-polished-chrome rounded transition-colors">Annuler</a>
                    <button type="submit" class="moto-button">Créer la moto</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');
            
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        imagePlaceholder.classList.add('hidden');
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.src = '#';
                    imagePreview.classList.add('hidden');
                    imagePlaceholder.classList.remove('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>