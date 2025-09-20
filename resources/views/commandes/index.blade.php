<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-list me-2"></i>
                Liste des commandes
            </h2>
            <a href="{{ route('commandes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouvelle commande
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

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter me-2"></i>
                    Filtres
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('commandes.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="N°, objet, prescripteur...">
                        </div>
                        <div class="col-md-2">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="">Tous</option>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="type_commande" class="form-label">Type</label>
                            <select class="form-select" id="type_commande" name="type_commande">
                                <option value="">Tous</option>
                                @foreach($typesCommandes as $type)
                                    <option value="{{ $type->id }}" {{ request('type_commande') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgentes" name="urgentes" value="1"
                                       {{ request('urgentes') ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgentes">
                                    Urgentes uniquement
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>
                                    Filtrer
                                </button>
                                <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Commandes ({{ $commandes->total() }} résultat{{ $commandes->total() > 1 ? 's' : '' }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($commandes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Objet</th>
                                    <th>Prescripteur / Commune</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Montant</th>
                                    <th>Date demande</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commandes as $commande)
                                <tr class="{{ $commande->urgent ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($commande->urgent)
                                                <i class="fas fa-exclamation-triangle text-danger me-2" title="Urgent"></i>
                                            @endif
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-decoration-none fw-bold">
                                                {{ $commande->numero_commande }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ Str::limit($commande->objet, 40) }}
                                        </div>
                                        @if($commande->date_souhaitee && $commande->date_souhaitee->isPast())
                                            <small class="text-danger">
                                                <i class="fas fa-clock me-1"></i>
                                                Échéance dépassée ({{ $commande->date_souhaitee->format('d/m/Y') }})
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $commande->prescripteur->nom_complet }}</div>
                                        <small class="text-muted">{{ $commande->prescripteur->commune->nom }}</small>
                                        @if($commande->prescripteur->fonction)
                                            <br><small class="text-muted">{{ $commande->prescripteur->fonction }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $commande->typeCommande->couleur }}">
                                            {{ $commande->typeCommande->nom }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! $commande->statut_badge !!}
                                        <div class="small text-muted mt-1">
                                            {{ $commande->duree_traitement }} jour{{ $commande->duree_traitement > 1 ? 's' : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($commande->montant_final)
                                            <strong>{{ number_format($commande->montant_final, 2, ',', ' ') }} €</strong>
                                        @elseif($commande->montant_estime)
                                            <span class="text-muted">
                                                ~{{ number_format($commande->montant_estime, 2, ',', ' ') }} €
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $commande->date_demande->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $commande->gestionnaire->name }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('commandes.show', $commande) }}"
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(in_array($commande->statut, ['demande', 'devis']))
                                                <a href="{{ route('commandes.edit', $commande) }}"
                                                   class="btn btn-outline-secondary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer">
                        {{ $commandes->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune commande trouvée</h5>
                        <p class="text-muted">Modifiez vos filtres ou créez une nouvelle commande.</p>
                        <a href="{{ route('commandes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer une commande
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>