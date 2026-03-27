@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-file-alt"></i></span>
                Créer une nouvelle page
            </h1>
            <p class="page-description">Ajoutez une nouvelle page à votre site</p>
        </div>
        
        <div class="page-actions">
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $etablissement->id]) }}?section=pages" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="main-card-modern">
        <form id="pageForm" method="POST" action="{{ route('cms.admin.pages.store', ['etablissementId' => $etablissement->id]) }}">
            @csrf
            
            <div class="row">
                <!-- Colonne gauche - Informations de base -->
                <div class="col-md-6">
                    <div class="card-modern mb-4">
                        <div class="card-header-modern">
                            <h3><i class="fas fa-info-circle me-2"></i>Informations de base</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label for="title" class="form-label required">Titre de la page</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                <small class="text-muted">Le titre de la page affiché dans le navigateur et dans les menus</small>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ url('/company/' . $etablissement->id . '/page/') }}/</span>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" placeholder="titre-de-la-page">
                                </div>
                                <small class="text-muted">Laissez vide pour générer automatiquement depuis le titre</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Publication -->
                <div class="col-md-6">
                    <div class="card-modern mb-4">
                        <div class="card-header-modern">
                            <h3><i class="fas fa-globe me-2"></i>Publication</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publié</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="visibility" class="form-label">Visibilité</label>
                                <select class="form-select @error('visibility') is-invalid @enderror" name="visibility" id="visibility">
                                    <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Privé (nécessite connexion)</option>
                                    <option value="password" {{ old('visibility') == 'password' ? 'selected' : '' }}>Protégé par mot de passe</option>
                                </select>
                                @error('visibility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3" id="passwordField" style="display: none;">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" id="password" value="{{ old('password') }}">
                                <small class="text-muted">Laissez vide pour ne pas protéger la page</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="is_home" name="is_home" value="1" {{ old('is_home') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_home">
                                    Définir comme page d'accueil
                                </label>
                                <small class="text-muted d-block">Remplacera la page d'accueil actuelle</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="published_at" class="form-label">Date de publication</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                       name="published_at" id="published_at" value="{{ old('published_at') }}">
                                <small class="text-muted">Laissez vide pour utiliser la date actuelle</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Section - Pleine largeur -->
            <div class="row">
                <div class="col-12">
                    <div class="card-modern mb-4">
                        <div class="card-header-modern">
                            <h3><i class="fas fa-search me-2"></i>Optimisation SEO</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="seo_title" class="form-label">Titre SEO</label>
                                        <input type="text" class="form-control" id="seo_title" name="meta[seo_title]" 
                                               value="{{ old('meta.seo_title') }}" placeholder="{{ old('title', 'Titre de la page') }}">
                                        <small class="text-muted">Titre pour les moteurs de recherche (70 caractères max)</small>
                                        <div class="char-counter mt-1">0/70 caractères</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="seo_keywords" class="form-label">Mots-clés SEO</label>
                                        <input type="text" class="form-control" id="seo_keywords" name="meta[seo_keywords]" 
                                               value="{{ old('meta.seo_keywords') }}" placeholder="mot-clé1, mot-clé2, mot-clé3">
                                        <small class="text-muted">Séparés par des virgules</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="seo_description" class="form-label">Description SEO</label>
                                        <textarea class="form-control" id="seo_description" name="meta[seo_description]" 
                                                  rows="3" placeholder="Description pour les moteurs de recherche">{{ old('meta.seo_description') }}</textarea>
                                        <small class="text-muted">Description pour les moteurs de recherche (160 caractères max)</small>
                                        <div class="char-counter mt-1">0/160 caractères</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr>
                                    <div class="mb-3">
                                        <label for="og_image" class="form-label">Image Open Graph</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="og_image" name="meta[og_image]" 
                                                   value="{{ old('meta.og_image') }}" placeholder="URL de l'image">
                                            <button type="button" class="btn btn-outline-secondary" id="selectImageBtn">
                                                <i class="fas fa-image"></i> Choisir
                                            </button>
                                        </div>
                                        <small class="text-muted">Image pour le partage sur les réseaux sociaux</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="submit" class="btn btn-primary" id="savePageBtn">
                    <i class="fas fa-save me-2"></i>Créer la page
                </button>
                <button type="button" class="btn btn-outline-primary" id="saveAndPublishBtn">
                    <i class="fas fa-globe me-2"></i>Créer et publier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
}

.page-title-icon {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
}

.page-description {
    color: #64748b;
    margin: 5px 0 0 65px;
}

.main-card-modern {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.card-modern {
    background: #f8fafc;
    border-radius: 16px;
    overflow: hidden;
}

.card-header-modern {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header-modern h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.card-body-modern {
    padding: 20px;
}

.form-label {
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.form-label.required::after {
    content: '*';
    color: #ef4444;
    margin-left: 4px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 10px 12px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.char-counter {
    font-size: 0.75rem;
    color: #94a3b8;
    text-align: right;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.invalid-feedback {
    font-size: 0.8rem;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .main-card-modern {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            let slug = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });
    
    // Show/hide password field based on visibility
    const visibilitySelect = document.getElementById('visibility');
    const passwordField = document.getElementById('passwordField');
    
    visibilitySelect.addEventListener('change', function() {
        if (this.value === 'password') {
            passwordField.style.display = 'block';
        } else {
            passwordField.style.display = 'none';
        }
    });
    
    if (visibilitySelect.value === 'password') {
        passwordField.style.display = 'block';
    }
    
    // Character counters
    const seoTitle = document.getElementById('seo_title');
    const seoDesc = document.getElementById('seo_description');
    const titleCounter = seoTitle?.parentElement?.querySelector('.char-counter');
    const descCounter = seoDesc?.parentElement?.querySelector('.char-counter');
    
    if (seoTitle && titleCounter) {
        seoTitle.addEventListener('input', function() {
            const count = this.value.length;
            titleCounter.textContent = `${count}/70 caractères`;
            if (count > 70) titleCounter.style.color = '#ef4444';
            else titleCounter.style.color = '#94a3b8';
        });
        seoTitle.dispatchEvent(new Event('input'));
    }
    
    if (seoDesc && descCounter) {
        seoDesc.addEventListener('input', function() {
            const count = this.value.length;
            descCounter.textContent = `${count}/160 caractères`;
            if (count > 160) descCounter.style.color = '#ef4444';
            else descCounter.style.color = '#94a3b8';
        });
        seoDesc.dispatchEvent(new Event('input'));
    }
    
    // Form submission
    const form = document.getElementById('pageForm');
    const saveBtn = document.getElementById('savePageBtn');
    const saveAndPublishBtn = document.getElementById('saveAndPublishBtn');
    
    function submitForm(publish = false) {
        if (publish) {
            document.getElementById('status').value = 'published';
        }
        
        const formData = new FormData(form);
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création...';
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: data.message,
                    confirmButtonColor: '#4361ee'
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '{{ route("cms.admin.pages.index", ["etablissementId" => $etablissement->id]) }}';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message,
                    confirmButtonColor: '#4361ee'
                });
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer la page';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la création',
                confirmButtonColor: '#4361ee'
            });
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer la page';
        });
    }
    
    saveBtn.addEventListener('click', (e) => {
        e.preventDefault();
        submitForm(false);
    });
    
    if (saveAndPublishBtn) {
        saveAndPublishBtn.addEventListener('click', (e) => {
            e.preventDefault();
            submitForm(true);
        });
    }
});
</script>
@endsection