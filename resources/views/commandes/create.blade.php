<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-plus me-2"></i>
                Nouvelle commande
            </h2>
            <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-plus me-2"></i>
                            Créer une nouvelle commande
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('commandes.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Informations générales -->
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informations générales
                                    </h6>

                                    <div class="mb-3">
                                        <label for="prescripteur_id" class="form-label">Prescripteur *</label>
                                        <select class="form-select @error('prescripteur_id') is-invalid @enderror"
                                                id="prescripteur_id" name="prescripteur_id" required>
                                            <option value="">Sélectionnez un prescripteur</option>
                                            @foreach($prescripteurs->groupBy('commune.nom') as $commune => $prescripteursCommune)
                                                <optgroup label="{{ $commune }}">
                                                    @foreach($prescripteursCommune as $prescripteur)
                                                        <option value="{{ $prescripteur->id }}"
                                                                {{ old('prescripteur_id') == $prescripteur->id ? 'selected' : '' }}>
                                                            {{ $prescripteur->identite_complete }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('prescripteur_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type_commande_id" class="form-label">Type de commande *</label>
                                        <select class="form-select @error('type_commande_id') is-invalid @enderror"
                                                id="type_commande_id" name="type_commande_id" required>
                                            <option value="">Sélectionnez un type</option>
                                            @foreach($typesCommandes as $type)
                                                <option value="{{ $type->id }}"
                                                        {{ old('type_commande_id') == $type->id ? 'selected' : '' }}
                                                        data-color="{{ $type->couleur }}">
                                                    {{ $type->nom }} - {{ $type->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type_commande_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="objet" class="form-label">Objet de la commande *</label>
                                        <input type="text" class="form-control @error('objet') is-invalid @enderror"
                                               id="objet" name="objet" value="{{ old('objet') }}" required
                                               placeholder="Ex: Ordinateurs portables pour le service informatique">
                                        @error('objet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description détaillée *</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4" required
                                                  placeholder="Décrivez précisément le matériel ou service demandé...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Détails de la demande -->
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Détails de la demande
                                    </h6>

                                    <div class="mb-3">
                                        <label for="date_demande" class="form-label">Date de la demande *</label>
                                        <input type="date" class="form-control @error('date_demande') is-invalid @enderror"
                                               id="date_demande" name="date_demande"
                                               value="{{ old('date_demande', date('Y-m-d')) }}" required>
                                        @error('date_demande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_souhaitee" class="form-label">Date souhaitée</label>
                                        <input type="date" class="form-control @error('date_souhaitee') is-invalid @enderror"
                                               id="date_souhaitee" name="date_souhaitee"
                                               value="{{ old('date_souhaitee') }}">
                                        <div class="form-text">Date limite souhaitée pour la livraison/réalisation</div>
                                        @error('date_souhaitee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="urgent" name="urgent" value="1"
                                                   {{ old('urgent') ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger fw-bold" for="urgent">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Commande urgente
                                            </label>
                                        </div>
                                        <div class="form-text">Cochez si cette commande est prioritaire</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="montant_estime" class="form-label">Montant estimé</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                   class="form-control @error('montant_estime') is-invalid @enderror"
                                                   id="montant_estime" name="montant_estime"
                                                   value="{{ old('montant_estime') }}"
                                                   placeholder="0.00">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        @error('montant_estime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="imputation_budgetaire" class="form-label">Imputation budgétaire</label>
                                        <input type="text" class="form-control @error('imputation_budgetaire') is-invalid @enderror"
                                               id="imputation_budgetaire" name="imputation_budgetaire"
                                               value="{{ old('imputation_budgetaire') }}"
                                               placeholder="Ex: 6063 - Fournitures informatiques">
                                        @error('imputation_budgetaire')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Justification -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-comment-alt me-2"></i>
                                        Justification (optionnel)
                                    </h6>
                                    <div class="mb-3">
                                        <label for="justification" class="form-label">Justification de la demande</label>
                                        <textarea class="form-control @error('justification') is-invalid @enderror"
                                                  id="justification" name="justification" rows="3"
                                                  placeholder="Expliquez le contexte et la nécessité de cette commande...">{{ old('justification') }}</textarea>
                                        @error('justification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="row">
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-1"></i>
                                            Créer la commande
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
        // Colorer le select des types de commande
        document.getElementById('type_commande_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const color = option.getAttribute('data-color');
            if (color) {
                this.style.borderColor = color;
            } else {
                this.style.borderColor = '';
            }
        });

        // Validation de la date souhaitée
        document.getElementById('date_souhaitee').addEventListener('change', function() {
            const dateDemande = document.getElementById('date_demande').value;
            const dateSouhaitee = this.value;

            if (dateDemande && dateSouhaitee && dateSouhaitee < dateDemande) {
                alert('La date souhaitée ne peut pas être antérieure à la date de demande.');
                this.value = '';
            }
        });
    </script>
</x-app-layout>