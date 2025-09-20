<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index(Request $request)
    {
        $query = Fournisseur::withCount('devis');

        // Filtres
        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        if ($request->filled('ville')) {
            $query->where('ville', 'LIKE', "%{$request->ville}%");
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('raison_sociale', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('contact_commercial', 'LIKE', "%{$search}%")
                  ->orWhere('siret', 'LIKE', "%{$search}%")
                  ->orWhere('ville', 'LIKE', "%{$search}%");
            });
        }

        $fournisseurs = $query->orderBy('nom')->paginate(20);

        // Récupérer les villes pour le filtre
        $villes = Fournisseur::select('ville')
                             ->whereNotNull('ville')
                             ->where('ville', '!=', '')
                             ->distinct()
                             ->orderBy('ville')
                             ->pluck('ville');

        return view('fournisseurs.index', compact('fournisseurs', 'villes'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'raison_sociale' => 'nullable|string|max:255',
            'siret' => 'nullable|string|max:14|unique:fournisseurs,siret',
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string|max:5',
            'ville' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_commercial' => 'nullable|string|max:255',
            'email_commercial' => 'nullable|email|max:255',
            'telephone_commercial' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $fournisseur = Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
                        ->with('success', 'Fournisseur créé avec succès.');
    }

    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load(['devis' => function($query) {
            $query->with('commande')->orderBy('created_at', 'desc')->limit(10);
        }]);

        // Statistiques
        $stats = [
            'devis_total' => $fournisseur->devis()->count(),
            'devis_selectionnes' => $fournisseur->devis()->where('selectionne', true)->count(),
            'montant_total_devis' => $fournisseur->devis()->sum('montant_ttc'),
        ];

        return view('fournisseurs.show', compact('fournisseur', 'stats'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'raison_sociale' => 'nullable|string|max:255',
            'siret' => 'nullable|string|max:14|unique:fournisseurs,siret,' . $fournisseur->id,
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string|max:5',
            'ville' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_commercial' => 'nullable|string|max:255',
            'email_commercial' => 'nullable|email|max:255',
            'telephone_commercial' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.show', $fournisseur)
                        ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        // Vérifier si le fournisseur a des devis
        if ($fournisseur->devis()->count() > 0) {
            return redirect()->route('fournisseurs.index')
                           ->with('error', 'Impossible de supprimer ce fournisseur car il a des devis associés.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
                        ->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function toggleActive(Fournisseur $fournisseur)
    {
        $fournisseur->update(['active' => !$fournisseur->active]);

        $message = $fournisseur->active ? 'Fournisseur activé.' : 'Fournisseur désactivé.';

        return redirect()->back()->with('success', $message);
    }
}