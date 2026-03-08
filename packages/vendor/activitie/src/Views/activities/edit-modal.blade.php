<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal-content">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier l'Activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <form id="editActivityForm">
                    <input type="hidden" id="editActivityId" name="id">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="editActivityName" class="form-label-modern">Nom *</label>
                            <input type="text" class="form-control-modern" id="editActivityName" name="name" required
                                   oninput="generateEditSlugFromName()" placeholder="Nom de l'activité">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="editActivityCategorieId" class="form-label-modern">Catégorie *</label>
                            <select class="form-select-modern" id="editActivityCategorieId" name="categorie_id" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories ?? [] as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="editActivitySlug" class="form-label-modern">Slug *</label>
                            <div class="input-group">
                                <input type="text" class="form-control-modern" id="editActivitySlug" name="slug" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="checkEditSlugAvailability()">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted d-none" id="editSlugCheckingText">
                                    <i class="fas fa-spinner fa-spin"></i> Vérification du slug...
                                </small>
                                <small class="text-success d-none" id="editSlugAvailableText">
                                    <i class="fas fa-check-circle"></i> Slug disponible
                                </small>
                                <small class="text-danger d-none" id="editSlugUnavailableText">
                                    <i class="fas fa-times-circle"></i> Slug déjà utilisé
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editActivityIsActive" name="is_active">
                                <label class="form-check-label" for="editActivityIsActive">Activer cette activité</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modern-modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="updateActivityBtn" onclick="updateActivity()">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>