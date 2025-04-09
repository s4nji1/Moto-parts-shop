<?php

namespace App\Http\Controllers;

use App\Models\Schema;
use App\Models\Moto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchemaController extends Controller
{
    /**
     * Affiche la liste des pièces détachées
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Schema::query();

        // Recherche par nom si spécifié
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nom', 'LIKE', "%{$search}%");
        }

        // Filtre par version si spécifié
        if ($request->has('version')) {
            $query->where('version', $request->input('version'));
        }

        // Filtre par parent_id si spécifié
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->input('parent_id'));
        }

        // Filtre par moto_id si spécifié
        if ($request->has('moto_id')) {
            $query->where('moto_id', $request->input('moto_id'));
        }

        // Filtre par plage de prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Récupération des pièces avec pagination
        $schemas = $query->with('parent', 'enfants', 'moto')
            ->orderBy('nom')
            ->paginate(15);

        // Récupération des versions distinctes pour les filtres
        $versions = Schema::select('version')
            ->distinct()
            ->orderBy('version')
            ->pluck('version');

        // Récupération des pièces parentes pour les filtres
        $parents = Schema::whereNull('parent_id')
            ->orderBy('nom')
            ->get();

        // Récupération des motos pour les filtres
        $motos = Moto::with('model')->get();

        // Stats pour le tableau de bord
        $totalSchemas = Schema::count();
        $topSchemas = DB::table('commandes')
            ->join('schemas', 'commandes.schema_id', '=', 'schemas.id')
            ->select('schemas.id', 'schemas.nom', DB::raw('COUNT(commandes.id) as total_commandes'))
            ->groupBy('schemas.id', 'schemas.nom')
            ->orderBy('total_commandes', 'desc')
            ->take(5)
            ->get();

        return view('schemas.index', compact('schemas', 'versions', 'parents', 'motos', 'totalSchemas', 'topSchemas'));
    }

    /**
     * Affiche le formulaire de création d'une pièce
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Récupération des pièces qui peuvent être parentes
        $parentSchemas = Schema::orderBy('nom')->get();
        
        // Récupération des motos pour l'association
        $motos = Moto::with('model')->get();
        
        return view('schemas.create', compact('parentSchemas', 'motos'));
    }

    /**
     * Enregistre une nouvelle pièce
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:schemas,id',
            'price' => 'required|numeric|min:0',
            'moto_id' => 'nullable|exists:motos,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'serial_number' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('schemas.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'nom' => $request->nom,
            'version' => $request->version,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'moto_id' => $request->moto_id,
            'serial_number' => $request->serial_number,
        ];
        
        // Traitement de l'image si elle est présente
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('schemas', $imageName, 'public');
            $data['image'] = $imagePath;
        }
        
        Schema::create($data);

        return redirect()->route('schemas.index')
            ->with('success', 'Pièce créée avec succès.');
    }

    /**
     * Affiche les détails d'une pièce
     *
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\View\View
     */
    public function show(Schema $schema)
    {
        // Chargement des relations
        $schema->load('parent', 'enfants', 'commandes.client', 'moto.model');
        
        // Statistiques des commandes pour cette pièce
        $totalCommandes = $schema->commandes->count();
        $commandesRecentes = $schema->commandes()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('schemas.show', compact('schema', 'totalCommandes', 'commandesRecentes'));
    }

    /**
     * Affiche le formulaire d'édition d'une pièce
     *
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\View\View
     */
    public function edit(Schema $schema)
    {
        // Récupération des pièces qui peuvent être parentes (sauf la pièce elle-même et ses enfants)
        $parentSchemas = Schema::where('id', '!=', $schema->id)
            ->whereNotIn('parent_id', [$schema->id])
            ->orderBy('nom')
            ->get();
            
        // Récupération des motos pour l'association
        $motos = Moto::with('model')->get();
            
        return view('schemas.edit', compact('schema', 'parentSchemas', 'motos'));
    }

    /**
     * Met à jour une pièce
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Schema $schema)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:schemas,id',
            'price' => 'required|numeric|min:0',
            'moto_id' => 'nullable|exists:motos,id'
        ]);

        if ($validator->fails()) {
            return redirect()->route('schemas.edit', $schema->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification que la pièce ne se référence pas elle-même comme parent
        if ($request->parent_id == $schema->id) {
            return redirect()->route('schemas.edit', $schema->id)
                ->with('error', 'Une pièce ne peut pas être son propre parent.')
                ->withInput();
        }

        // Vérification que la pièce n'est pas le parent d'un de ses parents (boucle infinie)
        $currentParent = $request->parent_id;
        $visited = [$schema->id];
        
        while ($currentParent) {
            if (in_array($currentParent, $visited)) {
                return redirect()->route('schemas.edit', $schema->id)
                    ->with('error', 'Cette relation parent/enfant créerait une boucle infinie.')
                    ->withInput();
            }
            
            $visited[] = $currentParent;
            $parent = Schema::find($currentParent);
            $currentParent = $parent ? $parent->parent_id : null;
        }

        // Mise à jour de la pièce
        $schema->update([
            'nom' => $request->nom,
            'version' => $request->version,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'moto_id' => $request->moto_id
        ]);

        // Mise à jour du total des commandes associées si le prix a changé
        if ($schema->wasChanged('price')) {
            foreach ($schema->commandes as $commande) {
                $commande->total = $commande->quantite * $schema->price;
                $commande->save();
            }
        }

        return redirect()->route('schemas.index')
            ->with('success', 'Pièce mise à jour avec succès.');
    }

    /**
     * Supprime une pièce
     *
     * @param  \App\Models\Schema  $schema
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Schema $schema)
    {
        // Vérification si la pièce a des enfants
        if ($schema->enfants()->count() > 0) {
            return redirect()->route('schemas.index')
                ->with('error', 'Impossible de supprimer cette pièce car elle a des pièces enfants.');
        }

        // Vérification si la pièce est utilisée dans des commandes
        if ($schema->commandes()->count() > 0) {
            return redirect()->route('schemas.index')
                ->with('error', 'Impossible de supprimer cette pièce car elle est associée à des commandes.');
        }

        // Suppression de la pièce
        $schema->delete();

        return redirect()->route('schemas.index')
            ->with('success', 'Pièce supprimée avec succès.');
    }

    /**
     * Affiche l'arborescence des pièces
     *
     * @return \Illuminate\View\View
     */
    public function arborescence()
    {
        // Récupération des pièces racines (sans parent)
        $racines = Schema::whereNull('parent_id')
            ->with('enfants', 'moto')
            ->orderBy('nom')
            ->get();
            
        return view('schemas.arborescence', compact('racines'));
    }

    /**
     * Recherche dynamique des pièces (pour AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        
        $schemas = Schema::where('nom', 'LIKE', "%{$term}%")
            ->orWhere('version', 'LIKE', "%{$term}%")
            ->select('id', 'nom', 'version', 'price')
            ->take(10)
            ->get();
            
        return response()->json($schemas);
    }
}