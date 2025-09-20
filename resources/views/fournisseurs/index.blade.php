<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-truck me-2"></i>
                Gestion des Fournisseurs
            </h2>
            <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouveau fournisseur
            </a>
        </div>
    </x-slot>

    <div class="container">
        <!-- Messages flash -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtres et recherche -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('fournisseurs.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nom, email, SIRET, ville...">
                    </div>
                    <div class="col-md-3">
                        <label for="ville" class="form-label">Ville</label>
                        <select class="form-select" id="ville" name="ville">
                            <option value="">Toutes les villes</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                    {{ $ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="active" class="form-label">Statut</label>
                        <select class="form-select" id="active" name="active">
                            <option value="">Tous</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            Rechercher
                        </button>
                        <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des fournisseurs -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des fournisseurs ({{ $fournisseurs->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($fournisseurs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom / Raison sociale</th>
                                    <th>Ville</th>
                                    <th>Contact</th>
                                    <th>Devis</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fournisseurs as $fournisseur)
                                <tr class="{{ !$fournisseur->active ? 'table-light text-muted' : '' }}">
                                    <td>
                                        <div class="fw-bold">{{ $fournisseur->nom }}</div>
                                        @if($fournisseur->raison_sociale)
                                            <small class="text-muted">{{ $fournisseur->raison_sociale }}</small>
                                        @endif
                                        @if($fournisseur->siret)
                                            <br><small class="text-muted">SIRET: {{ $fournisseur->siret }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fournisseur->ville)
                                            {{ $fournisseur->ville }}
                                            @if($fournisseur->code_postal)
                                                <br><small class="text-muted">{{ $fournisseur->code_postal }}</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($fournisseur->email)
                                            <div><i class="fas fa-envelope me-1"></i>{{ $fournisseur->email }}</div>
                                        @endif
                                        @if($fournisseur->telephone)
                                            <div><i class="fas fa-phone me-1"></i>{{ $fournisseur->telephone }}</div>
                                        @endif
                                        @if($fournisseur->contact_commercial)
                                            <div><i class="fas fa-user me-1"></i>{{ $fournisseur->contact_commercial }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $fournisseur->devis_count }}</span>
                                    </td>
                                    <td>
                                        @if($fournisseur->active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('fournisseurs.show', $fournisseur) }}"
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                                               class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fournisseurs.toggle-active', $fournisseur) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-outline-{{ $fournisseur->active ? 'warning' : 'success' }}"
                                                        title="{{ $fournisseur->active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $fournisseur->active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            @if($fournisseur->devis()->count() === 0)
                                                <form action="{{ route('fournisseurs.destroy', $fournisseur) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $fournisseurs->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun fournisseur trouvé</h5>
                        <p class="text-muted">Commencez par créer un nouveau fournisseur.</p>
                        <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer un fournisseur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>