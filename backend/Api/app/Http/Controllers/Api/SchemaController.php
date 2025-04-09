<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schema;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Récupère tous les schémas (produits)
     */
    public function getAllSchemas()
    {
        $schemas = Schema::all();
        
        return response()->json([
            'success' => true,
            'schemas' => $schemas
        ]);
    }
    
    /**
     * Récupère un schéma spécifique
     */
    public function getSchema($id)
    {
        $schema = Schema::find($id);
        
        if (!$schema) {
            return response()->json([
                'success' => false,
                'message' => 'Schéma non trouvé'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'schema' => $schema
        ]);
    }
    
    /**
     * Recherche des schémas
     */
    public function searchSchemas(Request $request)
    {
        $query = $request->input('query', '');
        $category = $request->input('category');
        $sortBy = $request->input('sort_by', 'relevance');
        
        $schemasQuery = Schema::query();
        
        // Recherche par nom ou version
        if (!empty($query)) {
            $schemasQuery->where(function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('version', 'like', "%{$query}%");
            });
        }
        
        // Filtrer par catégorie (vous devrez adapter selon votre modèle)
        // Par exemple, si vous avez un champ catégorie dans le schéma
        if (!empty($category) && $category !== 'Tous') {
            $schemasQuery->where('category', $category);
        }
        
        // Tri
        switch ($sortBy) {
            case 'price_asc':
                $schemasQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $schemasQuery->orderBy('price', 'desc');
                break;
            default:
                // Par défaut, tri par pertinence ou autre champ
                $schemasQuery->orderBy('id', 'desc');
                break;
        }
        
        $schemas = $schemasQuery->get();
        
        return response()->json([
            'success' => true,
            'schemas' => $schemas
        ]);
    }
    
    /**
     * Récupère les catégories de schémas (si applicable)
     */
    public function getCategories()
    {
        // Adaptez cette méthode selon votre structure
        // Par exemple, si vous avez un champ catégorie dans le schéma
        $categories = Schema::select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
            
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}