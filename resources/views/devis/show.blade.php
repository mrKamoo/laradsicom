<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold h4 mb-0 text-dark">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Devis {{ $devis->reference_devis }}
            </h2>
            <div>
                <a href="{{ route('devis.edit', $devis) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="{{ route('commandes.show', $devis->commande) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la commande
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

        <!-- Informations sur la commande -->
        <div class="alert alert-info" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading">Commande : {{ $devis->commande->numero_commande }}</h5>
                    <p class="mb-1"><strong>Objet :</strong> {{ $devis->commande->objet }}</p>
                    <p class="mb-1"><strong>Prescripteur :</strong> {{ $devis->commande->prescripteur->nom_complet }} ({{ $devis->commande->prescripteur->commune->nom }})</p>
                    <p class="mb-0"><strong>Statut de la commande :</strong> {!! $devis->commande->statut_badge !!}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Détails du devis -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Détails du devis
                        </h5>
                        <div>
                            @if($devis->selectionne)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    Sélectionné
                                </span>
                            @endif
                            @if($devis->date_validite && $devis->date_validite->isPast())
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Expiré
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Informations générales</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fournisseur</label>
                                    <div>
                                        <div class="fw-bold">{{ $devis->fournisseur->nom }}</div>
                                        <div>{{ $devis->fournisseur->adresse }}</div>
                                        <div>{{ $devis->fournisseur->code_postal }} {{ $devis->fournisseur->ville }}</div>
                                        @if($devis->fournisseur->telephone)
                                            <div><i class="fas fa-phone me-1"></i> {{ $devis->fournisseur->telephone }}</div>
                                        @endif
                                        @if($devis->fournisseur->email)
                                            <div><i class="fas fa-envelope me-1"></i> {{ $devis->fournisseur->email }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Référence du devis</label>
                                    <div>{{ $devis->reference_devis }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Dates</label>
                                    <div>
                                        <strong>Date du devis :</strong> {{ $devis->date_devis->format('d/m/Y') }}<br>
                                        @if($devis->date_validite)
                                            <strong>Validité :</strong>
                                            <span class="{{ $devis->date_validite->isPast() ? 'text-danger fw-bold' : 'text-success' }}">
                                                {{ $devis->date_validite->format('d/m/Y') }}
                                                @if($devis->date_validite->isPast())
                                                    <i class="fas fa-times-circle"></i> Expiré
                                                @else
                                                    <i class="fas fa-check-circle"></i> Valide
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($devis->delai_livraison)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Délai de livraison</label>
                                        <div>{{ $devis->delai_livraison }} jours ouvrés</div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Montants</h6>

                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="fw-bold">Montant HT :</td>
                                            <td class="text-end">{{ number_format($devis->montant_ht, 2, ',', ' ') }} €</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">TVA ({{ $devis->taux_tva }}%) :</td>
                                            <td class="text-end">{{ number_format($devis->montant_tva, 2, ',', ' ') }} €</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td class="fw-bold">Total TTC :</td>
                                            <td class="text-end fw-bold fs-5">{{ number_format($devis->montant_ttc, 2, ',', ' ') }} €</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        @if($devis->garanti)
                                            <span class="badge bg-success">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Garanti
                                            </span>
                                        @endif
                                        @if($devis->installation_incluse)
                                            <span class="badge bg-info">
                                                <i class="fas fa-tools me-1"></i>
                                                Installation incluse
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($devis->description)
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Description détaillée</h6>
                                <div class="border p-3 bg-light rounded">
                                    {!! nl2br(e($devis->description)) !!}
                                </div>
                            </div>
                        @endif

                        @if($devis->conditions_particulieres)
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Conditions particulières</h6>
                                <div class="border p-3 bg-light rounded">
                                    {!! nl2br(e($devis->conditions_particulieres)) !!}
                                </div>
                            </div>
                        @endif

                        @if($devis->fichier_devis)
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Pièce jointe</h6>
                                <div class="d-flex align-items-center p-3 border rounded bg-light mb-3">
                                    <div class="me-3">
                                        @php
                                            $extension = strtolower(pathinfo($devis->nom_fichier_original ?? $devis->fichier_devis, PATHINFO_EXTENSION));
                                            $isPdf = $extension === 'pdf';
                                        @endphp
                                        <i class="fas {{ $isPdf ? 'fa-file-pdf text-danger' : 'fa-file text-secondary' }} fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $devis->nom_fichier_original ?? 'Document' }}</h6>
                                        <small class="text-muted">
                                            Devis du fournisseur
                                            @if(!$isPdf)
                                                <span class="text-warning">(Aperçu non disponible - format {{ strtoupper($extension) }})</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ route('devis.download', $devis) }}" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-download me-1"></i>
                                            Télécharger
                                        </a>
                                        @if($isPdf)
                                            <button class="btn btn-outline-secondary btn-sm" onclick="togglePdfViewer()">
                                                <i class="fas fa-eye me-1"></i>
                                                Aperçu
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if($isPdf)
                                    <!-- Message informatif pour l'aperçu -->
                                    <div id="pdf-viewer" class="border rounded bg-white" style="display: none;">
                                        <div class="p-4 text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                            <h5>Aperçu du PDF</h5>
                                            <p class="text-muted mb-3">
                                                En raison des restrictions de sécurité des navigateurs, l'aperçu direct n'est pas disponible.
                                                Veuillez utiliser l'un des boutons ci-dessous pour consulter le document.
                                            </p>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                                <a href="{{ route('devis.view', $devis) }}" target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    Ouvrir dans un nouvel onglet
                                                </a>
                                                <a href="{{ route('devis.download', $devis) }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-download me-1"></i>
                                                    Télécharger
                                                </a>
                                                <button class="btn btn-outline-secondary" onclick="togglePdfViewer()">
                                                    <i class="fas fa-times me-1"></i>
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <hr>
                            <div>
                                <h6 class="fw-bold text-primary">Pièce jointe</h6>
                                <div class="p-3 border rounded bg-light text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun fichier attaché à ce devis.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comparaison avec d'autres devis -->
                @if($autresDevis->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-balance-scale me-2"></i>
                                Comparaison avec les autres devis ({{ $autresDevis->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fournisseur</th>
                                            <th>Montant HT</th>
                                            <th>Montant TTC</th>
                                            <th>Délai</th>
                                            <th>Validité</th>
                                            <th>Sélectionné</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Devis actuel -->
                                        <tr class="table-warning">
                                            <td class="fw-bold">{{ $devis->fournisseur->nom }} <small class="text-muted">(actuel)</small></td>
                                            <td>{{ number_format($devis->montant_ht, 2, ',', ' ') }} €</td>
                                            <td class="fw-bold">{{ number_format($devis->montant_ttc, 2, ',', ' ') }} €</td>
                                            <td>{{ $devis->delai_livraison ?? '-' }} j</td>
                                            <td>
                                                @if($devis->date_validite)
                                                    <span class="{{ $devis->date_validite->isPast() ? 'text-danger' : 'text-success' }}">
                                                        {{ $devis->date_validite->format('d/m') }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($devis->selectionne)
                                                    <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Autres devis -->
                                        @foreach($autresDevis as $autreDevis)
                                        <tr class="{{ $autreDevis->selectionne ? 'table-success' : '' }}">
                                            <td>
                                                <a href="{{ route('devis.show', $autreDevis) }}" class="text-decoration-none">
                                                    {{ $autreDevis->fournisseur->nom }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ number_format($autreDevis->montant_ht, 2, ',', ' ') }} €
                                                @if($autreDevis->montant_ht < $devis->montant_ht)
                                                    <i class="fas fa-arrow-down text-success" title="Moins cher"></i>
                                                @elseif($autreDevis->montant_ht > $devis->montant_ht)
                                                    <i class="fas fa-arrow-up text-danger" title="Plus cher"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="{{ $autreDevis->montant_ttc < $devis->montant_ttc ? 'text-success fw-bold' : ($autreDevis->montant_ttc > $devis->montant_ttc ? 'text-danger' : '') }}">
                                                    {{ number_format($autreDevis->montant_ttc, 2, ',', ' ') }} €
                                                </span>
                                            </td>
                                            <td>{{ $autreDevis->delai_livraison ?? '-' }} j</td>
                                            <td>
                                                @if($autreDevis->date_validite)
                                                    <span class="{{ $autreDevis->date_validite->isPast() ? 'text-danger' : 'text-success' }}">
                                                        {{ $autreDevis->date_validite->format('d/m') }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($autreDevis->selectionne)
                                                    <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions rapides -->
                @if(!$devis->selectionne && $devis->commande->statut == 'validation')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Sélection
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Ce devis peut être sélectionné pour cette commande.</p>
                            <form action="{{ route('devis.select', $devis) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Êtes-vous sûr de vouloir sélectionner ce devis ?')">
                                    <i class="fas fa-check me-1"></i>
                                    Sélectionner ce devis
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($devis->selectionne)
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Devis sélectionné
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">Ce devis a été sélectionné pour cette commande.</p>
                            @if($devis->date_selection)
                                <small class="text-muted">
                                    Sélectionné le {{ $devis->date_selection->format('d/m/Y à H:i') }}
                                </small>
                            @endif
                            @if($devis->commande->statut == 'validation')
                                <hr>
                                <form action="{{ route('devis.deselect', $devis) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Êtes-vous sûr de vouloir désélectionner ce devis ?')">
                                        <i class="fas fa-times me-1"></i>
                                        Désélectionner
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

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
                            <label class="form-label fw-bold">Contact commercial</label>
                            <div>
                                @if($devis->fournisseur->contact_commercial)
                                    {{ $devis->fournisseur->contact_commercial }}<br>
                                @endif
                                @if($devis->fournisseur->telephone_commercial)
                                    <i class="fas fa-phone me-1"></i> {{ $devis->fournisseur->telephone_commercial }}<br>
                                @endif
                                @if($devis->fournisseur->email_commercial)
                                    <i class="fas fa-envelope me-1"></i> {{ $devis->fournisseur->email_commercial }}
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <div>
                                @if($devis->selectionne)
                                    <span class="text-success fw-bold">Sélectionné</span>
                                @elseif($devis->date_validite && $devis->date_validite->isPast())
                                    <span class="text-danger fw-bold">Expiré</span>
                                @else
                                    <span class="text-info fw-bold">En attente</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dates</label>
                            <div>
                                <strong>Créé :</strong> {{ $devis->created_at->format('d/m/Y à H:i') }}<br>
                                <strong>Modifié :</strong> {{ $devis->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        @if($devis->date_validite)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Validité</label>
                                <div class="{{ $devis->date_validite->isPast() ? 'text-danger' : 'text-success' }}">
                                    @if($devis->date_validite->isPast())
                                        Expiré depuis {{ $devis->date_validite->diffForHumans() }}
                                    @else
                                        Expire {{ $devis->date_validite->diffForHumans() }}
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Actions supplémentaires -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('devis.edit', $devis) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Modifier le devis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePdfViewer() {
            const viewer = document.getElementById('pdf-viewer');

            if (viewer.style.display === 'none') {
                viewer.style.display = 'block';

                // Scroll vers le visualiseur
                setTimeout(() => {
                    viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            } else {
                viewer.style.display = 'none';
            }
        }
    </script>
</x-app-layout>