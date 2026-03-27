<div class="modal fade" id="uploadThemeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cms.admin.themes.store', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Uploader un thème</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="theme_name" class="form-label">Nom du thème</label>
                        <input type="text" class="form-control" name="name" id="theme_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="theme_file" class="form-label">Fichier ZIP du thème</label>
                        <input type="file" class="form-control" name="theme_file" id="theme_file" accept=".zip" required>
                        <small class="text-muted">Le ZIP doit contenir layout.blade.php et un dossier assets/</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>