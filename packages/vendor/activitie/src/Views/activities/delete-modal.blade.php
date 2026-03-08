<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal-content">
            <div class="modal-header modern-modal-header bg-danger text-white">
                <h5 class="modal-title modern-modal-title text-white">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                
                <div id="activityToDeleteInfo" class="d-none">
                    <!-- Activity info will be loaded here -->
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Si cette activité a des participants ou des réservations, elle ne pourra pas être supprimée.
                </div>
            </div>
            <div class="modal-footer modern-modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="deleteActivity()">
                    <span class="btn-text">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>