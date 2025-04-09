<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Moto;
use App\Models\ModelMoto;
use App\Models\MotoModel;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MotoController extends Controller
{
    /**
     * Récupère toutes les motos du client connecté
     */
    public function getClientMotos()
    {
        $client = Auth::user();
        
        $motos = Moto::with('model')
            ->where('client_id', $client->id)
            ->get();
            
        return response()->json([
            'success' => true,
            'motos' => $motos
        ]);
    }
    
    /**
     * Récupère tous les modèles disponibles
     */
    public function getAllModels()
    {
        $models = MotoModel::all();
        
        return response()->json([
            'success' => true,
            'models' => $models
        ]);
    }
    
    /**
     * Ajoute une nouvelle moto pour le client
     */
    public function addMoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model_id' => 'required|exists:models,id',
            'image' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $client = Auth::user();
        
        $moto = new Moto();
        $moto->model_id = $request->model_id;
        $moto->client_id = $client->id;
        $moto->image = $request->image;
        $moto->save();
        
        // Charger le modèle lié à la moto
        $moto->load('model');
        
        return response()->json([
            'success' => true,
            'message' => 'Moto ajoutée avec succès',
            'moto' => $moto
        ], 201);
    }
    
    /**
     * Supprime une moto du client
     */
    public function deleteMoto($id)
    {
        $client = Auth::user();
        
        $moto = Moto::where('id', $id)
            ->where('client_id', $client->id)
            ->first();
            
        if (!$moto) {
            return response()->json([
                'success' => false,
                'message' => 'Moto non trouvée ou non autorisée'
            ], 404);
        }
        
        $moto->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Moto supprimée avec succès'
        ]);
    }
    
    /**
     * Récupère les pièces compatibles avec une moto
     */
    /**
 * Récupère les schémas (pièces) compatibles avec une moto
 */
public function getCompatibleSchemas($id)
{
    try {
        $moto = Moto::with('schemas')->find($id);
        
        if (!$moto) {
            return response()->json([
                'success' => false,
                'message' => 'Moto non trouvée'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'schemas' => $moto->schemas
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function getAllMotos()
{
    try {
        $motos = Moto::with('model')->get();
        
        $formattedMotos = $motos->map(function($moto) {
            return [
                'id' => $moto->id,
                'model_id' => $moto->model_id,
                'client_id' => $moto->client_id,
                'image' => $moto->image,
                'created_at' => $moto->created_at,
                'updated_at' => $moto->updated_at,
                'model' => $moto->model ? [
                    'id' => $moto->model->id,
                    'marque' => $moto->model->marque,
                    'annee' => $moto->model->annee
                ] : null
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedMotos
        ]);
    } catch (\Exception $e) {
        // Log l'erreur pour le débogage
        \Log::error('Erreur lors de la récupération des motos: ' . $e->getMessage());
        
        // Renvoyer une réponse d'erreur
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération des motos',
            'error' => $e->getMessage()
        ],500);
    }
}
}