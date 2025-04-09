<?php

namespace App\Http\Controllers;

use App\Models\MotoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelsController extends Controller
{
    /**
     * Affiche la liste des modèles de motos
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MotoModel::query();

        // Filtrage
        if ($request->filled('marque')) {
            $query->where('marque', 'LIKE', "%{$request->input('marque')}%");
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->input('annee'));
        }

        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $models = $query->withCount('motos')->paginate(10)->withQueryString();

        // Statistiques
        $totalModels = MotoModel::count();
        $yearOptions = MotoModel::select('annee')->distinct()->orderBy('annee')->pluck('annee');

        return view('models.index', compact('models', 'totalModels', 'yearOptions'));
    }

    /**
     * Affiche le formulaire de création d'un modèle
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('models.create');
    }

    /**
     * Enregistre un nouveau modèle de moto
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marque' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('models.create')
                ->withErrors($validator)
                ->withInput();
        }

        MotoModel::create([
            'marque' => $request->marque,
            'annee' => $request->annee,
        ]);

        return redirect()->route('models.index')
            ->with('success', 'Modèle de moto créé avec succès.');
    }

    /**
     * Affiche les détails d'un modèle
     *
     * @param  \App\Models\MotoModel  $model
     * @return \Illuminate\View\View
     */
    public function show(MotoModel $model)
    {
        $motos = $model->motos()->paginate(10);
        return view('models.show', compact('model', 'motos'));
    }

    /**
     * Affiche le formulaire d'édition d'un modèle
     *
     * @param  \App\Models\MotoModel  $model
     * @return \Illuminate\View\View
     */
    public function edit(MotoModel $model)
    {
        return view('models.edit', compact('model'));
    }

    /**
     * Met à jour un modèle de moto
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MotoModel  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MotoModel $model)
    {
        $validator = Validator::make($request->all(), [
            'marque' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('models.edit', $model->id)
                ->withErrors($validator)
                ->withInput();
        }

        $model->update([
            'marque' => $request->marque,
            'annee' => $request->annee,
        ]);

        return redirect()->route('models.index')
            ->with('success', 'Modèle de moto mis à jour avec succès.');
    }

    /**
     * Supprime un modèle de moto
     *
     * @param  \App\Models\MotoModel  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MotoModel $model)
    {
        // Vérifier s'il y a des motos associées à ce modèle
        if ($model->motos()->count() > 0) {
            return redirect()->route('models.index')
                ->with('error', 'Impossible de supprimer ce modèle car il a des motos associées.');
        }

        $model->delete();

        return redirect()->route('models.index')
            ->with('success', 'Modèle de moto supprimé avec succès.');
    }

    /**
     * Exporte les modèles de motos
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=models_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $models = MotoModel::withCount('motos')->get();
        $columns = ['ID', 'Marque', 'Année', 'Nombre de Motos'];

        $callback = function() use ($models, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($models as $model) {
                fputcsv($file, [
                    $model->id,
                    $model->marque,
                    $model->annee,
                    $model->motos_count
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Recherche dynamique des modèles de motos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        
        $models = MotoModel::where('marque', 'LIKE', "%{$term}%")
            ->orWhere('annee', 'LIKE', "%{$term}%")
            ->select('id', 'marque', 'annee')
            ->take(10)
            ->get();
            
        return response()->json($models);
    }
}