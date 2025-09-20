<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-plus me-2"></i>
                Nouveau devis pour la commande {{ $commande->numero_commande }}
            </h2>
            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Retour à la commande
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Informations sur la commande -->
                <div class="alert alert-info" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">Commande : {{ $commande->numero_commande }}</h5>
                            <p class="mb-1"><strong>Objet :</strong> {{ $commande->objet }}</p>
                            <p class="mb-1"><strong>Prescripteur :</strong> {{ $commande->prescripteur->nom_complet }} ({{ $commande->prescripteur->commune->nom }})</p>
                            <p class="mb-0"><strong>Montant estimé :</strong>
                                @if($commande->montant_estime)
                                    {{ number_format($commande->montant_estime, 2, ',', ' ') }} €
                                @else
                                    Non renseigné
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Créer un nouveau devis
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('devis.store', $commande) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="commande_id" value="{{ $commande->id }}">

                            <div class="row">
                                <!-- Informations fournisseur -->
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-building me-2"></i>
                                        Fournisseur
                                    </h6>

                                    <div class="mb-3">
                                        <label for="fournisseur_id" class="form-label">Fournisseur *</label>
                                        <select class="form-select @error('fournisseur_id') is-invalid @enderror"
                                                id="fournisseur_id" name="fournisseur_id" required>
                                            <option value="">Sélectionnez un fournisseur</option>
                                            @foreach($fournisseurs as $fournisseur)
                                                <option value="{{ $fournisseur->id }}"
                                                        {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                                    {{ $fournisseur->nom }} - {{ $fournisseur->ville }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('fournisseur_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="numero_devis" class="form-label">Référence du devis *</label>
                                        <input type="text" class="form-control @error('numero_devis') is-invalid @enderror"
                                               id="numero_devis" name="numero_devis" value="{{ old('numero_devis') }}" required
                                               placeholder="Ex: DEVIS-2024-001">
                                        @error('numero_devis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_devis" class="form-label">Date du devis *</label>
                                        <input type="date" class="form-control @error('date_devis') is-invalid @enderror"
                                               id="date_devis" name="date_devis"
                                               value="{{ old('date_devis', date('Y-m-d')) }}" required>
                                        @error('date_devis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_validite" class="form-label">Date de validité</label>
                                        <input type="date" class="form-control @error('date_validite') is-invalid @enderror"
                                               id="date_validite" name="date_validite"
                                               value="{{ old('date_validite') }}">
                                        <div class="form-text">Date limite de validité de cette offre</div>
                                        @error('date_validite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Montants -->
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-euro-sign me-2"></i>
                                        Montants
                                    </h6>

                                    <div class="mb-3">
                                        <label for="montant_ht" class="form-label">Montant HT *</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   class="form-control @error('montant_ht') is-invalid @enderror"
                                                   id="montant_ht" name="montant_ht"
                                                   value="{{ old('montant_ht') }}" required
                                                   placeholder="0.00">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        @error('montant_ht')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="taux_tva" class="form-label">Taux de TVA *</label>
                                        <div class="input-group">
                                            <select class="form-select @error('taux_tva') is-invalid @enderror"
                                                    id="taux_tva" name="taux_tva" required>
                                                <option value="">Sélectionnez un taux</option>
                                                <option value="0" {{ old('taux_tva') == '0' ? 'selected' : '' }}>0% (Exonéré)</option>
                                                <option value="5.5" {{ old('taux_tva') == '5.5' ? 'selected' : '' }}>5,5% (Taux réduit)</option>
                                                <option value="10" {{ old('taux_tva') == '10' ? 'selected' : '' }}>10% (Taux intermédiaire)</option>
                                                <option value="20" {{ old('taux_tva', '20') == '20' ? 'selected' : '' }}>20% (Taux normal)</option>
                                            </select>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('taux_tva')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="montant_tva" class="form-label">Montant TVA</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   class="form-control @error('montant_tva') is-invalid @enderror"
                                                   id="montant_tva" name="montant_tva"
                                                   value="{{ old('montant_tva') }}" readonly
                                                   placeholder="0.00">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <div class="form-text">Calculé automatiquement</div>
                                        @error('montant_tva')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="montant_ttc" class="form-label">Montant TTC</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   class="form-control @error('montant_ttc') is-invalid @enderror"
                                                   id="montant_ttc" name="montant_ttc"
                                                   value="{{ old('montant_ttc') }}" readonly
                                                   placeholder="0.00">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <div class="form-text">Calculé automatiquement</div>
                                        @error('montant_ttc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="delai_livraison" class="form-label">Délai de livraison</label>
                                        <div class="input-group">
                                            <input type="number" min="0"
                                                   class="form-control @error('delai_livraison') is-invalid @enderror"
                                                   id="delai_livraison" name="delai_livraison"
                                                   value="{{ old('delai_livraison') }}"
                                                   placeholder="Ex: 15">
                                            <span class="input-group-text">jours</span>
                                        </div>
                                        <div class="form-text">Délai indicatif en jours ouvrés</div>
                                        @error('delai_livraison')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description et conditions -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-file-alt me-2"></i>
                                        Détails du devis
                                    </h6>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description détaillée</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4"
                                                  placeholder="Décrivez précisément les éléments inclus dans ce devis...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                                        <textarea class="form-control @error('conditions_particulieres') is-invalid @enderror"
                                                  id="conditions_particulieres" name="conditions_particulieres" rows="3"
                                                  placeholder="Conditions de paiement, garanties, modalités particulières...">{{ old('conditions_particulieres') }}</textarea>
                                        @error('conditions_particulieres')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="fichier_devis" class="form-label">Pièce jointe (Devis du fournisseur)</label>
                                        <input type="file" class="form-control @error('fichier_devis') is-invalid @enderror"
                                               id="fichier_devis" name="fichier_devis"
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <div class="form-text">
                                            Formats acceptés : PDF, Word, Images (JPG, PNG) - Taille max : 5 Mo
                                        </div>
                                        @error('fichier_devis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="garanti" name="garanti" value="1"
                                                       {{ old('garanti') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="garanti">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    Produit/service garanti
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="installation_incluse" name="installation_incluse" value="1"
                                                       {{ old('installation_incluse') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="installation_incluse">
                                                    <i class="fas fa-tools me-1"></i>
                                                    Installation incluse
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="row">
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('commandes.show', $commande) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-1"></i>
                                            Enregistrer le devis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calcul automatique de la TVA et du TTC
        function calculerMontants() {
            const montantHT = parseFloat(document.getElementById('montant_ht').value) || 0;
            const tauxTVA = parseFloat(document.getElementById('taux_tva').value) || 0;

            const montantTVA = montantHT * tauxTVA / 100;
            const montantTTC = montantHT + montantTVA;

            document.getElementById('montant_tva').value = montantTVA.toFixed(2);
            document.getElementById('montant_ttc').value = montantTTC.toFixed(2);
        }

        // Événements pour le calcul automatique
        document.getElementById('montant_ht').addEventListener('input', calculerMontants);
        document.getElementById('taux_tva').addEventListener('change', calculerMontants);

        // Calcul initial au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            calculerMontants();
        });

        // Validation de la date de validité
        document.getElementById('date_validite').addEventListener('change', function() {
            const dateDevis = document.getElementById('date_devis').value;
            const dateValidite = this.value;

            if (dateDevis && dateValidite && dateValidite < dateDevis) {
                alert('La date de validité ne peut pas être antérieure à la date du devis.');
                this.value = '';
            }
        });
    </script>
</x-app-layout>