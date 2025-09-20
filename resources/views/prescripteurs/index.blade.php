<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-users me-2"></i>
                Gestion des Prescripteurs
            </h2>
            <a href="{{ route('prescripteurs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouveau prescripteur
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
                <form method="GET" action="{{ route('prescripteurs.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nom, prénom, email...">
                    </div>
                    <div class="col-md-3">
                        <label for="commune_id" class="form-label">Commune</label>
                        <select class="form-select" id="commune_id" name="commune_id">
                            <option value="">Toutes les communes</option>
                            @foreach($communes as $commune)
                                <option value="{{ $commune->id }}" {{ request('commune_id') == $commune->id ? 'selected' : '' }}>
                                    {{ $commune->nom }}
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
                        <a href="{{ route('prescripteurs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des prescripteurs -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des prescripteurs ({{ $prescripteurs->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($prescripteurs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom complet</th>
                                    <th>Commune</th>
                                    <th>Fonction</th>
                                    <th>Contact</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescripteurs as $prescripteur)
                                <tr class="{{ !$prescripteur->active ? 'table-light text-muted' : '' }}">
                                    <td>
                                        <div class="fw-bold">{{ $prescripteur->nom_complet }}</div>
                                        @if($prescripteur->service)
                                            <small class="text-muted">{{ $prescripteur->service }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $prescripteur->commune->nom }}</td>
                                    <td>{{ $prescripteur->fonction ?? '-' }}</td>
                                    <td>
                                        @if($prescripteur->email)
                                            <div><i class="fas fa-envelope me-1"></i>{{ $prescripteur->email }}</div>
                                        @endif
                                        @if($prescripteur->telephone)
                                            <div><i class="fas fa-phone me-1"></i>{{ $prescripteur->telephone }}</div>
                                        @endif
                                        @if($prescripteur->telephone_mobile)
                                            <div><i class="fas fa-mobile me-1"></i>{{ $prescripteur->telephone_mobile }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($prescripteur->active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('prescripteurs.show', $prescripteur) }}"
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('prescripteurs.edit', $prescripteur) }}"
                                               class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('prescripteurs.toggle-active', $prescripteur) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-outline-{{ $prescripteur->active ? 'warning' : 'success' }}"
                                                        title="{{ $prescripteur->active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $prescripteur->active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            @if($prescripteur->commandes()->count() === 0)
                                                <form action="{{ route('prescripteurs.destroy', $prescripteur) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce prescripteur ?')">
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
                        {{ $prescripteurs->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun prescripteur trouvé</h5>
                        <p class="text-muted">Commencez par créer un nouveau prescripteur.</p>
                        <a href="{{ route('prescripteurs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer un prescripteur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>