<div class="tab-pane fade" id="v-pills-social" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-share-alt me-2" style="color: #45b7d1;"></i>
            Réseaux sociaux
        </h3>
    </div>
    
    <form action="{{ route('cms.admin.settings.update', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST">
        @csrf
        
        <div class="social-sections">
            <div class="social-group">
                <div class="social-icon-item">
                    <i class="fab fa-facebook-f" style="color: #1877f2; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Facebook</label>
                        <input type="url" class="form-control" name="facebook_url" value="{{ $stats['etablissement']->getSetting('facebook_url', '') }}" placeholder="https://facebook.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-twitter" style="color: #1da1f2; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Twitter / X</label>
                        <input type="url" class="form-control" name="twitter_url" value="{{ $stats['etablissement']->getSetting('twitter_url', '') }}" placeholder="https://twitter.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-instagram" style="color: #e4405f; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Instagram</label>
                        <input type="url" class="form-control" name="instagram_url" value="{{ $stats['etablissement']->getSetting('instagram_url', '') }}" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-linkedin-in" style="color: #0077b5; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">LinkedIn</label>
                        <input type="url" class="form-control" name="linkedin_url" value="{{ $stats['etablissement']->getSetting('linkedin_url', '') }}" placeholder="https://linkedin.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-youtube" style="color: #ff0000; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">YouTube</label>
                        <input type="url" class="form-control" name="youtube_url" value="{{ $stats['etablissement']->getSetting('youtube_url', '') }}" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-tiktok" style="color: #000000; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">TikTok</label>
                        <input type="url" class="form-control" name="tiktok_url" value="{{ $stats['etablissement']->getSetting('tiktok_url', '') }}" placeholder="https://tiktok.com/@...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-pinterest" style="color: #bd081c; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Pinterest</label>
                        <input type="url" class="form-control" name="pinterest_url" value="{{ $stats['etablissement']->getSetting('pinterest_url', '') }}" placeholder="https://pinterest.com/...">
                    </div>
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