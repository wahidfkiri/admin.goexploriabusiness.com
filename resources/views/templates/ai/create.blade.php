{{-- resources/views/templates/ai/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Générer un Template avec IA')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-gradient-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-robot"></i> Générer un Template avec OpenAI
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Formulaire de génération -->
                        <form action="{{ route('ai.templates.generate') }}" method="POST" id="aiGenerateForm">
                            @csrf
                            
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">1. Description du Template</h5>
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-comment-dots"></i> Description détaillée *
                                    </label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="6" placeholder="Décrivez le template que vous souhaitez générer..." 
                                              required>{{ old('description') }}</textarea>
                                    <div class="form-text">
                                        Soyez le plus précis possible. Ex: "Une page d'accueil pour une agence digitale avec hero section, services, portfolio, et contact"
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-font"></i> Nom du Template
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="ex: Landing Page Agence Digitale">
                                    <div class="form-text">Laissez vide pour un nom auto-généré</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">2. Options de Design</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="style" class="form-label">Style</label>
                                            <select class="form-select" id="style" name="style">
                                                <option value="">Sélectionnez un style</option>
                                                @foreach($styles as $value => $label)
                                                    <option value="{{ $value }}" {{ old('style') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="layout" class="form-label">Type de Page</label>
                                            <select class="form-select" id="layout" name="layout">
                                                <option value="">Sélectionnez un type</option>
                                                @foreach($layouts as $value => $label)
                                                    <option value="{{ $value }}" {{ old('layout') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="framework" class="form-label">Framework CSS</label>
                                            <select class="form-select" id="framework" name="framework">
                                                <option value="">Sélectionnez un framework</option>
                                                @foreach($frameworks as $value => $label)
                                                    <option value="{{ $value }}" {{ old('framework') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="color_scheme" class="form-label">Palette de couleurs</label>
                                            <input type="text" class="form-control" id="color_scheme" name="color_scheme"
                                                   value="{{ old('color_scheme') }}" 
                                                   placeholder="ex: Bleu principal, blanc, gris clair">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">3. Options Avancées</h5>
                                
                                <div class="mb-3">
                                    <label for="generate_variants" class="form-label">
                                        <i class="fas fa-copy"></i> Générer des variantes
                                    </label>
                                    <select class="form-select" id="generate_variants" name="generate_variants">
                                        <option value="1" selected>1 variante (template principal)</option>
                                        <option value="2">2 variantes</option>
                                        <option value="3">3 variantes</option>
                                        <option value="4">4 variantes</option>
                                        <option value="5">5 variantes</option>
                                    </select>
                                    <div class="form-text">
                                        Attention: Plus de variantes = plus de tokens utilisés
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle"></i> Information
                                </h6>
                                <p class="mb-2">
                                    <strong>Coût estimé:</strong> ~0.10-0.50 USD par template (selon la complexité)
                                </p>
                                <p class="mb-0 small">
                                    La génération prend 10-30 secondes. L'IA utilisera GPT-4 pour créer un code HTML/CSS professionnel.
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                
                                <button type="submit" class="btn btn-primary btn-lg" id="generateBtn">
                                    <i class="fas fa-robot"></i> Générer avec OpenAI
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Exemples et aide -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-lightbulb"></i> Exemples de descriptions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Landing Page SaaS</h6>
                                    <p class="small text-muted mb-2">
                                        "Une page d'accueil pour une application SaaS avec hero section avec titre accrocheur, 
                                        grille de fonctionnalités, témoignages clients, tableau de prix, et formulaire de contact."
                                    </p>
                                    <button class="btn btn-sm btn-outline-primary w-100" 
                                            onclick="useExample(this)">
                                        Utiliser cet exemple
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Portfolio Créatif</h6>
                                    <p class="small text-muted mb-2">
                                        "Un portfolio pour un designer graphique avec header fixe, galerie de projets en masonry grid, 
                                        section about avec photo, compétences, et footer avec réseaux sociaux."
                                    </p>
                                    <button class="btn btn-sm btn-outline-primary w-100" 
                                            onclick="useExample(this)">
                                        Utiliser cet exemple
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Blog Minimaliste</h6>
                                    <p class="small text-muted mb-2">
                                        "Un blog minimaliste avec sidebar à gauche, liste d'articles avec images, titres, 
                                        extraits et dates, pagination, et recherche en header."
                                    </p>
                                    <button class="btn btn-sm btn-outline-primary w-100" 
                                            onclick="useExample(this)">
                                        Utiliser cet exemple
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Génération depuis URL -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-link"></i> Générer depuis une URL
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('ai.templates.generate-from-url') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">URL du site</label>
                                        <input type="url" class="form-control" name="url" 
                                               placeholder="https://example.com" required>
                                    </div>
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-magic"></i> Analyser et Générer
                                    </button>
                                </form>
                                <div class="mt-2 small text-muted">
                                    L'IA analysera le site et créera un template similaire.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h4 class="mb-3">Génération en cours...</h4>
                <p class="text-muted">
                    OpenAI génère votre template. Cela peut prendre 10-30 secondes.
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                </div>
                <p class="small text-muted">
                    <i class="fas fa-robot"></i> 
                    Modèle: GPT-4 | Tokens: ~2000-4000
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Exemples pré-remplis
const examples = {
    'Landing Page SaaS': `Une page d'accueil pour une application SaaS avec hero section avec titre accrocheur et sous-titre, grille de 3 fonctionnalités avec icônes, section témoignages clients avec avis et photos, tableau de prix avec 3 plans, et formulaire de contact avec nom, email et message. Design moderne avec gradients et animations subtiles.`,
    
    'Portfolio Créatif': `Un portfolio pour un designer graphique avec header fixe avec logo et navigation, hero section avec titre et phrase d'accroche, galerie de projets en masonry grid avec effet hover, section about avec photo et description personnelle, grille de compétences avec barres de progression, et footer avec liens réseaux sociaux. Design créatif avec couleurs vives.`,
    
    'Blog Minimaliste': `Un blog minimaliste avec sidebar à gauche avec recherche, catégories et archives, liste d'articles avec images en vignette, titres, extraits et dates de publication, pagination en bas, header avec logo et menu simple, et footer avec copyright. Design épuré avec beaucoup d'espace blanc.`
};

function useExample(button) {
    const exampleText = button.previousElementSibling.textContent;
    document.getElementById('description').value = exampleText;
    
    // Pré-remplir d'autres champs selon l'exemple
    if (exampleText.includes('SaaS')) {
        document.getElementById('layout').value = 'saas';
        document.getElementById('style').value = 'modern';
    } else if (exampleText.includes('Portfolio')) {
        document.getElementById('layout').value = 'portfolio';
        document.getElementById('style').value = 'creative';
    } else if (exampleText.includes('Blog')) {
        document.getElementById('layout').value = 'blog';
        document.getElementById('style').value = 'minimal';
    }
}

// Gestion du formulaire
document.getElementById('aiGenerateForm').addEventListener('submit', function(e) {
    const description = document.getElementById('description').value;
    
    if (description.length < 10) {
        e.preventDefault();
        alert('Veuillez fournir une description plus détaillée (au moins 10 caractères)');
        return false;
    }
    
    // Afficher le modal de chargement
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();
    
    // Désactiver le bouton
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
    
    return true;
});

// Compteur de caractères
document.getElementById('description').addEventListener('input', function() {
    const count = this.value.length;
    const counter = document.getElementById('charCounter') || (function() {
        const div = document.createElement('div');
        div.id = 'charCounter';
        div.className = 'form-text';
        this.parentNode.appendChild(div);
        return div;
    }).call(this);
    
    counter.textContent = `${count} caractères | Recommandé: 50-500 caractères`;
    
    if (count < 50) {
        counter.classList.add('text-warning');
        counter.classList.remove('text-success');
    } else {
        counter.classList.remove('text-warning');
        counter.classList.add('text-success');
    }
});
</script>
@endpush