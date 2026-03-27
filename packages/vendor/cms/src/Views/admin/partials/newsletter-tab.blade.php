<div class="tab-pane fade" id="v-pills-newsletter" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-envelope-open-text me-2" style="color: #9b59b6;"></i>
            Newsletter
        </h3>
        <button class="btn btn-primary btn-sm" id="newCampaignBtn">
            <i class="fas fa-paper-plane me-1"></i>Nouvelle campagne
        </button>
    </div>
    
    <div class="stats-grid-mini">
        <div class="stat-mini-card">
            <div class="stat-mini-value">{{ $stats['subscribers_count'] ?? 0 }}</div>
            <div class="stat-mini-label">Abonnés</div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-value">{{ $stats['open_rate'] ?? 0 }}%</div>
            <div class="stat-mini-label">Taux d'ouverture</div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-value">{{ $stats['click_rate'] ?? 0 }}%</div>
            <div class="stat-mini-label">Taux de clic</div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-value">{{ $stats['unsubscribe_rate'] ?? 0 }}%</div>
            <div class="stat-mini-label">Taux de désabonnement</div>
        </div>
    </div>
    
    <div class="mt-4">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            La gestion complète de la newsletter sera disponible prochainement.
        </div>
    </div>
    
    <!-- Configuration newsletter -->
    <div class="config-group mt-4">
        <h4>Configuration</h4>
        <form action="{{ route('cms.admin.settings.update', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST">
            @csrf
            <div class="config-item">
                <label class="config-label">Activer la newsletter</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="newsletter_enabled" value="1" {{ ($stats['etablissement']->getSetting('newsletter_enabled', true)) ? 'checked' : '' }}>
                    <label class="form-check-label">Activer l'inscription à la newsletter</label>
                </div>
            </div>
            <div class="config-item mt-3">
                <label class="config-label">Double opt-in</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="newsletter_double_optin" value="1" {{ ($stats['etablissement']->getSetting('newsletter_double_optin', true)) ? 'checked' : '' }}>
                    <label class="form-check-label">Envoyer un email de confirmation</label>
                </div>
            </div>
            <div class="config-item mt-3">
                <label class="config-label">Clé API (Mailchimp/Sendinblue)</label>
                <input type="text" class="form-control" name="newsletter_api_key" value="{{ $stats['etablissement']->getSetting('newsletter_api_key', '') }}">
            </div>
            <div class="config-item mt-3">
                <label class="config-label">ID de la liste</label>
                <input type="text" class="form-control" name="newsletter_list_id" value="{{ $stats['etablissement']->getSetting('newsletter_list_id', '') }}">
            </div>
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>