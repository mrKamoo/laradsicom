<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-user me-2"></i>
                {{ $prescripteur->nom_complet }}
            </h2>
            <div>
                <a href="{{ route('prescripteurs.edit', $prescripteur) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="{{ route('prescripteurs.index') }}" class="btn btn-outline-secondary">
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
                            Informations du prescripteur
                        </h5>
                        @if($prescripteur->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Identité</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nom complet</label>
                                    <div>{{ $prescripteur->nom_complet }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Commune</label>
                                    <div>{{ $prescripteur->commune->nom }}</div>
                                    @if($prescripteur->commune->code_postal)
                                        <small class="text-muted">{{ $prescripteur->commune->code_postal }}</small>
                                    @endif
                                </div>

                                @if($prescripteur->fonction)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Fonction</label>
                                        <div>{{ $prescripteur->fonction }}</div>
                                    </div>
                                @endif

                                @if($prescripteur->service)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Service</label>
                                        <div>{{ $prescripteur->service }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Coordonnées</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <div>
                                        <a href="mailto:{{ $prescripteur->email }}">{{ $prescripteur->email }}</a>
                                    </div>
                                </div>

                                @if($prescripteur->telephone)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Téléphone fixe</label>
                                        <div>
                                            <a href="tel:{{ $prescripteur->telephone }}">{{ $prescripteur->telephone }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if($prescripteur->telephone_mobile)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Téléphone mobile</label>
                                        <div>
                                            <a href="tel:{{ $prescripteur->telephone_mobile }}">{{ $prescripteur->telephone_mobile }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commandes récentes -->
                @if($prescripteur->commandes->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Commandes récentes ({{ $prescripteur->commandes->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Numéro</th>
                                            <th>Objet</th>
                                            <th>Type</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescripteur->commandes as $commande)
                                        <tr>
                                            <td>{{ $commande->numero_commande }}</td>
                                            <td>{{ Str::limit($commande->objet, 50) }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $commande->typeCommande->couleur }}">
                                                    {{ $commande->typeCommande->nom }}
                                                </span>
                                            </td>
                                            <td>{!! $commande->statut_badge !!}</td>
                                            <td>{{ $commande->created_at ? $commande->created_at->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <a href="{{ route('commandes.show', $commande) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('commandes.index', ['prescripteur' => $prescripteur->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-1"></i>
                                    Voir toutes les commandes
                                </a>
                            </div>
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
                        <a href="{{ route('commandes.create', ['prescripteur' => $prescripteur->id]) }}"
                           class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>
                            Nouvelle commande
                        </a>

                        <form action="{{ route('prescripteurs.toggle-active', $prescripteur) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $prescripteur->active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $prescripteur->active ? 'pause' : 'play' }} me-1"></i>
                                {{ $prescripteur->active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        @if($prescripteur->commandes()->count() === 0)
                            <form action="{{ route('prescripteurs.destroy', $prescripteur) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce prescripteur ?')">
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
                            <label class="form-label fw-bold">Total commandes</label>
                            <div class="h4 text-primary">{{ $prescripteur->commandes()->count() }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Commandes en cours</label>
                            <div class="h4 text-warning">{{ $prescripteur->commandes()->enCours()->count() }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Commandes clôturées</label>
                            <div class="h4 text-success">{{ $prescripteur->commandes()->parStatut('cloture')->count() }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Depuis</label>
                            <div>{{ $prescripteur->formatted_created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>