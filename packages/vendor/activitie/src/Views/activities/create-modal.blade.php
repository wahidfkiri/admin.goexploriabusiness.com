<!-- Create Activity Modal -->
<div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal-content">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title">
                    <i class="fas fa-running me-2"></i>Nouvelle Activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <form id="createActivityForm">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="createActivityName" class="form-label-modern">Nom *</label>
                            <input type="text" class="form-control-modern" id="createActivityName" name="name" required
                                   oninput="generateSlugFromName()" placeholder="Nom de l'activité">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="createActivityCategorieId" class="form-label-modern">Catégorie *</label>
                            <select class="form-select-modern" id="createActivityCategorieId" name="categorie_id" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories ?? [] as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="createActivitySlug" class="form-label-modern">Slug *</label>
                            <div class="input-group">
                                <input type="text" class="form-control-modern" id="createActivitySlug" name="slug" readonly required>
                                <button type="button" class="btn btn-outline-secondary" onclick="checkSlugAvailability()">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted d-none" id="slugCheckingText">
                                    <i class="fas fa-spinner fa-spin"></i> Vérification du slug...
                                </small>
                                <small class="text-success d-none" id="slugAvailableText">
                                    <i class="fas fa-check-circle"></i> Slug disponible
                                </small>
                                <small class="text-danger d-none" id="slugUnavailableText">
                                    <i class="fas fa-times-circle"></i> Slug déjà utilisé
                                </small>
                                <small class="form-text text-muted">
                                    Le slug est généré automatiquement à partir du nom et sera utilisé dans l'URL.
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="createActivityIsActive" name="is_active" checked>
                                <label class="form-check-label" for="createActivityIsActive">Activer cette activité</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modern-modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="submitActivityBtn" onclick="storeActivity()" disabled>
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer l'activité
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>