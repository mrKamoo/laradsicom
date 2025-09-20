<?php

namespace App\Http\Controllers;

use App\Models\Prescripteur;
use App\Models\Commune;
use Illuminate\Http\Request;

class PrescripteurController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescripteur::with('commune');

        // Filtres
        if ($request->filled('commune_id')) {
            $query->where('commune_id', $request->commune_id);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('fonction', 'LIKE', "%{$search}%")
                  ->orWhere('service', 'LIKE', "%{$search}%");
            });
        }

        $prescripteurs = $query->orderBy('nom')->orderBy('prenom')->paginate(20);
        $communes = Commune::actives()->orderBy('nom')->get();

        return view('prescripteurs.index', compact('prescripteurs', 'communes'));
    }

    public function create()
    {
        $communes = Commune::actives()->orderBy('nom')->get();
        return view('prescripteurs.create', compact('communes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'commune_id' => 'required|exists:communes,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'email' => 'required|email|unique:prescripteurs,email',
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $prescripteur = Prescripteur::create($validated);

        return redirect()->route('prescripteurs.index')
                        ->with('success', 'Prescripteur créé avec succès.');
    }

    public function show(Prescripteur $prescripteur)
    {
        $prescripteur->load(['commune', 'commandes' => function($query) {
            $query->with('typeCommande')->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('prescripteurs.show', compact('prescripteur'));
    }

    public function edit(Prescripteur $prescripteur)
    {
        $communes = Commune::actives()->orderBy('nom')->get();
        return view('prescripteurs.edit', compact('prescripteur', 'communes'));
    }

    public function update(Request $request, Prescripteur $prescripteur)
    {
        $validated = $request->validate([
            'commune_id' => 'required|exists:communes,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'fonction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'email' => 'required|email|unique:prescripteurs,email,' . $prescripteur->id,
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $prescripteur->update($validated);

        return redirect()->route('prescripteurs.show', $prescripteur)
                        ->with('success', 'Prescripteur mis à jour avec succès.');
    }

    public function destroy(Prescripteur $prescripteur)
    {
        // Vérifier si le prescripteur a des commandes
        if ($prescripteur->commandes()->count() > 0) {
            return redirect()->route('prescripteurs.index')
                           ->with('error', 'Impossible de supprimer ce prescripteur car il a des commandes associées.');
        }

        $prescripteur->delete();

        return redirect()->route('prescripteurs.index')
                        ->with('success', 'Prescripteur supprimé avec succès.');
    }

    public function toggleActive(Prescripteur $prescripteur)
    {
        $prescripteur->update(['active' => !$prescripteur->active]);

        $message = $prescripteur->active ? 'Prescripteur activé.' : 'Prescripteur désactivé.';

        return redirect()->back()->with('success', $message);
    }
}