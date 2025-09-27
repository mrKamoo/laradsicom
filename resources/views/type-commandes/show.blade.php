<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-tag me-2"></i>
                Type : {{ $typeCommande->nom }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('type-commandes.edit', $typeCommande) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="{{ route('type-commandes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="row">
            <!-- Informations du type -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Nom:</dt>
                            <dd class="col-sm-8">
                                <span class="badge" style="background-color: {{ $typeCommande->couleur }}">
                                    {{ $typeCommande->nom }}
                                </span>
                            </dd>

                            <dt class="col-sm-4">Description:</dt>
                            <dd class="col-sm-8">{{ $typeCommande->description ?? 'Aucune description' }}</dd>

                            <dt class="col-sm-4">Couleur:</dt>
                            <dd class="col-sm-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $typeCommande->couleur }}; border-radius: 3px; border: 1px solid #ddd;"></div>
                                    <code>{{ $typeCommande->couleur }}</code>
                                </div>
                            </dd>

                            <dt class="col-sm-4">Statut:</dt>
                            <dd class="col-sm-8">
                                @if($typeCommande->active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Créé le:</dt>
                            <dd class="col-sm-8">{{ $typeCommande->created_at->format('d/m/Y à H:i') }}</dd>

                            <dt class="col-sm-4">Modifié le:</dt>
                            <dd class="col-sm-8">{{ $typeCommande->updated_at->format('d/m/Y à H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('type-commandes.toggle', $typeCommande) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $typeCommande->active ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $typeCommande->active ? 'pause' : 'play' }} me-1"></i>
                                    {{ $typeCommande->active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>

                            @if($typeCommande->commandes->count() == 0)
                                <form method="POST" action="{{ route('type-commandes.destroy', $typeCommande) }}"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type ?')">
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
                </div>
            </div>

            <!-- Commandes associées -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Commandes associées ({{ $typeCommande->commandes->count() }})
                        </h5>
                        @if($typeCommande->active)
                            <a href="{{ route('commandes.create', ['type_commande_id' => $typeCommande->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Nouvelle commande
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($typeCommande->commandes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Numéro</th>
                                            <th>Objet</th>
                                            <th>Prescripteur</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($typeCommande->commandes->take(10) as $commande)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('commandes.show', $commande) }}" class="text-decoration-none">
                                                        {{ $commande->numero_commande }}
                                                    </a>
                                                </td>
                                                <td>{{ Str::limit($commande->objet, 30) }}</td>
                                                <td>{{ $commande->prescripteur->nom ?? 'N/A' }}</td>
                                                <td>{!! $commande->statut_badge !!}</td>
                                                <td>{{ $commande->date_demande->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($commande->montant_final)
                                                        {{ number_format($commande->montant_final, 2) }} €
                                                    @elseif($commande->montant_estime)
                                                        ~{{ number_format($commande->montant_estime, 2) }} €
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($typeCommande->commandes->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('commandes.index', ['type_commande_id' => $typeCommande->id]) }}" class="btn btn-outline-primary">
                                        Voir toutes les commandes ({{ $typeCommande->commandes->count() }})
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune commande pour ce type.</p>
                                @if($typeCommande->active)
                                    <a href="{{ route('commandes.create', ['type_commande_id' => $typeCommande->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>
                                        Créer une commande
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>