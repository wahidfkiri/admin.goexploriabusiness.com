<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4 class="delete-title">Confirmer la suppression</h4>
                <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette ville ?</p>
                
                <div class="ville-to-delete" id="villeToDeleteInfo">
                    <!-- Ville info will be loaded here -->
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible.
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDeleteBtn">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="btn-text">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>