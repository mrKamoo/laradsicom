<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Prescripteur;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::with(['prescripteur.commune', 'typeCommande', 'gestionnaire']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_commande')) {
            $query->where('type_commande_id', $request->type_commande);
        }

        if ($request->filled('urgentes')) {
            $query->where('urgent', true);
        }

        if ($request->filled('commune')) {
            $query->whereHas('prescripteur', function ($q) use ($request) {
                $q->where('commune_id', $request->commune);
            });
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero_commande', 'LIKE', "%{$search}%")
                  ->orWhere('objet', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('prescripteur', function ($sq) use ($search) {
                      $sq->where('nom', 'LIKE', "%{$search}%")
                         ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(15);

        // Données pour les filtres
        $typesCommandes = TypeCommande::actifs()->get();
        $statuts = [
            'demande' => 'Demande reçue',
            'devis' => 'En attente de devis',
            'validation' => 'En attente de validation',
            'commande' => 'Bon de commande émis',
            'livraison' => 'En cours de livraison',
            'cloture' => 'Clôturée'
        ];

        return view('commandes.index', compact('commandes', 'typesCommandes', 'statuts'));
    }

    public function create()
    {
        $prescripteurs = Prescripteur::with('commune')->actifs()->get();
        $typesCommandes = TypeCommande::actifs()->get();

        return view('commandes.create', compact('prescripteurs', 'typesCommandes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prescripteur_id' => 'required|exists:prescripteurs,id',
            'type_commande_id' => 'required|exists:types_commandes,id',
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
            'justification' => 'nullable|string',
            'date_demande' => 'required|date',
            'date_souhaitee' => 'nullable|date|after_or_equal:date_demande',
            'urgent' => 'boolean',
            'montant_estime' => 'nullable|numeric|min:0',
            'imputation_budgetaire' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['urgent'] = $request->has('urgent');

        $commande = Commande::create($validated);

        return redirect()->route('commandes.show', $commande)
                        ->with('success', 'Commande créée avec succès.');
    }

    public function show(Commande $commande)
    {
        $commande->load([
            'prescripteur.commune',
            'typeCommande',
            'gestionnaire',
            'devis.fournisseur'
        ]);

        return view('commandes.show', compact('commande'));
    }

    public function edit(Commande $commande)
    {
        // Seules certaines commandes peuvent être modifiées
        if (!in_array($commande->statut, ['demande', 'devis'])) {
            return redirect()->route('commandes.show', $commande)
                           ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $prescripteurs = Prescripteur::with('commune')->actifs()->get();
        $typesCommandes = TypeCommande::actifs()->get();

        return view('commandes.edit', compact('commande', 'prescripteurs', 'typesCommandes'));
    }

    public function update(Request $request, Commande $commande)
    {
        // Vérification des droits de modification
        if (!in_array($commande->statut, ['demande', 'devis'])) {
            return redirect()->route('commandes.show', $commande)
                           ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'prescripteur_id' => 'required|exists:prescripteurs,id',
            'type_commande_id' => 'required|exists:types_commandes,id',
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
            'justification' => 'nullable|string',
            'date_demande' => 'required|date',
            'date_souhaitee' => 'nullable|date|after_or_equal:date_demande',
            'urgent' => 'boolean',
            'montant_estime' => 'nullable|numeric|min:0',
            'imputation_budgetaire' => 'nullable|string|max:255',
        ]);

        $validated['urgent'] = $request->has('urgent');

        $commande->update($validated);

        return redirect()->route('commandes.show', $commande)
                        ->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande)
    {
        // Seules les commandes en statut "demande" peuvent être supprimées
        if ($commande->statut !== 'demande') {
            return redirect()->route('commandes.index')
                           ->with('error', 'Seules les commandes en statut "Demande" peuvent être supprimées.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')
                        ->with('success', 'Commande supprimée avec succès.');
    }

    public function updateStatus(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'statut' => 'required|in:demande,devis,validation,commande,livraison,cloture',
            'notes' => 'nullable|string'
        ]);

        if ($commande->changerStatut($validated['statut'], $request->input('notes'))) {
            return redirect()->route('commandes.show', $commande)
                           ->with('success', 'Statut mis à jour avec succès.');
        }

        return redirect()->route('commandes.show', $commande)
                        ->with('error', 'Impossible de changer le statut. Transition non autorisée.');
    }

    public function changerStatut(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'nouveau_statut' => 'required|in:demande,devis,validation,commande,livraison,cloture',
            'notes' => 'nullable|string'
        ]);

        if ($commande->changerStatut($validated['nouveau_statut'], $validated['notes'])) {
            return redirect()->route('commandes.show', $commande)
                           ->with('success', 'Statut mis à jour avec succès.');
        }

        return redirect()->route('commandes.show', $commande)
                        ->with('error', 'Impossible de changer le statut. Transition non autorisée.');
    }

    public function dashboard()
    {
        $stats = [
            'total' => Commande::count(),
            'en_cours' => Commande::enCours()->count(),
            'urgentes' => Commande::urgentes()->enCours()->count(),
            'cloturees' => Commande::parStatut('cloture')->count(),
        ];

        $commandesRecentes = Commande::with(['prescripteur.commune', 'typeCommande'])
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();

        $commandesUrgentes = Commande::with(['prescripteur.commune', 'typeCommande'])
                                   ->urgentes()
                                   ->enCours()
                                   ->orderBy('date_souhaitee')
                                   ->limit(5)
                                   ->get();

        // Statistiques par statut
        $statsParStatut = Commande::selectRaw('statut, COUNT(*) as count')
                                ->groupBy('statut')
                                ->pluck('count', 'statut')
                                ->toArray();

        return view('commandes.dashboard', compact('stats', 'commandesRecentes', 'commandesUrgentes', 'statsParStatut'));
    }
}