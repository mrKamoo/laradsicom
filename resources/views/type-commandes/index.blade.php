<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-tags me-2"></i>
                Types de Commandes
            </h2>
            <a href="{{ route('type-commandes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouveau type
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

        <!-- Tableau des types -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des types de commandes ({{ $types->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($types->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Couleur</th>
                                    <th>Commandes</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $type)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge me-2" style="background-color: {{ $type->couleur }}">
                                                    {{ $type->nom }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $type->description ?? 'Aucune description' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $type->couleur }}; border-radius: 3px; border: 1px solid #ddd;"></div>
                                                <code>{{ $type->couleur }}</code>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $type->commandes_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            @if($type->active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('type-commandes.show', $type) }}"
                                                   class="btn btn-outline-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('type-commandes.edit', $type) }}"
                                                   class="btn btn-outline-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('type-commandes.toggle', $type) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-outline-{{ $type->active ? 'warning' : 'success' }}"
                                                            title="{{ $type->active ? 'Désactiver' : 'Activer' }}">
                                                        <i class="fas fa-{{ $type->active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                @if($type->commandes_count == 0)
                                                    <form method="POST" action="{{ route('type-commandes.destroy', $type) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type ?')">
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
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun type de commande n'a été créé.</p>
                        <a href="{{ route('type-commandes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer le premier type
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>