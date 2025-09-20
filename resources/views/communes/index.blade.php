<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-building me-2"></i>
                Gestion des Communes
            </h2>
            <a href="{{ route('communes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouvelle commune
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
                <form method="GET" action="{{ route('communes.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nom, code postal, code INSEE...">
                    </div>
                    <div class="col-md-3">
                        <label for="active" class="form-label">Statut</label>
                        <select class="form-select" id="active" name="active">
                            <option value="">Toutes</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-5 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            Rechercher
                        </button>
                        <a href="{{ route('communes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des communes -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des communes ({{ $communes->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($communes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Code postal</th>
                                    <th>Code INSEE</th>
                                    <th>Contact</th>
                                    <th>Prescripteurs</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($communes as $commune)
                                <tr class="{{ !$commune->active ? 'table-light text-muted' : '' }}">
                                    <td>
                                        <div class="fw-bold">{{ $commune->nom }}</div>
                                        @if($commune->adresse)
                                            <small class="text-muted">{{ Str::limit($commune->adresse, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $commune->code_postal ?? '-' }}</td>
                                    <td>{{ $commune->code_insee ?? '-' }}</td>
                                    <td>
                                        @if($commune->email)
                                            <div><i class="fas fa-envelope me-1"></i>{{ $commune->email }}</div>
                                        @endif
                                        @if($commune->telephone)
                                            <div><i class="fas fa-phone me-1"></i>{{ $commune->telephone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $commune->prescripteurs_count }}</span>
                                    </td>
                                    <td>
                                        @if($commune->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('communes.show', $commune) }}"
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('communes.edit', $commune) }}"
                                               class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('communes.toggle-active', $commune) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-outline-{{ $commune->active ? 'warning' : 'success' }}"
                                                        title="{{ $commune->active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $commune->active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            @if($commune->prescripteurs()->count() === 0)
                                                <form action="{{ route('communes.destroy', $commune) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commune ?')">
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
                        {{ $communes->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune commune trouvée</h5>
                        <p class="text-muted">Commencez par créer une nouvelle commune.</p>
                        <a href="{{ route('communes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer une commune
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>