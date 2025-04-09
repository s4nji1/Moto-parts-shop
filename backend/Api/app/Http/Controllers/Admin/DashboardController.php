<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Schema;
use App\Models\Moto; // Ajouté
use App\Models\MotoModel; // Ajouté
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Période actuelle et précédente pour les comparaisons
        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // Nombre total de commandes
        $totalOrders = Commande::count();
        
        // Commandes en attente
        $pendingOrders = Commande::whereIn('status', ['en_attente', 'en_traitement'])->count();
        
        // Commandes récentes
        $recentOrders = Commande::with('client', 'schema')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistiques des clients
        $totalCustomers = Client::count();
        $newCustomers = Client::where('created_at', '>=', $currentMonthStart)->count();
        
        // Récents clients inscrits
        $recentCustomers = Client::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistiques des schémas (pièces détachées)
        $totalProducts = Schema::count();
        $lowStockProducts = 0; // Le modèle Schema n'a pas de champ quantité_stock
        $lowStockProductsList = []; // Liste vide car pas de gestion de stock
        
        // AJOUT: Statistiques pour les motos
        $totalMotos = Moto::count();
        $newMotos = Moto::where('created_at', '>=', $currentMonthStart)->count();

        // Récupérer les motos récentes
$recentMotos = Moto::with(['model', 'client'])
->orderBy('created_at', 'desc')
->take(5)
->get();

// Récupérer les modèles populaires (avec le nombre de motos par modèle)
$popularModels = MotoModel::withCount('motos')
->orderBy('motos_count', 'desc')
->take(5)
->get();
        
        // AJOUT: Statistiques pour les modèles
        $totalModels = MotoModel::count();
        $topBrandQuery = MotoModel::select('marque', DB::raw('count(*) as total'))
            ->groupBy('marque')
            ->orderBy('total', 'desc')
            ->first();
        $topBrand = $topBrandQuery ? $topBrandQuery->marque : 'N/A';
        
        // AJOUT: Calcul du prix moyen des pièces
        $avgPrice = Schema::avg('price') ?: 0;
        $avgPrice = number_format($avgPrice, 2);

        // Données pour le graphique des ventes mensuelles (6 derniers mois)
        $monthlySalesData = [];
        $monthlySalesLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $monthlySalesLabels[] = $monthLabel;
            
            // Compter le nombre de commandes par mois plutôt que les montants
            $monthlySalesData[] = Commande::whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])->count();
        }

        // Données pour le top des schémas les plus commandés
        $topSchemas = DB::table('commandes')
            ->join('schemas', 'commandes.schema_id', '=', 'schemas.id')
            ->select('schemas.id', 'schemas.nom', DB::raw('COUNT(commandes.id) as total_commandes'))
            ->groupBy('schemas.id', 'schemas.nom')
            ->orderBy('total_commandes', 'desc')
            ->take(5)
            ->get();
            
        $topCategoriesLabels = $topSchemas->pluck('nom')->toArray();
        $topCategoriesData = $topSchemas->pluck('total_commandes')->toArray();

        // Calcul de statistiques supplémentaires
        $totalSales = $totalOrders; // Utiliser le nombre de commandes comme indicateur
        $currentMonthSales = Commande::where('created_at', '>=', $currentMonthStart)->count();
        $lastMonthSales = Commande::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        
        // Calcul de croissance
        $salesGrowth = $lastMonthSales > 0 
            ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 1) 
            : 0;

        return view('admin.dashboard', compact(
            'totalSales',
            'salesGrowth',
            'totalCustomers',
            'newCustomers',
            'totalOrders',
            'pendingOrders',
            'recentOrders',
            'totalProducts',
            'lowStockProducts',
            'lowStockProductsList',
            'monthlySalesData',
            'monthlySalesLabels',
            'topCategoriesLabels',
            'topCategoriesData',
            'recentCustomers',
            'totalMotos',
            'newMotos',
            'totalModels',
            'topBrand',
            'avgPrice',
            'recentMotos',
            'popularModels'
        ));
    }
}