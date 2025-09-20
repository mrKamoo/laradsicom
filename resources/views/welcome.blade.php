<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light text-dark d-flex align-items-center justify-content-center min-vh-100">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <img src="https://www.agglobeziers.fr/wp-content/uploads/2022/08/cropped-LOGO_AGGLO_20_VersionPaysage_200.png"
                         alt="Logo Agglo Béziers"
                         class="img-fluid mb-4"
                         style="max-height: 150px;">
                    <h1 class="display-6 fw-bold mb-2">Direction des Système d'Information</h1>
                    <p class="lead mb-4">Service Infrastructures et Télécommunication</p>

                    @if (Route::has('login'))
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">
                                    Accéder au tableau de bord
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                    Se connecter
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                        S'inscrire
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif

                    <div class="mt-5">
                        <p class="text-muted">
                            Propulsé par <strong>Laravel {{ Illuminate\Foundation\Application::VERSION }}</strong> et <strong>Bootstrap 5</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>