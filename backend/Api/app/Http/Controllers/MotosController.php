<?php

namespace App\Http\Controllers;

use App\Models\Moto;
use App\Models\MotoModel;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MotosController extends Controller
{
    /**
     * Affiche la liste des motos
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Moto::with('model', 'client');

        // Filtre par modèle
        if ($request->has('model_id')) {
            $query->where('model_id', $request->input('model_id'));
        }
        
        // Filtre par client
        if ($request->has('client_id')) {
            if ($request->input('client_id') === 'none') {
                $query->whereNull('client_id');
            } else if ($request->input('client_id')) {
                $query->where('client_id', $request->input('client_id'));
            }
        }

        // Filtre par marque (via la relation model)
        if ($request->has('marque')) {
            $marque = $request->input('marque');
            $query->whereHas('model', function($q) use ($marque) {
                $q->where('marque', 'LIKE', "%{$marque}%");
            });
        }

        // Filtre par année (via la relation model)
        if ($request->has('annee')) {
            $annee = $request->input('annee');
            $query->whereHas('model', function($q) use ($annee) {
                $q->where('annee', $annee);
            });
        }

        // Recherche générale
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('model', function($subq) use ($search) {
                    $subq->where('marque', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('client', function($subq) use ($search) {
                    $subq->where('firstname', 'LIKE', "%{$search}%")
                         ->orWhere('lastname', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }

        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        if ($sortField === 'marque' || $sortField === 'annee') {
            $query->join('models', 'motos.model_id', '=', 'models.id')
                  ->orderBy('models.'.$sortField, $sortDirection)
                  ->select('motos.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $motos = $query->paginate(10)->withQueryString();

        // Récupérer les modèles pour le filtre
        $models = MotoModel::orderBy('marque')->orderBy('annee', 'desc')->get();

        // Récupérer les clients pour le filtre
        $clients = Client::orderBy('lastname')->orderBy('firstname')->get();

        // Récupérer les marques distinctes pour le filtre
        $marques = MotoModel::select('marque')
            ->distinct()
            ->orderBy('marque')
            ->pluck('marque');

        // Récupérer les années distinctes pour le filtre
        $annees = MotoModel::select('annee')
            ->distinct()
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        // Statistiques
        $totalMotos = Moto::count();
        $motosByMarque = DB::table('motos')
            ->join('models', 'motos.model_id', '=', 'models.id')
            ->select('models.marque', DB::raw('count(*) as total'))
            ->groupBy('models.marque')
            ->orderBy('total', 'desc')
            ->get();

        return view('motos.index', compact(
            'motos',
            'models',
            'clients',
            'marques',
            'annees',
            'totalMotos',
            'motosByMarque'
        ));
    }

    /**
     * Affiche le formulaire de création d'une moto
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $models = MotoModel::orderBy('marque')->orderBy('annee', 'desc')->get();
        $clients = Client::orderBy('lastname')->orderBy('firstname')->get();
        return view('motos.create', compact('models', 'clients'));
    }

    /**
     * Enregistre une nouvelle moto
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model_id' => 'required|exists:models,id',
            'client_id' => 'nullable|exists:clients,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation pour l'image
        ]);

        if ($validator->fails()) {
            return redirect()->route('motos.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Traitement de l'image si elle est présente
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('motos', $imageName, 'public');
        }

        Moto::create([
            'model_id' => $request->model_id,
            'client_id' => $request->client_id,
            'image' => $imagePath, // Sauvegarder le chemin de l'image
        ]);

        return redirect()->route('motos.index')
            ->with('success', 'Moto créée avec succès.');
    }

    /**
     * Affiche les détails d'une moto
     *
     * @param  \App\Models\Moto  $moto
     * @return \Illuminate\View\View
     */
    public function show(Moto $moto)
    {
        $moto->load('model', 'client');
        
        // Récupérer les pièces compatibles avec ce modèle de moto
        // Cette fonctionnalité dépendra de votre structure de données
        // et des relations entre les modèles

        return view('motos.show', compact('moto'));
    }

    /**
     * Affiche le formulaire d'édition d'une moto
     *
     * @param  \App\Models\Moto  $moto
     * @return \Illuminate\View\View
     */
    public function edit(Moto $moto)
    {
        $models = MotoModel::orderBy('marque')->orderBy('annee', 'desc')->get();
        $clients = Client::orderBy('lastname')->orderBy('firstname')->get();
        return view('motos.edit', compact('moto', 'models', 'clients'));
    }

    /**
     * Met à jour une moto
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moto  $moto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Moto $moto)
    {
        $validator = Validator::make($request->all(), [
            'model_id' => 'required|exists:models,id',
            'client_id' => 'nullable|exists:clients,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation pour l'image
        ]);

        if ($validator->fails()) {
            return redirect()->route('motos.edit', $moto->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Traitement de l'image si elle est présente
        $imagePath = $moto->image; // Conserver l'image existante par défaut
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($moto->image && Storage::disk('public')->exists($moto->image)) {
                Storage::disk('public')->delete($moto->image);
            }
            
            // Stocker la nouvelle image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('motos', $imageName, 'public');
        }

        // Option pour supprimer l'image
        if ($request->has('remove_image') && $request->remove_image) {
            if ($moto->image && Storage::disk('public')->exists($moto->image)) {
                Storage::disk('public')->delete($moto->image);
            }
            $imagePath = null;
        }

        $moto->update([
            'model_id' => $request->model_id,
            'client_id' => $request->client_id,
            'image' => $imagePath,
        ]);

        return redirect()->route('motos.index')
            ->with('success', 'Moto mise à jour avec succès.');
    }

    /**
     * Supprime une moto
     *
     * @param  \App\Models\Moto  $moto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Moto $moto)
    {
        // Vérifier si la moto est liée à des commandes ou d'autres entités
        // Cela dépendra de votre structure de données

        // Supprimer l'image si elle existe
        if ($moto->image && Storage::disk('public')->exists($moto->image)) {
            Storage::disk('public')->delete($moto->image);
        }

        $moto->delete();

        return redirect()->route('motos.index')
            ->with('success', 'Moto supprimée avec succès.');
    }

    /**
     * Exporte la liste des motos au format CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=motos_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        // Construire la requête en fonction des filtres
        $query = Moto::with(['model', 'client']);

        // Appliquer les filtres si présents
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->input('model_id'));
        }

        if ($request->filled('marque')) {
            $marque = $request->input('marque');
            $query->whereHas('model', function($q) use ($marque) {
                $q->where('marque', 'LIKE', "%{$marque}%");
            });
        }

        if ($request->filled('annee')) {
            $annee = $request->input('annee');
            $query->whereHas('model', function($q) use ($annee) {
                $q->where('annee', $annee);
            });
        }

        if ($request->filled('client_id')) {
            if ($request->input('client_id') === 'none') {
                $query->whereNull('client_id');
            } else {
                $query->where('client_id', $request->input('client_id'));
            }
        }

        $motos = $query->get();

        $columns = array('ID', 'Marque', 'Modèle', 'Année', 'Client', 'Email Client', 'Téléphone Client', 'Image', 'Date d\'ajout');

        $callback = function() use ($motos, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($motos as $moto) {
                fputcsv($file, [
                    $moto->id,
                    $moto->model->marque,
                    $moto->model->nom ?? 'N/A', // Si vous avez un champ 'nom' dans votre modèle
                    $moto->model->annee,
                    $moto->client ? $moto->client->firstname . ' ' . $moto->client->lastname : 'N/A',
                    $moto->client ? $moto->client->email : 'N/A',
                    $moto->client ? $moto->client->phone : 'N/A',
                    $moto->image ? asset('storage/' . $moto->image) : 'Pas d\'image',
                    $moto->created_at->format('d/m/Y H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}