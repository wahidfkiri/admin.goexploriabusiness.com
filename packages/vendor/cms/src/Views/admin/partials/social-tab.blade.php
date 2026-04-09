<div class="tab-pane fade" id="v-pills-social" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-share-alt me-2" style="color: #45b7d1;"></i>
            Réseaux sociaux
        </h3>
    </div>
    
    <form id="socialForm">
        @csrf
        <input type="hidden" name="_method" value="POST">
        
        <div class="social-sections">
            <div class="social-group">
                <div class="social-icon-item">
                    <i class="fab fa-facebook-f" style="color: #1877f2; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Facebook</label>
                        <input type="url" class="form-control" name="facebook_url" value="{{ $stats['etablissement']->getSetting('facebook_url', '','social') }}" placeholder="https://facebook.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-twitter" style="color: #1da1f2; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Twitter / X</label>
                        <input type="url" class="form-control" name="twitter_url" value="{{ $stats['etablissement']->getSetting('twitter_url', '', 'social') }}" placeholder="https://twitter.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-instagram" style="color: #e4405f; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Instagram</label>
                        <input type="url" class="form-control" name="instagram_url" value="{{ $stats['etablissement']->getSetting('instagram_url', '', 'social') }}" placeholder="https://instagram.com/...">
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
                        <input type="url" class="form-control" name="youtube_url" value="{{ $stats['etablissement']->getSetting('youtube_url', '', 'social') }}" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-tiktok" style="color: #000000; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">TikTok</label>
                        <input type="url" class="form-control" name="tiktok_url" value="{{ $stats['etablissement']->getSetting('tiktok_url', '', 'social') }}" placeholder="https://tiktok.com/@...">
                    </div>
                </div>
            </div>
            
            <div class="social-group mt-3">
                <div class="social-icon-item">
                    <i class="fab fa-pinterest" style="color: #bd081c; font-size: 1.5rem;"></i>
                    <div class="flex-grow-1 ms-3">
                        <label class="config-label">Pinterest</label>
                        <input type="url" class="form-control" name="pinterest_url" value="{{ $stats['etablissement']->getSetting('pinterest_url', '', 'social') }}" placeholder="https://pinterest.com/...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary" id="saveSocialBtn">
                <i class="fas fa-save me-2"></i>Sauvegarder
            </button>
            <div class="spinner-border spinner-border-sm text-primary ms-2" id="socialLoading" style="display: none;" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    </form>
</div>

<style>
.social-icon-item {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #eef2f6;
}

.social-icon-item:hover {
    background: #ffffff;
    border-color: #e2e8f0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transform: translateX(4px);
}

.social-group .form-control {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.social-group .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 10000;
    min-width: 280px;
}
.toast-notification.show {
    transform: translateX(0);
}
.toast-content {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid;
    border-radius: 12px;
}
.toast-notification.success .toast-content {
    border-left-color: #10b981;
}
.toast-notification.success i {
    color: #10b981;
}
.toast-notification.error .toast-content {
    border-left-color: #ef4444;
}
.toast-notification.error i {
    color: #ef4444;
}

@media (max-width: 768px) {
    .social-icon-item {
        padding: 12px;
        flex-wrap: wrap;
    }
    
    .social-icon-item i {
        margin-bottom: 8px;
    }
    
    .flex-grow-1 {
        width: 100%;
        margin-left: 0 !important;
        margin-top: 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const etablissementId = {{ $stats['etablissement']->id }};
    const socialForm = document.getElementById('socialForm');
    
    if (socialForm) {
        socialForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveSocialBtn');
            const loading = document.getElementById('socialLoading');
            const formData = new FormData(this);
            
            saveBtn.disabled = true;
            loading.style.display = 'inline-block';
            
            try {
                const response = await fetch(`/admin/cms/${etablissementId}/settings`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                } else {
                    showToast(result.message || 'Erreur lors de la sauvegarde', 'error');
                }
            } catch (error) {
                console.error('Save error:', error);
                showToast('Erreur lors de la sauvegarde', 'error');
            } finally {
                saveBtn.disabled = false;
                loading.style.display = 'none';
            }
        });
    }
    
    // Validation des URLs des réseaux sociaux
    const socialInputs = document.querySelectorAll('#socialForm input[type="url"]');
    socialInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !isValidUrl(value)) {
                this.classList.add('is-invalid');
                showToast(`L'URL ${this.name} n'est pas valide`, 'error');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    function isValidUrl(string) {
        if (!string) return true;
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
    }
    
    // Styles for toast
    if (!document.querySelector('#social-toast-styles')) {
        const style = document.createElement('style');
        style.id = 'social-toast-styles';
        style.textContent = `
            .toast-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transform: translateX(400px);
                transition: transform 0.3s ease;
                z-index: 10000;
                min-width: 280px;
            }
            .toast-notification.show {
                transform: translateX(0);
            }
            .toast-content {
                padding: 16px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                border-left: 4px solid;
                border-radius: 12px;
            }
            .toast-notification.success .toast-content {
                border-left-color: #10b981;
            }
            .toast-notification.success i {
                color: #10b981;
            }
            .toast-notification.error .toast-content {
                border-left-color: #ef4444;
            }
            .toast-notification.error i {
                color: #ef4444;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>