{{-- resources/views/admin/menus/modals/seo.blade.php --}}
<div class="modal fade" id="seoModal" tabindex="-1" aria-labelledby="seoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seoModalLabel">
                    <i class="fas fa-search me-2"></i>Optimisation SEO
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="seoForm">
                    @csrf
                    
                    <!-- Prévisualisation Google -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Prévisualisation Google</h6>
                        <div class="google-preview">
                            <div class="google-preview-title" id="googlePreviewTitle">
                                {{ $menu->page_meta['title'] ?? $menu->title }}
                            </div>
                            <div class="google-preview-url" id="googlePreviewUrl">
                                {{ url('/pages/' . ($menu->page_slug ?? $menu->slug)) }}
                            </div>
                            <div class="google-preview-description" id="googlePreviewDescription">
                                {{ $menu->page_meta['description'] ?? 'Description de la page...' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Métadonnées SEO -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="pageTitle" class="form-label">Titre SEO (méta title)</label>
                                <input type="text" class="form-control" id="pageTitle" 
                                       value="{{ $menu->page_meta['title'] ?? $menu->title }}"
                                       maxlength="60">
                                <div class="form-text">
                                    <span id="titleCounter">0/60</span> caractères. Idéal: 50-60 caractères
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="pageDescription" class="form-label">Description SEO (méta description)</label>
                                <textarea class="form-control" id="pageDescription" rows="3" 
                                          maxlength="160">{{ $menu->page_meta['description'] ?? '' }}</textarea>
                                <div class="form-text">
                                    <span id="descriptionCounter">0/160</span> caractères. Idéal: 120-160 caractères
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="pageKeywords" class="form-label">Mots-clés</label>
                                <input type="text" class="form-control" id="pageKeywords" 
                                       value="{{ $menu->page_meta['keywords'] ?? '' }}"
                                       placeholder="tourisme, voyage, destination, vacances">
                                <div class="form-text">Séparés par des virgules</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Open Graph (Facebook) -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fab fa-facebook me-2"></i>Open Graph (Facebook)
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pageOgTitle" class="form-label">Titre Facebook</label>
                                    <input type="text" class="form-control" id="pageOgTitle" 
                                           value="{{ $menu->page_meta['og_title'] ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pageOgDescription" class="form-label">Description Facebook</label>
                                    <input type="text" class="form-control" id="pageOgDescription" 
                                           value="{{ $menu->page_meta['og_description'] ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="pageOgImage" class="form-label">Image Facebook</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="pageOgImage" 
                                               value="{{ $menu->page_meta['og_image'] ?? '' }}"
                                               placeholder="https://example.com/image.jpg">
                                        <button class="btn btn-outline-secondary" type="button" id="browseOgImage">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Image recommandée: 1200x630 pixels</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Analyse SEO -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Analyse SEO
                        </h6>
                        
                        <div class="seo-analysis">
                            <div class="seo-checklist">
                                <div class="seo-item" id="seoCheckTitle">
                                    <i class="fas fa-circle text-secondary"></i>
                                    <span>Titre SEO optimal (50-60 caractères)</span>
                                </div>
                                
                                <div class="seo-item" id="seoCheckDescription">
                                    <i class="fas fa-circle text-secondary"></i>
                                    <span>Description SEO optimale (120-160 caractères)</span>
                                </div>
                                
                                <div class="seo-item" id="seoCheckKeywords">
                                    <i class="fas fa-circle text-secondary"></i>
                                    <span>Mots-clés définis</span>
                                </div>
                                
                                <div class="seo-item" id="seoCheckOgImage">
                                    <i class="fas fa-circle text-secondary"></i>
                                    <span>Image Open Graph définie</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Robot.txt et sitemap -->
                    <div class="mb-3">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-robot me-2"></i>Indexation
                        </h6>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="seoAllowIndexing" checked>
                            <label class="form-check-label" for="seoAllowIndexing">
                                Autoriser l'indexation par les moteurs de recherche
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="seoAllowFollowing" checked>
                            <label class="form-check-label" for="seoAllowFollowing">
                                Autoriser le suivi des liens
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="seoIncludeInSitemap" checked>
                            <label class="form-check-label" for="seoIncludeInSitemap">
                                Inclure dans le sitemap
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="analyzeSeoBtn">
                    <i class="fas fa-sync-alt me-2"></i>Analyser
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveSeoBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer SEO
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .google-preview {
        border: 1px solid #dfe1e5;
        border-radius: 8px;
        padding: 12px 16px;
        background: white;
        font-family: Arial, sans-serif;
        max-width: 600px;
    }
    
    .google-preview-title {
        color: #1a0dab;
        font-size: 20px;
        line-height: 1.3;
        margin-bottom: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .google-preview-url {
        color: #006621;
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 5px;
    }
    
    .google-preview-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.58;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .seo-analysis {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    
    .seo-checklist {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .seo-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border-radius: 5px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .seo-item i.fa-circle.text-success {
        color: #28a745;
    }
    
    .seo-item i.fa-circle.text-warning {
        color: #ffc107;
    }
    
    .seo-item i.fa-circle.text-danger {
        color: #dc3545;
    }
    
    .seo-item i.fa-circle.text-secondary {
        color: #6c757d;
    }
    
    .seo-item:hover {
        background: #e9ecef;
    }
    
    .character-counter {
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    .character-counter.good {
        color: #28a745;
    }
    
    .character-counter.warning {
        color: #ffc107;
    }
    
    .character-counter.danger {
        color: #dc3545;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const menuId = {{ $menu->id }};
        
        // CORRECTION: Utiliser une syntaxe différente pour éviter l'erreur
        const meta = @json($menu->page_meta ?: []);
        
        // Initialiser les valeurs
        if (meta && Object.keys(meta).length > 0) {
            $('#pageTitle').val(meta.title || '');
            $('#pageDescription').val(meta.description || '');
            $('#pageKeywords').val(meta.keywords || '');
            $('#pageOgTitle').val(meta.og_title || '');
            $('#pageOgDescription').val(meta.og_description || '');
            $('#pageOgImage').val(meta.og_image || '');
            
            if (meta.robots) {
                $('#seoAllowIndexing').prop('checked', !meta.robots.includes('noindex'));
                $('#seoAllowFollowing').prop('checked', !meta.robots.includes('nofollow'));
            }
            
            if (typeof meta.sitemap !== 'undefined') {
                $('#seoIncludeInSitemap').prop('checked', meta.sitemap !== false);
            }
        }
        
        // Mettre à jour les compteurs de caractères
        updateCharacterCounters();
        
        // Mettre à jour la prévisualisation Google en temps réel
        $('#pageTitle, #pageDescription').on('input', function() {
            updateGooglePreview();
            updateCharacterCounters();
            analyzeSEO();
        });
        
        $('#pageKeywords, #pageOgTitle, #pageOgDescription, #pageOgImage').on('input', function() {
            analyzeSEO();
        });
        
        // Mettre à jour les checkboxes
        $('#seoAllowIndexing, #seoAllowFollowing, #seoIncludeInSitemap').on('change', analyzeSEO);
        
        // Bouton parcourir pour l'image
        $('#browseOgImage').on('click', function() {
            // Vous pouvez intégrer un sélecteur de médias ici
            alert('Fonctionnalité de sélection de média à implémenter');
        });
        
        // Analyser le SEO
        $('#analyzeSeoBtn').on('click', function() {
            analyzeSEO(true);
        });
        
        // Sauvegarder les paramètres SEO
        $('#saveSeoBtn').on('click', function() {
            saveSEOSettings();
        });
        
        // Initialiser l'analyse
        analyzeSEO();
        updateGooglePreview();
        
        function updateCharacterCounters() {
            const title = $('#pageTitle').val();
            const description = $('#pageDescription').val();
            
            // Titre
            const titleCount = title.length;
            $('#titleCounter').text(`${titleCount}/60`);
            
            // Description
            const descCount = description.length;
            $('#descriptionCounter').text(`${descCount}/160`);
            
            // Mettre à jour les classes CSS
            updateCounterClass($('#titleCounter'), titleCount, 50, 60);
            updateCounterClass($('#descriptionCounter'), descCount, 120, 160);
        }
        
        function updateCounterClass(element, count, minGood, maxGood) {
            element.removeClass('good warning danger');
            
            if (count >= minGood && count <= maxGood) {
                element.addClass('good');
            } else if (count === 0) {
                element.addClass('danger');
            } else if (count < minGood || count > maxGood) {
                element.addClass('warning');
            }
        }
        
        function updateGooglePreview() {
            const title = $('#pageTitle').val() || '{{ $menu->title }}';
            const description = $('#pageDescription').val() || 'Description de la page...';
            const slug = '{{ $menu->page_slug ?? $menu->slug }}';
            
            // Limiter la longueur pour la prévisualisation
            let previewTitle = title;
            if (previewTitle.length > 60) {
                previewTitle = previewTitle.substring(0, 57) + '...';
            }
            
            let previewDesc = description;
            if (previewDesc.length > 160) {
                previewDesc = previewDesc.substring(0, 157) + '...';
            }
            
            $('#googlePreviewTitle').text(previewTitle);
            $('#googlePreviewUrl').text('{{ url("/pages") }}/' + slug);
            $('#googlePreviewDescription').text(previewDesc);
        }
        
        function analyzeSEO(showAlert = false) {
            const title = $('#pageTitle').val();
            const description = $('#pageDescription').val();
            const keywords = $('#pageKeywords').val();
            const ogImage = $('#pageOgImage').val();
            
            // Vérifier le titre
            if (title.length >= 50 && title.length <= 60) {
                $('#seoCheckTitle i').removeClass().addClass('fas fa-circle text-success');
            } else if (title.length === 0) {
                $('#seoCheckTitle i').removeClass().addClass('fas fa-circle text-danger');
            } else {
                $('#seoCheckTitle i').removeClass().addClass('fas fa-circle text-warning');
            }
            
            // Vérifier la description
            if (description.length >= 120 && description.length <= 160) {
                $('#seoCheckDescription i').removeClass().addClass('fas fa-circle text-success');
            } else if (description.length === 0) {
                $('#seoCheckDescription i').removeClass().addClass('fas fa-circle text-danger');
            } else {
                $('#seoCheckDescription i').removeClass().addClass('fas fa-circle text-warning');
            }
            
            // Vérifier les mots-clés
            if (keywords && keywords.trim().length > 0) {
                $('#seoCheckKeywords i').removeClass().addClass('fas fa-circle text-success');
            } else {
                $('#seoCheckKeywords i').removeClass().addClass('fas fa-circle text-warning');
            }
            
            // Vérifier l'image Open Graph
            if (ogImage && ogImage.trim().length > 0 && ogImage.startsWith('http')) {
                $('#seoCheckOgImage i').removeClass().addClass('fas fa-circle text-success');
            } else {
                $('#seoCheckOgImage i').removeClass().addClass('fas fa-circle text-warning');
            }
            
            if (showAlert) {
                const issues = [];
                
                if (!title || title.length === 0) issues.push('Titre SEO manquant');
                if (!description || description.length === 0) issues.push('Description SEO manquante');
                if (!keywords || keywords.trim().length === 0) issues.push('Mots-clés non définis');
                if (!ogImage || ogImage.trim().length === 0) issues.push('Image Open Graph manquante');
                
                if (issues.length === 0) {
                    showNotification('success', 'Analyse SEO complétée. Aucun problème majeur détecté.');
                } else {
                    showNotification('warning', 'Analyse SEO: ' + issues.join(', '));
                }
            }
        }
        
        function saveSEOSettings() {
            const seoData = {
                title: $('#pageTitle').val() || '',
                description: $('#pageDescription').val() || '',
                keywords: $('#pageKeywords').val() || '',
                og_title: $('#pageOgTitle').val() || '',
                og_description: $('#pageOgDescription').val() || '',
                og_image: $('#pageOgImage').val() || '',
                robots: [],
                sitemap: $('#seoIncludeInSitemap').is(':checked')
            };
            
            // Construire la directive robots
            if (!$('#seoAllowIndexing').is(':checked')) {
                seoData.robots.push('noindex');
            }
            if (!$('#seoAllowFollowing').is(':checked')) {
                seoData.robots.push('nofollow');
            }
            
            $.ajax({
                url: `/menus/${menuId}/page/update-seo`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    meta: seoData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('success', 'Paramètres SEO sauvegardés avec succès');
                        $('#seoModal').modal('hide');
                    } else {
                        showNotification('danger', response.message || 'Erreur lors de la sauvegarde');
                    }
                },
                error: function(xhr) {
                    console.error('Save SEO error:', xhr.responseText);
                    showNotification('danger', 'Erreur lors de la sauvegarde');
                }
            });
        }
        
        function showNotification(type, message) {
            // Implémentez votre fonction de notification
            alert(message); // Version simple pour le moment
        }
    });
</script>