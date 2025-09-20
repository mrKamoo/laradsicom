<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-building me-2"></i>
                Nouvelle Commune
            </h2>
            <a href="{{ route('communes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>
                            Informations de la commune
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('communes.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom de la commune <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                               id="nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="code_postal" class="form-label">Code postal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                               id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                               placeholder="Ex: 75001" maxlength="5" required>
                                        @error('code_postal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="code_insee" class="form-label">Code INSEE</label>
                                        <input type="text" class="form-control @error('code_insee') is-invalid @enderror"
                                               id="code_insee" name="code_insee" value="{{ old('code_insee') }}"
                                               placeholder="Ex: 75101" maxlength="5">
                                        @error('code_insee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="active"
                                                   name="active" {{ old('active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">
                                                Commune active
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Coordonnées</h6>

                                    <div class="mb-3">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <textarea class="form-control @error('adresse') is-invalid @enderror"
                                                  id="adresse" name="adresse" rows="3"
                                                  placeholder="Adresse complète de la mairie">{{ old('adresse') }}</textarea>
                                        @error('adresse')
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

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="mairie@commune.fr">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('communes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer la commune
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>