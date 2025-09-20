<section>
    <div class="mb-4">
        <p class="text-muted small">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
        </p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="fas fa-trash me-1"></i>
        Supprimer le compte
    </button>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Êtes-vous sûr de vouloir supprimer votre compte ?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            <strong>Attention :</strong> Cette action est irréversible !
                        </div>

                        <p class="text-muted mb-4">
                            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Veuillez saisir votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe actuel</label>
                            <input id="password" name="password" type="password"
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                   placeholder="Saisissez votre mot de passe"
                                   required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Supprimer définitivement le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            // Ouvrir automatiquement le modal s'il y a des erreurs
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                modal.show();
            });
        </script>
    @endif
</section>