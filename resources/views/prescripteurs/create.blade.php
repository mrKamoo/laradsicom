<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-user-plus me-2"></i>
                Nouveau Prescripteur
            </h2>
            <a href="{{ route('prescripteurs.index') }}" class="btn btn-outline-secondary">
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
                            <i class="fas fa-user me-2"></i>
                            Informations du prescripteur
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('prescripteurs.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Identité</h6>

                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                               id="nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                               id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="commune_id" class="form-label">Commune <span class="text-danger">*</span></label>
                                        <select class="form-select @error('commune_id') is-invalid @enderror"
                                                id="commune_id" name="commune_id" required>
                                            <option value="">Sélectionnez une commune</option>
                                            @foreach($communes as $commune)
                                                <option value="{{ $commune->id }}"
                                                        {{ old('commune_id') == $commune->id ? 'selected' : '' }}>
                                                    {{ $commune->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('commune_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">Fonction</h6>

                                    <div class="mb-3">
                                        <label for="fonction" class="form-label">Fonction</label>
                                        <input type="text" class="form-control @error('fonction') is-invalid @enderror"
                                               id="fonction" name="fonction" value="{{ old('fonction') }}"
                                               placeholder="Ex: Maire, Adjoint, Secrétaire...">
                                        @error('fonction')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="service" class="form-label">Service</label>
                                        <input type="text" class="form-control @error('service') is-invalid @enderror"
                                               id="service" name="service" value="{{ old('service') }}"
                                               placeholder="Ex: Services techniques, Administration...">
                                        @error('service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="active"
                                                   name="active" {{ old('active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">
                                                Prescripteur actif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">Coordonnées</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone fixe</label>
                                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                               id="telephone" name="telephone" value="{{ old('telephone') }}"
                                               placeholder="01 23 45 67 89">
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="telephone_mobile" class="form-label">Téléphone mobile</label>
                                        <input type="tel" class="form-control @error('telephone_mobile') is-invalid @enderror"
                                               id="telephone_mobile" name="telephone_mobile" value="{{ old('telephone_mobile') }}"
                                               placeholder="06 12 34 56 78">
                                        @error('telephone_mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('prescripteurs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer le prescripteur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>