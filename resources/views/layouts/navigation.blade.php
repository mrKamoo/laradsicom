<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="https://www.agglobeziers.fr/wp-content/uploads/2022/08/cropped-LOGO_AGGLO_20_VersionPaysage_200.png"
                 alt="Logo Agglo Béziers"
                 class="img-fluid me-2"
                 style="max-height: 40px;">
        </a>

        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-line me-1"></i>
                        Tableau de bord
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('commandes.*') ? 'active fw-bold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-shopping-cart me-1"></i>
                        Commandes
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('commandes.index') }}">
                                <i class="fas fa-list me-2"></i>
                                Toutes les commandes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('commandes.create') }}">
                                <i class="fas fa-plus me-2"></i>
                                Nouvelle commande
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('commandes.index', ['statut' => 'demande']) }}">
                                <i class="fas fa-inbox me-2"></i>
                                Demandes reçues
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('commandes.index', ['statut' => 'devis']) }}">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                En attente de devis
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('commandes.index', ['urgentes' => '1']) }}">
                                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                Commandes urgentes
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs(['prescripteurs.*', 'communes.*', 'fournisseurs.*']) ? 'active fw-bold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>
                        Gestion
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('prescripteurs.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Prescripteurs
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('communes.index') }}">
                                <i class="fas fa-building me-2"></i>
                                Communes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('fournisseurs.index') }}">
                                <i class="fas fa-truck me-2"></i>
                                Fournisseurs
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('prescripteurs.create') }}">
                                <i class="fas fa-user-plus me-2"></i>
                                Nouveau prescripteur
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('communes.create') }}">
                                <i class="fas fa-plus me-2"></i>
                                Nouvelle commune
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('fournisseurs.create') }}">
                                <i class="fas fa-truck me-2"></i>
                                Nouveau fournisseur
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Se déconnecter
                            </button>
                        </form>
                    </li>
                      <li>
                            Version : {{ config('app.version') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>