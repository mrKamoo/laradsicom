<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Connexion</h4>
        <p class="text-muted">Connectez-vous à votre compte</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">
                Se souvenir de moi
            </label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Se connecter
            </button>
        </div>

        <div class="text-center mt-3">
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        @if (Route::has('register'))
            <hr class="my-3">
            <div class="text-center">
                <span class="text-muted small">Pas encore de compte ?</span>
                <a href="{{ route('register') }}" class="text-decoration-none">
                    S'inscrire
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>