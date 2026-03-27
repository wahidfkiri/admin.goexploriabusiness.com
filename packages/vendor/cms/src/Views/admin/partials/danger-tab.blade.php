<div class="tab-pane fade" id="v-pills-danger" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title text-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Zone de danger
        </h3>
    </div>
    
    <div class="danger-zone">
        <div class="danger-action">
            <div>
                <h5>Vider le cache du site</h5>
                <p class="text-muted">Supprime toutes les pages en cache pour forcer la régénération.</p>
            </div>
            <button class="btn btn-warning" onclick="clearCache({{ $stats['etablissement']->id }})">
                <i class="fas fa-trash-alt me-2"></i>Vider le cache
            </button>
        </div>
        
        <div class="danger-action">
            <div>
                <h5>Réinitialiser la configuration</h5>
                <p class="text-muted">Remet tous les paramètres de configuration à leurs valeurs par défaut.</p>
            </div>
            <button class="btn btn-warning" onclick="resetConfig({{ $stats['etablissement']->id }})">
                <i class="fas fa-undo-alt me-2"></i>Réinitialiser
            </button>
        </div>
        
        <div class="danger-action">
            <div>
                <h5>Supprimer toutes les pages</h5>
                <p class="text-muted">Cette action est irréversible. Toutes les pages seront définitivement supprimées.</p>
            </div>
            <button class="btn btn-danger" onclick="deleteAllPages({{ $stats['etablissement']->id }})">
                <i class="fas fa-trash me-2"></i>Supprimer toutes les pages
            </button>
        </div>
    </div>
</div>