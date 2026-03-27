<div class="tab-pane fade" id="v-pills-comments" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-comments me-2" style="color: #ffd166;"></i>
            Commentaires
        </h3>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="commentFilter" style="width: auto;">
                <option value="all">Tous</option>
                <option value="pending">En attente</option>
                <option value="approved">Approuvés</option>
                <option value="spam">Spam</option>
            </select>
            <button class="btn btn-outline-primary btn-sm" id="bulkApproveBtn" disabled>
                <i class="fas fa-check me-1"></i>Approuver sélection
            </button>
            <button class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                <i class="fas fa-trash me-1"></i>Supprimer sélection
            </button>
        </div>
    </div>
    
    <div class="comments-list">
        @php
            $comments = $stats['comments'] ?? [];
            $items = $comments instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator 
                ? $comments->items() 
                : (is_array($comments) ? $comments : ($comments ? $comments->toArray() : []));
        @endphp
        
        @forelse($items as $comment)
        <div class="comment-item" data-id="{{ is_array($comment) ? $comment['id'] : $comment->id }}">
            <div class="comment-checkbox">
                <input type="checkbox" class="comment-checkbox-item" value="{{ is_array($comment) ? $comment['id'] : $comment->id }}">
            </div>
            <div class="comment-avatar">
                @php
                    $userName = is_array($comment) ? ($comment['user_name'] ?? 'Anonyme') : ($comment->user_name ?? 'Anonyme');
                    $userAvatar = is_array($comment) ? ($comment['user_avatar'] ?? null) : ($comment->user_avatar ?? null);
                @endphp
                @if($userAvatar)
                    <img src="{{ $userAvatar }}" alt="{{ $userName }}">
                @else
                    <div class="avatar-placeholder">
                        {{ strtoupper(substr($userName, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="comment-content">
                <div class="comment-header">
                    <span class="comment-author">{{ $userName }}</span>
                    <span class="comment-date">
                        @php
                            $date = is_array($comment) ? ($comment['created_at'] ?? null) : ($comment->created_at ?? null);
                        @endphp
                        {{ $date ? \Carbon\Carbon::parse($date)->diffForHumans() : 'Date inconnue' }}
                    </span>
                    <span class="comment-status">
                        @php
                            $status = is_array($comment) ? ($comment['status'] ?? 'pending') : ($comment->status ?? 'pending');
                        @endphp
                        @if($status === 'approved')
                            <span class="badge bg-success">Approuvé</span>
                        @elseif($status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @else
                            <span class="badge bg-secondary">Spam</span>
                        @endif
                    </span>
                </div>
                <div class="comment-text">
                    {{ is_array($comment) ? ($comment['content'] ?? '') : ($comment->content ?? '') }}
                </div>
                <div class="comment-meta">
                    @php
                        $pageTitle = is_array($comment) ? ($comment['page_title'] ?? 'Page inconnue') : ($comment->page_title ?? 'Page inconnue');
                        $pageSlug = is_array($comment) ? ($comment['page_slug'] ?? '#') : ($comment->page_slug ?? '#');
                    @endphp
                    <span>Page: <a href="{{ route('cms.company.page', ['etablissementId' => $stats['etablissement']->id, 'slug' => $pageSlug]) }}" target="_blank">{{ $pageTitle }}</a></span>
                </div>
                <div class="comment-actions">
                    @if($status !== 'approved')
                        <button class="btn btn-sm btn-outline-success" onclick="approveComment({{ is_array($comment) ? $comment['id'] : $comment->id }})">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                    @endif
                    @if($status !== 'spam')
                        <button class="btn btn-sm btn-outline-warning" onclick="markAsSpam({{ is_array($comment) ? $comment['id'] : $comment->id }})">
                            <i class="fas fa-ban"></i> Spam
                        </button>
                    @endif
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteComment({{ is_array($comment) ? $comment['id'] : $comment->id }})">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state-small">
            <i class="fas fa-comments fa-4x mb-3" style="color: #ddd;"></i>
            <p>Aucun commentaire pour le moment</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination - CORRIGÉE -->
    @if(isset($stats['comments']) && $stats['comments'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="pagination-modern mt-4">
            {{ $stats['comments']->links() }}
        </div>
    @endif
</div>

<style>
.comment-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    position: relative;
}

.comment-item:hover {
    background: #e9ecef;
    transform: translateX(3px);
}

.comment-checkbox {
    flex-shrink: 0;
    padding-top: 15px;
}

.comment-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.comment-avatar {
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}

.comment-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.comment-author {
    font-weight: 600;
    color: #1e293b;
}

.comment-date {
    font-size: 0.8rem;
    color: #64748b;
}

.comment-text {
    margin-bottom: 10px;
    color: #334155;
    line-height: 1.5;
}

.comment-meta {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 10px;
}

.comment-meta a {
    color: #4361ee;
    text-decoration: none;
}

.comment-meta a:hover {
    text-decoration: underline;
}

.comment-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.empty-state-small {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.pagination-modern {
    margin-top: 30px;
    text-align: center;
}

.pagination-modern nav {
    display: inline-block;
}

@media (max-width: 768px) {
    .comment-item {
        flex-direction: column;
    }
    
    .comment-checkbox {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .comment-actions {
        flex-wrap: wrap;
    }
}
</style>

<script>
let selectedComments = [];

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des checkboxes
    const checkboxes = document.querySelectorAll('.comment-checkbox-item');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    function updateBulkButtons() {
        const checked = document.querySelectorAll('.comment-checkbox-item:checked');
        const hasChecked = checked.length > 0;
        
        if (bulkApproveBtn) bulkApproveBtn.disabled = !hasChecked;
        if (bulkDeleteBtn) bulkDeleteBtn.disabled = !hasChecked;
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButtons);
    });
    
    // Filtre des commentaires
    const filterSelect = document.getElementById('commentFilter');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const filter = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('comment_filter', filter);
            window.location.href = url.toString();
        });
        
        // Récupérer le filtre depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentFilter = urlParams.get('comment_filter');
        if (currentFilter) {
            filterSelect.value = currentFilter;
        }
    }
    
    // Bulk approve
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.comment-checkbox-item:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            if (ids.length === 0) return;
            
            if (confirm(`Approuver ${ids.length} commentaire(s) ?`)) {
                bulkAction('approve', ids);
            }
        });
    }
    
    // Bulk delete
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.comment-checkbox-item:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            
            if (ids.length === 0) return;
            
            if (confirm(`Supprimer ${ids.length} commentaire(s) ? Cette action est irréversible.`)) {
                bulkAction('delete', ids);
            }
        });
    }
});

