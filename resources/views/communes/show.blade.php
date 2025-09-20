<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-building me-2"></i>
                {{ $commune->nom }}
            </h2>
            <div>
                <a href="{{ route('communes.edit', $commune) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="{{ route('communes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
            </div>
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

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations de la commune
                        </h5>
                        @if($commune->active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nom</label>
                                    <div>{{ $commune->nom }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Code postal</label>
                                    <div>{{ $commune->code_postal ?? '-' }}</div>
                                </div>

                                @if($commune->code_insee)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Code INSEE</label>
                                        <div>{{ $commune->code_insee }}</div>
                                    </div>
                                @endif

                                @if($commune->adresse)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Adresse</label>
                                        <div class="border p-3 bg-light rounded">
                                            {{ $commune->adresse }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Coordonnées</h6>

                                @if($commune->telephone)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Téléphone</label>
                                        <div>
                                            <a href="tel:{{ $commune->telephone }}">{{ $commune->telephone }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if($commune->email)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email</label>
                                        <div>
                                            <a href="mailto:{{ $commune->email }}">{{ $commune->email }}</a>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Créée le</label>
                                    <div>{{ $commune->formatted_created_at }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Modifiée le</label>
                                    <div>{{ $commune->formatted_updated_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescripteurs -->
                @if($commune->prescripteurs->count() > 0)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-users me-2"></i>
                                Prescripteurs ({{ $commune->prescripteurs->count() }})
                            </h5>
                            <a href="{{ route('prescripteurs.create', ['commune' => $commune->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Nouveau prescripteur
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom complet</th>
                                            <th>Fonction</th>
                                            <th>Contact</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commune->prescripteurs as $prescripteur)
                                        <tr class="{{ !$prescripteur->active ? 'table-light text-muted' : '' }}">
                                            <td>
                                                <div class="fw-bold">{{ $prescripteur->nom_complet }}</div>
                                                @if($prescripteur->service)
                                                    <small class="text-muted">{{ $prescripteur->service }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $prescripteur->fonction ?? '-' }}</td>
                                            <td>
                                                @if($prescripteur->email)
                                                    <div><i class="fas fa-envelope me-1"></i>{{ $prescripteur->email }}</div>
                                                @endif
                                                @if($prescripteur->telephone)
                                                    <div><i class="fas fa-phone me-1"></i>{{ $prescripteur->telephone }}</div>
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
                                                <a href="{{ route('prescripteurs.show', $prescripteur) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun prescripteur</h5>
                            <p class="text-muted">Cette commune n'a pas encore de prescripteur.</p>
                            <a href="{{ route('prescripteurs.create', ['commune' => $commune->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Ajouter un prescripteur
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions rapides -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Actions rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('prescripteurs.create', ['commune' => $commune->id]) }}"
                           class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-user-plus me-1"></i>
                            Nouveau prescripteur
                        </a>

                        <form action="{{ route('communes.toggle-active', $commune) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $commune->active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $commune->active ? 'pause' : 'play' }} me-1"></i>
                                {{ $commune->active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        @if($commune->prescripteurs()->count() === 0)
                            <form action="{{ route('communes.destroy', $commune) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commune ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-1"></i>
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistiques
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total prescripteurs</label>
                            <div class="h4 text-primary">{{ $stats['prescripteurs_total'] }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Prescripteurs actifs</label>
                            <div class="h4 text-success">{{ $stats['prescripteurs_actifs'] }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Total commandes</label>
                            <div class="h4 text-info">{{ $stats['commandes_total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>