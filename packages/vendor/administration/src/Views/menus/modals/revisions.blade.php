{{-- resources/views/admin/menus/modals/revisions.blade.php --}}
<div class="modal fade" id="revisionsModal" tabindex="-1" aria-labelledby="revisionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revisionsModalLabel">
                    <i class="fas fa-history me-2"></i>Historique des révisions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="revisions-container">
                    <!-- Liste des révisions sera chargée ici -->
                    <div id="revisionsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des révisions...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Comparaison de révisions -->
                <div class="revision-comparison mt-4" style="display: none;">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-code-compare me-2"></i>Comparaison
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <select class="form-select form-select-sm" id="compareRevision1">
                                        <option value="">Sélectionner une révision...</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <pre id="compareContent1" class="bg-light p-3" style="height: 300px; overflow: auto;"></pre>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <select class="form-select form-select-sm" id="compareRevision2">
                                        <option value="">Sélectionner une révision...</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <pre id="compareContent2" class="bg-light p-3" style="height: 300px; overflow: auto;"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-secondary" id="hideComparisonBtn">
                            <i class="fas fa-times me-2"></i>Masquer la comparaison
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="showComparisonBtn">
                    <i class="fas fa-code-compare me-2"></i>Comparer des révisions
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
    .revisions-container {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .revision-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .revision-item:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    
    .revision-item.current {
        border-color: #28a745;
        background-color: #d4edda;
    }
    
    .revision-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .revision-version {
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .revision-date {
        font-size: 0.9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .revision-info {
        margin-bottom: 15px;
    }
    
    .revision-user {
        font-weight: 500;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .revision-description {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .revision-actions {
        display: flex;
        gap: 10px;
    }
    
    .revision-stats {
        font-size: 0.8rem;
        color: #6c757d;
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .revision-stat {
        display: flex;
        align-items: center;
        gap: 3px;
    }
    
    .revision-preview {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
    
    .revision-preview-content {
        max-height: 200px;
        overflow: auto;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const menuId = {{ $menu->id }};
        let revisions = [];
        
        // Charger les révisions
        loadRevisions();
        
        // Afficher/masquer la comparaison
        $('#showComparisonBtn').on('click', function() {
            $('.revision-comparison').show();
            $(this).hide();
            populateComparisonSelects();
        });
        
        $('#hideComparisonBtn').on('click', function() {
            $('.revision-comparison').hide();
            $('#showComparisonBtn').show();
        });
        
        // Gérer les événements des révisions
        $(document).on('click', '.preview-revision', function() {
            const revisionId = $(this).data('id');
            previewRevision(revisionId);
        });
        
        $(document).on('click', '.restore-revision', function() {
            const revisionId = $(this).data('id');
            restoreRevision(revisionId);
        });
        
        $(document).on('click', '.compare-revision', function() {
            const revisionId = $(this).data('id');
            $('.revision-comparison').show();
            $('#showComparisonBtn').hide();
            $('#compareRevision2').val(revisionId).trigger('change');
        });
        
        // Comparaison des révisions
        $('#compareRevision1, #compareRevision2').on('change', function() {
            const rev1Id = $('#compareRevision1').val();
            const rev2Id = $('#compareRevision2').val();
            
            if (rev1Id && rev2Id) {
                compareRevisions(rev1Id, rev2Id);
            }
        });
        
        function loadRevisions() {
            $.ajax({
                url: `/menus/${menuId}/revisions`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        revisions = response.data;
                        renderRevisionsList(revisions);
                        populateComparisonSelects();
                    }
                }
            });
        }
        
        function renderRevisionsList(revisions) {
            if (!revisions || revisions.length === 0) {
                $('#revisionsList').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5>Aucune révision disponible</h5>
                        <p class="text-muted">Les révisions apparaîtront ici après vos premières modifications.</p>
                    </div>
                `);
                return;
            }
            
            let html = '';
            const currentContent = editor.getHtml();
            
            revisions.forEach((revision, index) => {
                const isCurrent = revision.content === currentContent;
                const isPublished = revision.is_published_version;
                
                html += `
                    <div class="revision-item ${isCurrent ? 'current' : ''} ${isPublished ? 'published' : ''}">
                        <div class="revision-header">
                            <div class="revision-version">
                                <i class="fas fa-code-branch"></i>
                                ${revision.version}
                                ${isPublished ? '<span class="badge bg-success ms-2">Publiée</span>' : ''}
                                ${isCurrent ? '<span class="badge bg-primary ms-2">Actuelle</span>' : ''}
                            </div>
                            <div class="revision-date">
                                <i class="far fa-clock"></i>
                                ${revision.formatted_date}
                            </div>
                        </div>
                        
                        <div class="revision-info">
                            <div class="revision-user">
                                <i class="fas fa-user"></i>
                                ${revision.user_name}
                                ${revision.user_email ? `<small class="text-muted">(${revision.user_email})</small>` : ''}
                            </div>
                            <div class="revision-description">
                                ${revision.change_description || '<em>Modification sans description</em>'}
                            </div>
                        </div>
                        
                        <div class="revision-stats">
                            <div class="revision-stat">
                                <i class="fas fa-file-code"></i>
                                ${revision.content_size_formatted}
                            </div>
                            <div class="revision-stat">
                                <i class="fas fa-paint-brush"></i>
                                ${revision.style_size_formatted}
                            </div>
                            <div class="revision-stat">
                                <i class="fas fa-database"></i>
                                ${revision.total_size_formatted}
                            </div>
                        </div>
                        
                        <div class="revision-actions">
                            <button class="btn btn-sm btn-outline-primary preview-revision" data-id="${revision.id}">
                                <i class="fas fa-eye"></i> Prévisualiser
                            </button>
                            <button class="btn btn-sm btn-outline-success restore-revision" data-id="${revision.id}">
                                <i class="fas fa-history"></i> Restaurer
                            </button>
                            <button class="btn btn-sm btn-outline-info compare-revision" data-id="${revision.id}">
                                <i class="fas fa-code-compare"></i> Comparer
                            </button>
                        </div>
                    </div>
                `;
            });
            
            $('#revisionsList').html(html);
        }
        
        function populateComparisonSelects() {
            let options = '<option value="">Sélectionner une révision...</option>';
            
            revisions.forEach(revision => {
                options += `<option value="${revision.id}">${revision.version} - ${revision.formatted_date} - ${revision.user_name}</option>`;
            });
            
            $('#compareRevision1').html(options);
            $('#compareRevision2').html(options);
        }
        
        function previewRevision(revisionId) {
            const revision = revisions.find(r => r.id == revisionId);
            if (!revision) return;
            
            // Créer une fenêtre de prévisualisation
            const previewWindow = window.open('', '_blank');
            previewWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Prévisualisation: ${revision.version}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .preview-header { 
                            background: #f8f9fa; 
                            padding: 20px; 
                            border-radius: 8px;
                            margin-bottom: 20px;
                        }
                        ${revision.styles || ''}
                    </style>
                </head>
                <body>
                    <div class="preview-header">
                        <h4>Prévisualisation de la révision ${revision.version}</h4>
                        <p><strong>Date:</strong> ${revision.formatted_date_full}</p>
                        <p><strong>Auteur:</strong> ${revision.user_name}</p>
                        <p><strong>Description:</strong> ${revision.change_description || 'Non spécifiée'}</p>
                    </div>
                    ${revision.content || '<p>Aucun contenu</p>'}
                </body>
                </html>
            `);
            previewWindow.document.close();
        }
        
        function restoreRevision(revisionId) {
            if (!confirm('Êtes-vous sûr de vouloir restaurer cette révision ? Le contenu actuel sera remplacé.')) {
                return;
            }
            
            $.ajax({
                url: `/admin/menus/${menuId}/revisions/${revisionId}/restore`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('success', 'Révision restaurée avec succès');
                        $('#revisionsModal').modal('hide');
                        
                        // Recharger l'éditeur
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification('danger', response.message || 'Erreur lors de la restauration');
                    }
                }
            });
        }
        
        function compareRevisions(rev1Id, rev2Id) {
            const rev1 = revisions.find(r => r.id == rev1Id);
            const rev2 = revisions.find(r => r.id == rev2Id);
            
            if (!rev1 || !rev2) return;
            
            $('#compareContent1').text(rev1.content || '(vide)');
            $('#compareContent2').text(rev2.content || '(vide)');
            
            // Appliquer la coloration syntaxique simple
            applySimpleDiff($('#compareContent1'), $('#compareContent2'));
        }
        
        function applySimpleDiff(elem1, elem2) {
            const text1 = elem1.text();
            const text2 = elem2.text();
            
            // Simple comparaison ligne par ligne
            const lines1 = text1.split('\n');
            const lines2 = text2.split('\n');
            
            let html1 = '';
            let html2 = '';
            
            const maxLines = Math.max(lines1.length, lines2.length);
            
            for (let i = 0; i < maxLines; i++) {
                const line1 = lines1[i] || '';
                const line2 = lines2[i] || '';
                
                if (line1 === line2) {
                    html1 += `<div>${escapeHtml(line1)}</div>`;
                    html2 += `<div>${escapeHtml(line2)}</div>`;
                } else {
                    html1 += `<div class="bg-warning">${escapeHtml(line1)}</div>`;
                    html2 += `<div class="bg-warning">${escapeHtml(line2)}</div>`;
                }
            }
            
            elem1.html(html1);
            elem2.html(html2);
        }
        
        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    });
</script>