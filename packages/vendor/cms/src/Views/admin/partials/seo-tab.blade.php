<div class="tab-pane fade" id="v-pills-seo" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-search me-2" style="color: #06d6a0;"></i>
            Optimisation SEO
        </h3>
    </div>
    
    <form action="{{ route('cms.admin.settings.update', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST">
        @csrf
        
        <div class="seo-sections">
            <div class="config-group">
                <h4>Métadonnées globales</h4>
                <div class="config-item">
                    <label class="config-label">Titre par défaut</label>
                    <input type="text" class="form-control" name="seo_title" value="{{ $stats['etablissement']->getSetting('seo_title', '') }}" placeholder="Titre du site">
                    <small class="text-muted">Titre affiché dans les résultats de recherche (50-60 caractères)</small>
                    <div class="char-counter mt-1" id="seoTitleCounter">0/60 caractères</div>
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Description par défaut</label>
                    <textarea class="form-control" name="seo_description" rows="3" id="seoDescription" placeholder="Description du site">{{ $stats['etablissement']->getSetting('seo_description', '') }}</textarea>
                    <small class="text-muted">Description affichée dans les résultats de recherche (150-160 caractères)</small>
                    <div class="char-counter mt-1" id="seoDescCounter">0/160 caractères</div>
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Mots-clés</label>
                    <input type="text" class="form-control" name="seo_keywords" value="{{ $stats['etablissement']->getSetting('seo_keywords', '') }}" placeholder="mot-clé1, mot-clé2, mot-clé3">
                    <small class="text-muted">Séparés par des virgules</small>
                </div>
            </div>
            
            <div class="config-group mt-4">
                <h4>Google Analytics</h4>
                <div class="config-item">
                    <label class="config-label">ID de suivi (GA4)</label>
                    <input type="text" class="form-control" name="google_analytics_id" value="{{ $stats['etablissement']->getSetting('google_analytics_id', '') }}" placeholder="G-XXXXXXXX">
                    <small class="text-muted">Exemple: G-XXXXXXXX</small>
                </div>
            </div>
            
            <div class="config-group mt-4">
                <h4>Google Search Console</h4>
                <div class="config-item">
                    <label class="config-label">Code de vérification</label>
                    <input type="text" class="form-control" name="google_verification" value="{{ $stats['etablissement']->getSetting('google_verification', '') }}" placeholder="Code de vérification Google">
                    <small class="text-muted">Code fourni par Google Search Console</small>
                </div>
            </div>
            
            <div class="config-group mt-4">
                <h4>Bing Webmaster Tools</h4>
                <div class="config-item">
                    <label class="config-label">Code de vérification Bing</label>
                    <input type="text" class="form-control" name="bing_verification" value="{{ $stats['etablissement']->getSetting('bing_verification', '') }}" placeholder="Code de vérification Bing">
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Sauvegarder
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const seoTitle = document.querySelector('#v-pills-seo input[name="seo_title"]');
    const seoDesc = document.querySelector('#v-pills-seo textarea[name="seo_description"]');
    const titleCounter = document.getElementById('seoTitleCounter');
    const descCounter = document.getElementById('seoDescCounter');
    
    if (seoTitle && titleCounter) {
        seoTitle.addEventListener('input', function() {
            const count = this.value.length;
            titleCounter.textContent = `${count}/60 caractères`;
            if (count > 60) titleCounter.style.color = '#ef4444';
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
});
</script>