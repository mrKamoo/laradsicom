<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-plus me-2"></i>
                Nouveau Type de Commande
            </h2>
            <a href="{{ route('type-commandes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="container">
        <!-- Messages d'erreur globaux -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations du type
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('type-commandes.store') }}">
                            @csrf

                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom du type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                       id="nom" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Description du type de commande (optionnel)</div>
                            </div>

                            <!-- Couleur -->
                            <div class="mb-3">
                                <label for="couleur" class="form-label">Couleur <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color @error('couleur') is-invalid @enderror"
                                           id="couleur" name="couleur" value="{{ old('couleur', '#007bff') }}" required>
                                    <input type="text" class="form-control @error('couleur') is-invalid @enderror"
                                           id="couleur_text" value="{{ old('couleur', '#007bff') }}" readonly>
                                </div>
                                @error('couleur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Couleur d'affichage du type dans l'interface</div>
                            </div>

                            <!-- Statut actif -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="active" name="active"
                                           {{ old('active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">
                                        Type actif
                                    </label>
                                </div>
                                <div class="form-text">Les types inactifs ne seront pas proposés lors de la création de nouvelles commandes</div>
                            </div>

                            <!-- Preview -->
                            <div class="mb-4">
                                <label class="form-label">Aperçu</label>
                                <div class="p-3 border rounded bg-light">
                                    <span id="badge-preview" class="badge" style="background-color: #007bff;">
                                        Nom du type
                                    </span>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('type-commandes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer le type
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mise à jour de l'aperçu en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            const nomInput = document.getElementById('nom');
            const couleurInput = document.getElementById('couleur');
            const couleurTextInput = document.getElementById('couleur_text');
            const badgePreview = document.getElementById('badge-preview');

            function updatePreview() {
                const nom = nomInput.value || 'Nom du type';
                const couleur = couleurInput.value;

                badgePreview.textContent = nom;
                badgePreview.style.backgroundColor = couleur;
                couleurTextInput.value = couleur;
            }

            nomInput.addEventListener('input', updatePreview);
            couleurInput.addEventListener('change', updatePreview);

            // Initialisation
            updatePreview();
        });
    </script>
</x-app-layout>