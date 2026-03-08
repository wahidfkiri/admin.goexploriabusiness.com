<!-- CREATE CATEGORY MODAL -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="createCategoryModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="createCategoryForm">
                    @csrf
                    <div class="mb-3">
                        <label for="createCategoryName" class="form-label-modern">Nom de la catégorie *</label>
                        <input type="text" class="form-control-modern" id="createCategoryName" name="name" 
                               placeholder="Ex: E-commerce, Portfolio, Blog..." required>
                        <div class="form-text-modern">Nom unique de la catégorie</div>
                    </div>
                    

                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label-modern">Type *</label>
                        <select class="form-control-modern" id="categorie_type_id" name="categorie_type_id" required>
                            @foreach($categorie_types as $type)
                            <option value="{{$type->id}}">{{ $type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="createCategoryDescription" class="form-label-modern">Description</label>
                        <textarea class="form-control-modern" id="createCategoryDescription" name="description" 
                                  rows="3" placeholder="Description de la catégorie..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="createCategoryIsActive" name="is_active" checked>
                            <label class="form-check-label" for="createCategoryIsActive">Catégorie active</label>
                        </div>
                        <div class="form-text-modern">Les catégories inactives ne seront pas disponibles pour l'assignation</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary btn-pulse" id="submitCategoryBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer la catégorie
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>