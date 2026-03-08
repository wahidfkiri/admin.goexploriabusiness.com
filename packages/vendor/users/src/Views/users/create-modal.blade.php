<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createUserName" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control" id="createUserName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="createUserEmail" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="createUserEmail" name="email" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="createUserPassword" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" id="createUserPassword" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="createUserPasswordConfirmation" class="form-label">Confirmation *</label>
                            <input type="password" class="form-control" id="createUserPasswordConfirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="createUserIsActive" name="is_active" checked>
                            <label class="form-check-label" for="createUserIsActive">Utilisateur actif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="submitUserBtn">
                        <i class="fas fa-save me-2"></i>Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>