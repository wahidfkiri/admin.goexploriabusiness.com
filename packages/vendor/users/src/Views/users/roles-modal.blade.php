<div class="modal fade" id="rolesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-shield me-2"></i>Gestion des Rôles
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="userRolesId">
                <div class="mb-3">
                    <p>Utilisateur : <strong id="userRolesName"></strong></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Sélectionnez les rôles :</label>
                    <div id="rolesContainer">
                        <!-- Roles will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateRolesBtn">
                    <i class="fas fa-save me-2"></i>Mettre à jour les rôles
                </button>
            </div>
        </div>
    </div>
</div>