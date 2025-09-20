<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-chart-line me-2"></i>
                Tableau de bord - Gestion des commandes
            </h2>
            <a href="{{ route('commandes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nouvelle commande
            </a>
        </div>
    </x-slot>

    <div class="container">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total commandes</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">En cours</h6>
                                <h3 class="mb-0">{{ $stats['en_cours'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Urgentes</h6>
                                <h3 class="mb-0">{{ $stats['urgentes'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Clôturées</h6>
                                <h3 class="mb-0">{{ $stats['cloturees'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Commandes récentes -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Commandes récentes
                        </h5>
                        <a href="{{ route('commandes.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>
                    <div class="card-body">
                        @if($commandesRecentes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° Commande</th>
                                            <th>Objet</th>
                                            <th>Prescripteur</th>
                                            <th>Type</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commandesRecentes as $commande)
                                        <tr>
                                            <td>
                                                <a href="{{ route('commandes.show', $commande) }}" class="text-decoration-none">
                                                    {{ $commande->numero_commande }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($commande->objet, 30) }}</td>
                                            <td>
                                                <small class="text-muted">{{ $commande->prescripteur->commune->nom }}</small><br>
                                                {{ $commande->prescripteur->nom_complet }}
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $commande->typeCommande->couleur }}">
                                                    {{ $commande->typeCommande->nom }}
                                                </span>
                                            </td>
                                            <td>{!! $commande->statut_badge !!}</td>
                                            <td>
                                                <small>{{ $commande->date_demande->format('d/m/Y') }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">Aucune commande récente</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Commandes urgentes -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Commandes urgentes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($commandesUrgentes->count() > 0)
                            @foreach($commandesUrgentes as $commande)
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-decoration-none">
                                                {{ $commande->numero_commande }}
                                            </a>
                                        </h6>
                                        <p class="mb-1 small">{{ Str::limit($commande->objet, 40) }}</p>
                                        <small class="text-muted">
                                            {{ $commande->prescripteur->commune->nom }} -
                                            @if($commande->date_souhaitee)
                                                Souhaité le {{ $commande->date_souhaitee->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="ms-2">
                                        {!! $commande->statut_badge !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">Aucune commande urgente</p>
                        @endif
                    </div>
                </div>

                <!-- Répartition par statut -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Répartition par statut
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statutsLabels = [
                                'demande' => 'Demandes reçues',
                                'devis' => 'En attente de devis',
                                'validation' => 'En attente de validation',
                                'commande' => 'Bons de commande émis',
                                'livraison' => 'En cours de livraison',
                                'cloture' => 'Clôturées'
                            ];
                        @endphp
                        @foreach($statutsLabels as $statut => $label)
                            @if(isset($statsParStatut[$statut]) && $statsParStatut[$statut] > 0)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small">{{ $label }}</span>
                                <span class="badge bg-secondary">{{ $statsParStatut[$statut] }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>