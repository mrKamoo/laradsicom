<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-file-alt me-2"></i>
                Commande {{ $commande->numero_commande }}
            </h2>
            <div>
                @if(in_array($commande->statut, ['demande', 'devis']))
                    <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-outline-warning me-2">
                        <i class="fas fa-edit me-1"></i>
                        Modifier
                    </a>
                @endif
                <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Informations principales ---->
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Détails de la commande
                        </h5>
                        <div class="d-flex align-items-center">
                            @if($commande->urgent)
                                <span class="badge bg-danger me-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    URGENT
                                </span>
                            @endif
                            {!! $commande->statut_badge !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Prescripteur</label>
                                    <div>{{ $commande->prescripteur->identite_complete }}</div>
                                    <small class="text-muted">{{ $commande->prescripteur->commune->nom }}</small>
                                    @if($commande->prescripteur->fonction)
                                        <br><small class="text-muted">{{ $commande->prescripteur->fonction }}</small>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Type de commande</label>
                                    <div>
                                        <span class="badge" style="background-color: {{ $commande->typeCommande->couleur }}">
                                            {{ $commande->typeCommande->nom }}
                                        </span>
                                        <div class="small text-muted mt-1">{{ $commande->typeCommande->description }}</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Objet de la commande</label>
                                    <div>{{ $commande->objet }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description détaillée</label>
                                    <div class="border p-3 bg-light rounded">
                                        {{ $commande->description }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Détails de la demande</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Dates</label>
                                    <div>
                                        <strong>Demande :</strong> {{ $commande->date_demande->format('d/m/Y') }}<br>
                                        @if($commande->date_souhaitee)
                                            <strong>Souhaitée :</strong>
                                            <span class="{{ $commande->date_souhaitee->isPast() ? 'text-danger fw-bold' : '' }}">
                                                {{ $commande->date_souhaitee->format('d/m/Y') }}
                                                @if($commande->date_souhaitee->isPast())
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                @endif
                                            </span><br>
                                        @endif
                                        @if($commande->date_livraison)
                                            <strong>Livraison :</strong> {{ $commande->date_livraison->format('d/m/Y') }}<br>
                                        @endif
                                        @if($commande->date_cloture)
                                            <strong>Clôture :</strong> {{ $commande->date_cloture->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Montants</label>
                                    <div>
                                        @if($commande->montant_estime)
                                            <strong>Estimé :</strong> {{ number_format($commande->montant_estime, 2, ',', ' ') }} €<br>
                                        @endif
                                        @if($commande->montant_final)
                                            <strong>Final :</strong> {{ number_format($commande->montant_final, 2, ',', ' ') }} €
                                        @endif
                                    </div>
                                </div>

                                @if($commande->imputation_budgetaire)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Imputation budgétaire</label>
                                        <div>{{ $commande->imputation_budgetaire }}</div>
                                    </div>
                                @endif

                                @if($commande->numero_bon_commande)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Numéro de bon de commande</label>
                                        <div>{{ $commande->numero_bon_commande }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($commande->justification)
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Justification</h6>
                                <div class="border p-3 bg-light rounded">
                                    {{ $commande->justification }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Devis -->
                @if($commande->devis->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Devis ({{ $commande->devis->count() }})
                            </h5>
                            @if(in_array($commande->statut, ['devis', 'validation']))
                                <a href="{{ route('devis.create', ['commande' => $commande->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Nouveau devis
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fournisseur</th>
                                            <th>Date</th>
                                            <th>Montant HT</th>
                                            <th>Montant TTC</th>
                                            <th>Validité</th>
                                            <th>Sélectionné</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commande->devis as $devis)
                                        <tr class="{{ $devis->selectionne ? 'table-success' : '' }}">
                                            <td>
                                                <div class="fw-bold">{{ $devis->fournisseur->nom }}</div>
                                                <small class="text-muted">{{ $devis->fournisseur->email }}</small>
                                            </td>
                                            <td>{{ $devis->date_devis->format('d/m/Y') }}</td>
                                            <td>{{ number_format($devis->montant_ht, 2, ',', ' ') }} €</td>
                                            <td>{{ number_format($devis->montant_ttc, 2, ',', ' ') }} €</td>
                                            <td>
                                                @if($devis->date_validite)
                                                    <span class="{{ $devis->date_validite->isPast() ? 'text-danger' : 'text-success' }}">
                                                        {{ $devis->date_validite->format('d/m/Y') }}
                                                        @if($devis->date_validite->isPast())
                                                            <i class="fas fa-times-circle"></i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($devis->selectionne)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Sélectionné
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('devis.show', $devis) }}" class="btn btn-outline-primary" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(in_array($commande->statut, ['devis', 'validation']))
                                                        <a href="{{ route('devis.edit', $devis) }}" class="btn btn-outline-secondary" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if(!$devis->selectionne && $commande->statut == 'validation')
                                                            <form action="{{ route('devis.select', $devis) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-outline-success" title="Sélectionner">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($commande->notes)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-sticky-note me-2"></i>
                                Notes de suivi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="border p-3 bg-light rounded">
                                {!! nl2br(e($commande->notes)) !!}
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
                        @if($commande->statut == 'demande')
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="devis">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Passer en "En attente de devis"
                                </button>
                            </form>
                        @endif

                        @if($commande->statut == 'devis' && $commande->devis->count() > 0)
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="validation">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Passer en "En attente de validation"
                                </button>
                            </form>
                        @endif

                        @if($commande->statut == 'validation' && $commande->devisRetenu)
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="commande">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Émettre le bon de commande
                                </button>
                            </form>
                        @endif

                        @if($commande->statut == 'commande')
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="livraison">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Marquer en cours de livraison
                                </button>
                            </form>
                        @endif

                        @if($commande->statut == 'livraison')
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="cloture">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-1"></i>
                                    Clôturer la commande
                                </button>
                            </form>
                        @endif

                        @if($commande->devis->count() == 0 && in_array($commande->statut, ['devis', 'validation']))
                            <a href="{{ route('devis.create', ['commande' => $commande->id]) }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-plus me-1"></i>
                                Ajouter un devis
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Informations système -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Informations système
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Numéro de commande</label>
                            <div>{{ $commande->numero_commande }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Gestionnaire</label>
                            <div>{{ $commande->gestionnaire->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <div>{{ $commande->statut_label }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Durée de traitement</label>
                            <div>{{ $commande->duree_traitement }} jour{{ $commande->duree_traitement > 1 ? 's' : '' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dates</label>
                            <div>
                                <strong>Créée :</strong> {{ $commande->created_at->format('d/m/Y à H:i') }}<br>
                                <strong>Modifiée :</strong> {{ $commande->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour changement de statut avec notes -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changement de statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                      placeholder="Ajoutez une note sur ce changement de statut..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>