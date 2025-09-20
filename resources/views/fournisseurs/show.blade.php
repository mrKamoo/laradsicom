<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-truck me-2"></i>
                {{ $fournisseur->nom }}
            </h2>
            <div>
                <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
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
                            Informations du fournisseur
                        </h5>
                        @if($fournisseur->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nom commercial</label>
                                    <div>{{ $fournisseur->nom }}</div>
                                </div>

                                @if($fournisseur->raison_sociale)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Raison sociale</label>
                                        <div>{{ $fournisseur->raison_sociale }}</div>
                                    </div>
                                @endif

                                @if($fournisseur->siret)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">SIRET</label>
                                        <div>{{ $fournisseur->siret }}</div>
                                    </div>
                                @endif

                                @if($fournisseur->adresse || $fournisseur->ville)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Adresse</label>
                                        <div class="border p-3 bg-light rounded">
                                            @if($fournisseur->adresse)
                                                {{ $fournisseur->adresse }}<br>
                                            @endif
                                            @if($fournisseur->code_postal || $fournisseur->ville)
                                                {{ $fournisseur->code_postal }} {{ $fournisseur->ville }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Coordonnées</h6>

                                @if($fournisseur->email)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email principal</label>
                                        <div>
                                            <a href="mailto:{{ $fournisseur->email }}">{{ $fournisseur->email }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if($fournisseur->telephone)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Téléphone principal</label>
                                        <div>
                                            <a href="tel:{{ $fournisseur->telephone }}">{{ $fournisseur->telephone }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if($fournisseur->contact_commercial)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Contact commercial</label>
                                        <div>{{ $fournisseur->contact_commercial }}</div>
                                        @if($fournisseur->email_commercial)
                                            <small>
                                                <a href="mailto:{{ $fournisseur->email_commercial }}">{{ $fournisseur->email_commercial }}</a>
                                            </small>
                                        @endif
                                        @if($fournisseur->telephone_commercial)
                                            <br><small>
                                                <a href="tel:{{ $fournisseur->telephone_commercial }}">{{ $fournisseur->telephone_commercial }}</a>
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($fournisseur->notes)
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Notes</h6>
                                <div class="border p-3 bg-light rounded">
                                    {!! nl2br(e($fournisseur->notes)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Devis récents -->
                @if($fournisseur->devis->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Devis récents ({{ $fournisseur->devis->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Commande</th>
                                            <th>Date</th>
                                            <th>Montant TTC</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fournisseur->devis as $devis)
                                        <tr class="{{ $devis->selectionne ? 'table-success' : '' }}">
                                            <td>
                                                <div class="fw-bold">{{ $devis->commande->numero_commande }}</div>
                                                <small class="text-muted">{{ Str::limit($devis->commande->objet, 40) }}</small>
                                            </td>
                                            <td>{{ $devis->date_devis ? $devis->date_devis->format('d/m/Y') : '-' }}</td>
                                            <td>{{ number_format($devis->montant_ttc, 2, ',', ' ') }} €</td>
                                            <td>
                                                @if($devis->selectionne)
                                                    <span class="badge bg-success">Sélectionné</span>
                                                @elseif($devis->date_validite && $devis->date_validite->isPast())
                                                    <span class="badge bg-danger">Expiré</span>
                                                @else
                                                    <span class="badge bg-secondary">En attente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('devis.show', $devis) }}"
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
                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun devis</h5>
                            <p class="text-muted">Ce fournisseur n'a pas encore soumis de devis.</p>
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
                        <form action="{{ route('fournisseurs.toggle-active', $fournisseur) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $fournisseur->active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $fournisseur->active ? 'pause' : 'play' }} me-1"></i>
                                {{ $fournisseur->active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        @if($fournisseur->devis()->count() === 0)
                            <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
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
                            <label class="form-label fw-bold">Total devis</label>
                            <div class="h4 text-primary">{{ $stats['devis_total'] }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Devis sélectionnés</label>
                            <div class="h4 text-success">{{ $stats['devis_selectionnes'] }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Montant total devis</label>
                            <div class="h4 text-info">{{ number_format($stats['montant_total_devis'], 2, ',', ' ') }} €</div>
                        </div>

                        @if($stats['devis_total'] > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Taux de sélection</label>
                                <div class="h4 text-warning">
                                    {{ round(($stats['devis_selectionnes'] / $stats['devis_total']) * 100, 1) }}%
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>