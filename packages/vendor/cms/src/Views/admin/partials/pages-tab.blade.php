<div class="tab-pane fade" id="v-pills-pages" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-file-alt me-2" style="color: var(--accent-color);"></i>
            Gestion des pages
        </h3>
        <a href="{{ route('cms.admin.pages.create', ['etablissementId' => $stats['etablissement']->id]) }}" class="btn-create">
            <i class="fas fa-plus-circle"></i>
            <span>Nouvelle page</span>
        </a>
    </div>
    
    <div class="table-container-modern">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Slug</th>
                    <th>Statut</th>
                    <th>Visibilité</th>
                    <th>Modifié le</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['all_pages'] ?? [] as $page)
                <tr>
                    <td class="fw-semibold">
                        <i class="fas fa-file-alt me-2" style="color: var(--primary-color);"></i>
                        {{ $page->title }}
                    </td>
                    <td><code class="slug-code">{{ $page->slug }}</code></td>
                    <td>
                        @if($page->status === 'published')
                            <span class="status-badge published">
                                <i class="fas fa-check-circle"></i> Publiée
                            </span>
                        @else
                            <span class="status-badge draft">
                                <i class="fas fa-pen-fancy"></i> Brouillon
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($page->visibility === 'public')
                            <span class="visibility-badge public">
                                <i class="fas fa-eye"></i> Public
                            </span>
                        @elseif($page->visibility === 'private')
                            <span class="visibility-badge private">
                                <i class="fas fa-lock"></i> Privé
                            </span>
                        @else
                            <span class="visibility-badge password">
                                <i class="fas fa-key"></i> Protégé
                            </span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        <i class="far fa-clock me-1"></i>
                        {{ $page->updated_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="actions-cell">
                        <div class="action-buttons">
                            <!-- Aperçu -->
                            <a href="{{ route('cms.company.page', ['etablissementId' => $stats['etablissement']->id, 'slug' => $page->slug]) }}" 
                               class="action-btn preview" 
                               target="_blank"
                               title="Aperçu">
                                <i class="fas fa-eye"></i>
                                <span class="btn-tooltip">Aperçu</span>
                            </a>
                            
                            <!-- Éditer les informations -->
                            <a href="{{ route('cms.admin.pages.edit', ['etablissementId' => $stats['etablissement']->id, 'id' => $page->id]) }}" 
                               class="action-btn edit-info"
                               title="Modifier les informations">
                                <i class="fas fa-info-circle"></i>
                                <span class="btn-tooltip">Informations</span>
                            </a>
                            
                            <!-- Éditer le contenu -->
                            <a href="{{ route('cms.admin.pages.edit-content', ['etablissementId' => $stats['etablissement']->id, 'id' => $page->id]) }}" 
                               class="action-btn edit-content"
                               title="Éditer le contenu">
                                <i class="fas fa-edit"></i>
                                <span class="btn-tooltip">Contenu</span>
                            </a>
                            
                            <!-- Supprimer -->
                            <button class="action-btn delete" 
                                    onclick="deletePage({{ $page->id }})"
                                    title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                                <span class="btn-tooltip">Supprimer</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if(empty($stats['all_pages']))
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Aucune page créée</h4>
                        <p>Commencez par créer votre première page</p>
                        <a href="{{ route('cms.admin.pages.create', ['etablissementId' => $stats['etablissement']->id]) }}" class="btn-create">
                            <i class="fas fa-plus-circle"></i>
                            <span>Créer une page</span>
                        </a>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(isset($stats['all_pages']) && method_exists($stats['all_pages'], 'links'))
    <div class="pagination-modern mt-4">
        {{ $stats['all_pages']->links() }}
    </div>
    @endif
</div>

<style>
/* ============================================
   BOUTON CRÉATION
   ============================================ */
.btn-create {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    color: white;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(67, 97, 238, 0.2);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    color: white;
    text-decoration: none;
}

.btn-create i {
    font-size: 1rem;
}

/* ============================================
   BADGES STATUT
   ============================================ */
.status-badge, .visibility-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

.status-badge.published {
    background: #e6f7e6;
    color: #2e7d32;
}

.status-badge.draft {
    background: #fff3e0;
    color: #ed6c02;
}

.visibility-badge.public {
    background: #e3f2fd;
    color: #1976d2;
}

.visibility-badge.private {
    background: #f5f5f5;
    color: #616161;
}

.visibility-badge.password {
    background: #fff8e1;
    color: #f57c00;
}

/* ============================================
   BOUTONS D'ACTIONS
   ============================================ */
.actions-cell {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.action-btn {
    position: relative;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

.action-btn i {
    font-size: 0.9rem;
}

/* Aperçu */
.action-btn.preview:hover {
    background: #0dcaf0;
    border-color: #0dcaf0;
    color: white;
    box-shadow: 0 4px 10px rgba(13, 202, 240, 0.3);
}

/* Informations */
.action-btn.edit-info:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
    box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
}

/* Contenu */
.action-btn.edit-content:hover {
    background: #4361ee;
    border-color: #4361ee;
    color: white;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

/* Supprimer */
.action-btn.delete:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
}

/* Tooltip */
.btn-tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    margin-bottom: 8px;
    pointer-events: none;
}

.btn-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #1e293b transparent transparent transparent;
}

.action-btn:hover .btn-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-2px);
}

/* ============================================
   TABLE MODERNE
   ============================================ */
.table-container-modern {
    overflow-x: auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    padding: 16px 12px;
    background: #fafbfc;
    font-weight: 600;
    color: #1e293b;
    font-size: 0.85rem;
    border-bottom: 1px solid #eef2f6;
}

.modern-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.modern-table tr:hover {
    background: #fafbfc;
}

.fw-semibold {
    font-weight: 500;
}

.slug-code {
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    color: #4361ee;
}

.text-muted {
    color: #6c757d;
}

.text-center {
    text-align: center;
}

/* ============================================
   EMPTY STATE
   ============================================ */
.empty-state {
    text-align: center;
    padding: 60px 20px !important;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-icon i {
    font-size: 2.5rem;
    color: #94a3b8;
}

.empty-state h4 {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: #1e293b;
}

.empty-state p {
    color: #64748b;
    margin-bottom: 20px;
}

/* ============================================
   PAGINATION
   ============================================ */
.pagination-modern {
    margin-top: 20px;
    text-align: center;
}

.pagination-modern nav {
    display: inline-block;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 768px) {
    .action-buttons {
        gap: 5px;
    }
    
    .action-btn {
        width: 30px;
        height: 30px;
    }
    
    .action-btn i {
        font-size: 0.8rem;
    }
    
    .status-badge span, .visibility-badge span {
        display: none;
    }
    
    .status-badge, .visibility-badge {
        padding: 4px 8px;
    }
    
    .btn-tooltip {
        display: none;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 10px 8px;
    }
}
</style>

<script>
function deletePage(pageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette page ? Cette action est irréversible.')) {
        showLoading();
        
        fetch(`/admin/cms/${currentEtablissementId}/pages/${pageId}`, {
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
                showToast('Page supprimée avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Erreur lors de la suppression', 'error');
        });
    }
}

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
</script>