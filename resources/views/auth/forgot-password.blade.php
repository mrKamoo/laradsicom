<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Mot de passe oublié</h4>
        <p class="text-muted small">
            Mot de passe oublié ? Pas de problème. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation qui vous permettra d'en choisir un nouveau.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Envoyer le lien de réinitialisation
            </button>
        </div>

        <hr class="my-3">
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none small">
                Retour à la connexion
            </a>
        </div>
    </form>
</x-guest-layout>