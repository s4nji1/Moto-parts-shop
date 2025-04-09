<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ClientsController extends Controller
{
    /**
     * Affiche la liste des clients
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Recherche par nom, prénom, email ou téléphone
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('cin', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par date d'inscription
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $clients = $query->paginate(10)->withQueryString();

        // Statistiques
        $totalClients = Client::count();
        $newClientsThisMonth = Client::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $activeClients = Client::whereHas('commandes')->count();

        return view('clients.index', compact(
            'clients', 
            'totalClients', 
            'newClientsThisMonth', 
            'activeClients'
        ));
    }

    /**
     * Affiche le formulaire de création d'un client
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistre un nouveau client
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'cin' => 'nullable|string|max:50|unique:clients',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('clients.create')
                ->withErrors($validator)
                ->withInput();
        }

        Client::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'cin' => $request->cin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Affiche les détails d'un client
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function show(Client $client)
    {
        // Récupérer les commandes du client
        $commandes = Commande::where('client_id', $client->id)
            ->with('schema')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques du client
        $stats = [
            'total_commandes' => $commandes->count(),
            'premiere_commande' => $commandes->last() ? $commandes->last()->created_at->format('d/m/Y') : 'Aucune commande',
            'derniere_commande' => $commandes->first() ? $commandes->first()->created_at->format('d/m/Y') : 'Aucune commande',
        ];

        return view('clients.show', compact('client', 'commandes', 'stats'));
    }

    /**
     * Affiche le formulaire d'édition d'un client
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour un client
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'cin' => ['nullable', 'string', 'max:50', Rule::unique('clients')->ignore($client->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('clients.edit', $client->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'cin' => $request->cin,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $client->update($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Supprime un client
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client)
    {
        // Vérifier si le client a des commandes
        if ($client->commandes()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il a des commandes associées.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }

    /**
     * Exporter la liste des clients au format CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=clients_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $clients = Client::orderBy('lastname')->get();
        $columns = array('ID', 'Prénom', 'Nom', 'CIN', 'Email', 'Téléphone', 'Adresse', 'Date d\'inscription');

        $callback = function() use ($clients, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->firstname,
                    $client->lastname,
                    $client->cin,
                    $client->email,
                    $client->phone,
                    $client->address,
                    $client->created_at->format('d/m/Y H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}