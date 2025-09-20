<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold h4 mb-0 text-dark">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                            <h5 class="card-title mb-0">Bienvenue, {{ Auth::user()->name }} !</h5>
                        </div>
                        <p class="card-text text-muted">
                            Vous êtes connecté à la plateforme de la Direction des Systèmes d'Information.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Service Infrastructures</h6>
                                        <p class="card-text small">Gestion des infrastructures IT</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Télécommunications</h6>
                                        <p class="card-text small">Réseaux et communications</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>