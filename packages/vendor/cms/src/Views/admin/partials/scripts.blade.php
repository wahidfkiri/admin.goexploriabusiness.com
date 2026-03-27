<script>
// Fonctions globales pour l'administration CMS
function showLoading() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner-modern">
                <div class="spinner"></div>
                <p>Chargement...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
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
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Styles pour les notifications
if (!document.querySelector('#cms-toast-styles')) {
    const styles = document.createElement('style');
    styles.id = 'cms-toast-styles';
    styles.textContent = `
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .spinner-modern {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: #4361ee;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
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
    `;
    document.head.appendChild(styles);
}
</script>