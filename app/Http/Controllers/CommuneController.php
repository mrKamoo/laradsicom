<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    public function index(Request $request)
    {
        $query = Commune::withCount('prescripteurs');

        // Filtres
        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('code_postal', 'LIKE', "%{$search}%")
                  ->orWhere('code_insee', 'LIKE', "%{$search}%");
            });
        }

        $communes = $query->orderBy('nom')->paginate(20);

        return view('communes.index', compact('communes'));
    }

    public function create()
    {
        return view('communes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_postal' => 'required|string|max:5',
            'code_insee' => 'nullable|string|max:5',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $commune = Commune::create($validated);

        return redirect()->route('communes.index')
                        ->with('success', 'Commune créée avec succès.');
    }

    public function show(Commune $commune)
    {
        $commune->load(['prescripteurs' => function($query) {
            $query->orderBy('nom')->orderBy('prenom');
        }]);

        $stats = [
            'prescripteurs_total' => $commune->prescripteurs()->count(),
            'prescripteurs_actifs' => $commune->prescripteursActifs()->count(),
            'commandes_total' => $commune->prescripteurs()->withCount('commandes')->get()->sum('commandes_count'),
        ];

        return view('communes.show', compact('commune', 'stats'));
    }

    public function edit(Commune $commune)
    {
        return view('communes.edit', compact('commune'));
    }

    public function update(Request $request, Commune $commune)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_postal' => 'required|string|max:5',
            'code_insee' => 'nullable|string|max:5',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Gérer la checkbox 'active' séparément
        $validated['active'] = $request->has('active');

        $commune->update($validated);

        return redirect()->route('communes.show', $commune)
                        ->with('success', 'Commune mise à jour avec succès.');
    }

    public function destroy(Commune $commune)
    {
        // Vérifier si la commune a des prescripteurs
        if ($commune->prescripteurs()->count() > 0) {
            return redirect()->route('communes.index')
                           ->with('error', 'Impossible de supprimer cette commune car elle a des prescripteurs associés.');
        }

        $commune->delete();

        return redirect()->route('communes.index')
                        ->with('success', 'Commune supprimée avec succès.');
    }

    public function toggleActive(Commune $commune)
    {
        $commune->update(['active' => !$commune->active]);

        $message = $commune->active ? 'Commune activée.' : 'Commune désactivée.';

        return redirect()->back()->with('success', $message);
    }
}