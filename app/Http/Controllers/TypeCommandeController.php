<?php

namespace App\Http\Controllers;

use App\Models\TypeCommande;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TypeCommandeController extends Controller
{
    public function index(): View
    {
        $types = TypeCommande::withCount('commandes')->orderBy('nom')->get();

        return view('type-commandes.index', compact('types'));
    }

    public function create(): View
    {
        return view('type-commandes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Debug temporaire
        \Log::info('Données reçues: ', $request->all());

        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255|unique:types_commandes,nom',
                'description' => 'nullable|string',
                'couleur' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            ]);

            $validated['active'] = $request->has('active');

            \Log::info('Données validées: ', $validated);

            $type = TypeCommande::create($validated);

            \Log::info('Type créé avec ID: ' . $type->id);

            return redirect()->route('type-commandes.index')
                ->with('success', 'Type de commande créé avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()]);
        }
    }

    public function show(TypeCommande $typeCommande): View
    {
        $typeCommande->load(['commandes' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('type-commandes.show', compact('typeCommande'));
    }

    public function edit(TypeCommande $typeCommande): View
    {
        return view('type-commandes.edit', compact('typeCommande'));
    }

    public function update(Request $request, TypeCommande $typeCommande): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:types_commandes,nom,' . $typeCommande->id,
            'description' => 'nullable|string',
            'couleur' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['active'] = $request->has('active');

        $typeCommande->update($validated);

        return redirect()->route('type-commandes.index')
            ->with('success', 'Type de commande mis à jour avec succès.');
    }

    public function destroy(TypeCommande $typeCommande): RedirectResponse
    {
        if ($typeCommande->commandes()->exists()) {
            return redirect()->route('type-commandes.index')
                ->with('error', 'Impossible de supprimer ce type car il est utilisé par des commandes.');
        }

        $typeCommande->delete();

        return redirect()->route('type-commandes.index')
            ->with('success', 'Type de commande supprimé avec succès.');
    }

    public function toggle(TypeCommande $typeCommande): RedirectResponse
    {
        $typeCommande->update(['active' => !$typeCommande->active]);

        $status = $typeCommande->active ? 'activé' : 'désactivé';

        return redirect()->route('type-commandes.index')
            ->with('success', "Type de commande {$status} avec succès.");
    }
}