<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-truck me-2"></i>
                Nouveau Fournisseur
            </h2>
            <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
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
                            <i class="fas fa-truck me-2"></i>
                            Informations du fournisseur
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('fournisseurs.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom commercial <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                               id="nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="raison_sociale" class="form-label">Raison sociale</label>
                                        <input type="text" class="form-control @error('raison_sociale') is-invalid @enderror"
                                               id="raison_sociale" name="raison_sociale" value="{{ old('raison_sociale') }}">
                                        @error('raison_sociale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="siret" class="form-label">SIRET</label>
                                        <input type="text" class="form-control @error('siret') is-invalid @enderror"
                                               id="siret" name="siret" value="{{ old('siret') }}"
                                               placeholder="14 chiffres" maxlength="14">
                                        @error('siret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="active"
                                                   name="active" {{ old('active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">
                                                Fournisseur actif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Adresse</h6>

                                    <div class="mb-3">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <textarea class="form-control @error('adresse') is-invalid @enderror"
                                                  id="adresse" name="adresse" rows="3">{{ old('adresse') }}</textarea>
                                        @error('adresse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="code_postal" class="form-label">Code postal</label>
                                                <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                                       id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                                       maxlength="5">
                                                @error('code_postal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="ville" class="form-label">Ville</label>
                                                <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                                       id="ville" name="ville" value="{{ old('ville') }}">
                                                @error('ville')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Contact principal</h6>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                               id="telephone" name="telephone" value="{{ old('telephone') }}"
                                               placeholder="01 23 45 67 89">
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Contact commercial</h6>

                                    <div class="mb-3">
                                        <label for="contact_commercial" class="form-label">Nom du contact</label>
                                        <input type="text" class="form-control @error('contact_commercial') is-invalid @enderror"
                                               id="contact_commercial" name="contact_commercial" value="{{ old('contact_commercial') }}">
                                        @error('contact_commercial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_commercial" class="form-label">Email commercial</label>
                                        <input type="email" class="form-control @error('email_commercial') is-invalid @enderror"
                                               id="email_commercial" name="email_commercial" value="{{ old('email_commercial') }}">
                                        @error('email_commercial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="telephone_commercial" class="form-label">Téléphone commercial</label>
                                        <input type="tel" class="form-control @error('telephone_commercial') is-invalid @enderror"
                                               id="telephone_commercial" name="telephone_commercial" value="{{ old('telephone_commercial') }}"
                                               placeholder="01 23 45 67 89">
                                        @error('telephone_commercial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="Notes internes sur le fournisseur...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer le fournisseur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>