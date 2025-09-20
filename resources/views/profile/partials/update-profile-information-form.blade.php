<section>
    <div class="mb-4">
        <p class="text-muted small">
            Mettez à jour les informations de votre profil et votre adresse e-mail.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nom complet</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2">
                    <p class="mb-2">
                        <strong>Votre adresse e-mail n'est pas vérifiée.</strong>
                    </p>
                    <button form="send-verification" class="btn btn-outline-warning btn-sm">
                        Cliquez ici pour renvoyer l'e-mail de vérification
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2">
                            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                Enregistrer
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">
                    <i class="fas fa-check me-1"></i>
                    Enregistré avec succès
                </span>
            @endif
        </div>
    </form>
</section>