<div class="tab-pane fade" id="v-pills-config" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-cog me-2" style="color: #6c757d;"></i>
            Configuration générale
        </h3>
    </div>
    
    <form action="{{ route('cms.admin.settings.update', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST">
        @csrf
        
        <div class="config-sections">
            <div class="config-group">
                <h4>Informations générales</h4>
                <div class="config-item">
                    <label class="config-label">Nom du site</label>
                    <input type="text" class="form-control" name="site_name" value="{{ $stats['etablissement']->getSetting('site_name', $stats['etablissement']->name ?? 'Mon site') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Slogan</label>
                    <input type="text" class="form-control" name="site_slogan" value="{{ $stats['etablissement']->getSetting('site_slogan', '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Description</label>
                    <textarea class="form-control" name="site_description" rows="3">{{ $stats['etablissement']->getSetting('site_description', '') }}</textarea>
                </div>
            </div>

            <div class="config-group">
    <h4>Identité visuelle</h4>
    <div class="config-item">
        <label>Logo du site</label>
        <input type="file" name="site_logo" accept="image/*">
        @if($logo = $stats['etablissement']->getSetting('site_logo'))
            <img src="{{ Storage::url($logo) }}" width="150">
        @endif
    </div>
    <div class="config-item mt-3">
        <label>Favicon</label>
        <input type="file" name="site_favicon" accept="image/x-icon,image/png">
    </div>
</div>
            
            <div class="config-group mt-4">
                <h4>Email et notifications</h4>
                <div class="config-item">
                    <label class="config-label">Email de contact</label>
                    <input type="email" class="form-control" name="contact_email" value="{{ $stats['etablissement']->getSetting('contact_email', $stats['etablissement']->email_contact ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Email de notification</label>
                    <input type="email" class="form-control" name="notification_email" value="{{ $stats['etablissement']->getSetting('notification_email', '') }}">
                </div>
            </div>
            
            <div class="config-group mt-4">
                <h4>Localisation</h4>
                <div class="config-item">
                    <label class="config-label">Adresse</label>
                    <input type="text" class="form-control" name="address" value="{{ $stats['etablissement']->getSetting('address', $stats['etablissement']->adresse ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Code postal</label>
                    <input type="text" class="form-control" name="zip_code" value="{{ $stats['etablissement']->getSetting('zip_code', $stats['etablissement']->zip_code ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Ville</label>
                    <input type="text" class="form-control" name="city" value="{{ $stats['etablissement']->getSetting('city', $stats['etablissement']->ville ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Téléphone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $stats['etablissement']->getSetting('phone', $stats['etablissement']->phone ?? '') }}">
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Sauvegarder la configuration
            </button>
        </div>
    </form>
</div>