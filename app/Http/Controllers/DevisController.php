<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Devis;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function create(Commande $commande)
    {
        $fournisseurs = Fournisseur::actifs()->get();

        return view('devis.create', compact('commande', 'fournisseurs'));
    }

    public function store(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_devis' => 'required|string|max:255',
            'date_devis' => 'required|date',
            'date_validite' => 'nullable|date|after_or_equal:date_devis',
            'montant_ht' => 'required|numeric|min:0',
            'taux_tva' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant_ttc' => 'required|numeric|min:0',
            'delai_livraison' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'conditions_particulieres' => 'nullable|string',
            'garanti' => 'nullable|boolean',
            'installation_incluse' => 'nullable|boolean',
            'fichier_devis' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Recalcul automatique des montants TVA et TTC
        $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
        $validated['montant_ttc'] = $validated['montant_ht'] + $validated['montant_tva'];

        // Gestion du fichier upload
        if ($request->hasFile('fichier_devis')) {
            $file = $request->file('fichier_devis');
            $nom_original = $file->getClientOriginalName();
            $nom_fichier = time() . '_' . $nom_original;

            // Stocker le fichier dans storage/app/devis
            $chemin = $file->storeAs('devis', $nom_fichier);

            $validated['fichier_devis'] = $chemin;
            $validated['nom_fichier_original'] = $nom_original;
        }

        $validated['commande_id'] = $commande->id;

        Devis::create($validated);

        return redirect()->route('commandes.show', $commande)
                        ->with('success', 'Devis ajouté avec succès.');
    }

    public function show(Devis $devis)
    {
        $devis->load(['commande.prescripteur.commune', 'fournisseur']);

        // Récupérer les autres devis de la même commande
        $autresDevis = $devis->commande->devis()->where('id', '!=', $devis->id)->with('fournisseur')->get();

        return view('devis.show', compact('devis', 'autresDevis'));
    }

    public function edit(Devis $devis)
    {
        // Un devis ne peut être modifié que s'il n'est pas sélectionné
        if ($devis->selectionne) {
            return redirect()->route('commandes.show', $devis->commande)
                           ->with('error', 'Un devis sélectionné ne peut plus être modifié.');
        }

        $fournisseurs = Fournisseur::actifs()->get();

        return view('devis.edit', compact('devis', 'fournisseurs'));
    }

    public function update(Request $request, Devis $devis)
    {
        if ($devis->selectionne) {
            return redirect()->route('commandes.show', $devis->commande)
                           ->with('error', 'Un devis sélectionné ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_devis' => 'required|string|max:255',
            'date_devis' => 'required|date',
            'date_validite' => 'nullable|date|after_or_equal:date_devis',
            'montant_ht' => 'required|numeric|min:0',
            'taux_tva' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant_ttc' => 'required|numeric|min:0',
            'delai_livraison' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'conditions_particulieres' => 'nullable|string',
            'garanti' => 'nullable|boolean',
            'installation_incluse' => 'nullable|boolean',
            'fichier_devis' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Recalcul automatique des montants TVA et TTC
        $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
        $validated['montant_ttc'] = $validated['montant_ht'] + $validated['montant_tva'];

        // Gestion du fichier upload
        if ($request->hasFile('fichier_devis')) {
            // Supprimer l'ancien fichier s'il existe
            if ($devis->fichier_devis) {
                \Storage::delete($devis->fichier_devis);
            }

            $file = $request->file('fichier_devis');
            $nom_original = $file->getClientOriginalName();
            $nom_fichier = time() . '_' . $nom_original;

            // Stocker le nouveau fichier
            $chemin = $file->storeAs('devis', $nom_fichier);

            $validated['fichier_devis'] = $chemin;
            $validated['nom_fichier_original'] = $nom_original;
        }

        $devis->update($validated);

        return redirect()->route('commandes.show', $devis->commande)
                        ->with('success', 'Devis mis à jour avec succès.');
    }

    public function destroy(Devis $devis)
    {
        if ($devis->selectionne) {
            return redirect()->route('commandes.show', $devis->commande)
                           ->with('error', 'Un devis sélectionné ne peut pas être supprimé.');
        }

        $commande = $devis->commande;
        $devis->delete();

        return redirect()->route('commandes.show', $commande)
                        ->with('success', 'Devis supprimé avec succès.');
    }

    public function select(Devis $devis)
    {
        if ($devis->selectionner()) {
            return redirect()->route('commandes.show', $devis->commande)
                           ->with('success', 'Devis sélectionné avec succès.');
        }

        return redirect()->route('commandes.show', $devis->commande)
                        ->with('error', 'Erreur lors de la sélection du devis.');
    }

    public function deselect(Devis $devis)
    {
        $devis->selectionne = false;
        $devis->date_selection = null;
        $devis->save();

        // Remettre le montant final de la commande à null
        $devis->commande()->update(['montant_final' => null]);

        return redirect()->route('commandes.show', $devis->commande)
                        ->with('success', 'Devis désélectionné avec succès.');
    }

    public function retenir(Devis $devis)
    {
        if ($devis->selectionner()) {
            return redirect()->route('commandes.show', $devis->commande)
                           ->with('success', 'Devis sélectionné avec succès.');
        }

        return redirect()->route('commandes.show', $devis->commande)
                        ->with('error', 'Erreur lors de la sélection du devis.');
    }

    public function deselectioner(Devis $devis)
    {
        $devis->selectionne = false;
        $devis->date_selection = null;
        $devis->save();

        // Remettre le montant final de la commande à null
        $devis->commande()->update(['montant_final' => null]);

        return redirect()->route('commandes.show', $devis->commande)
                        ->with('success', 'Devis désélectionné avec succès.');
    }

    public function download(Devis $devis)
    {
        if (!$devis->fichier_devis || !\Storage::exists($devis->fichier_devis)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        return \Storage::download($devis->fichier_devis, $devis->nom_fichier_original);
    }
}