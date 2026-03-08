<!-- EDIT CATEGORY MODAL -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="editCategoryModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier la catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editCategoryId" name="id">
                    
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label-modern">Nom de la catégorie *</label>
                        <input type="text" class="form-control-modern" id="editCategoryName" name="name" required>
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
                        <label for="editCategoryDescription" class="form-label-modern">Description</label>
                        <textarea class="form-control-modern" id="editCategoryDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="editCategoryIsActive" name="is_active">
                            <label class="form-check-label" for="editCategoryIsActive">Catégorie active</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateCategoryBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>