function bulkAction(action, ids) {
    showLoading();
    
    fetch(`/admin/cms/${currentEtablissementId}/comments/bulk/${action}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Erreur lors de l\'opération', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de l\'opération', 'error');
    });
}

function approveComment(id) {
    if (!confirm('Approuver ce commentaire ?')) return;
    
    showLoading();
    
    fetch(`/admin/cms/${currentEtablissementId}/comments/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Commentaire approuvé', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de l\'opération', 'error');
    });
}

function markAsSpam(id) {
    if (!confirm('Marquer ce commentaire comme spam ?')) return;
    
    showLoading();
    
    fetch(`/admin/cms/${currentEtablissementId}/comments/${id}/spam`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Commentaire marqué comme spam', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de l\'opération', 'error');
    });
}

function deleteComment(id) {
    if (!confirm('Supprimer ce commentaire ? Cette action est irréversible.')) return;
    
    showLoading();
    
    fetch(`/admin/cms/${currentEtablissementId}/comments/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Commentaire supprimé', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de la suppression', 'error');
    });
}

// Helper functions
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

// Toast and loading styles
if (!document.querySelector('#comments-tab-styles')) {
    const styles = document.createElement('style');
    styles.id = 'comments-tab-styles';
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


<style>
.comment-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.comment-item:hover {
    background: #e9ecef;
}

.comment-avatar {
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}

.comment-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.comment-author {
    font-weight: 600;
    color: #1e293b;
}

.comment-date {
    font-size: 0.8rem;
    color: #64748b;
}

.comment-text {
    margin-bottom: 10px;
    color: #334155;
}

.comment-meta {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 10px;
}

.comment-actions {
    display: flex;
    gap: 10px;
}

@media (max-width: 768px) {
    .comment-item {
        flex-direction: column;
    }
    
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .comment-actions {
        flex-wrap: wrap;
    }
}
</style>
