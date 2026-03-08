{{-- resources/views/admin/menus/modals/page-settings.blade.php --}}
<div class="modal fade" id="pageSettingsModal" tabindex="-1" aria-labelledby="pageSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pageSettingsModalLabel">
                    <i class="fas fa-cog me-2"></i>Paramètres de la page
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pageSettingsForm">
                    @csrf
                    
                    <div class="row">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="settingsPageTitle" class="form-label">Titre de la page</label>
                                <input type="text" class="form-control" id="settingsPageTitle" 
                                       value="{{ $menu->page_meta['title'] ?? $menu->title }}">
                                <div class="form-text">Titre qui apparaîtra dans l'onglet du navigateur</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="settingsPageSlug" class="form-label">Slug de la page</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ url('/pages/') }}/</span>
                                    <input type="text" class="form-control" id="settingsPageSlug" 
                                           value="{{ $menu->page_slug ?? $menu->slug }}">
                                </div>
                                <div class="form-text">URL unique de la page</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuration de la mise en page -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-layer-group me-2"></i>Mise en page
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Largeur du contenu</label>
                                    <select class="form-select" id="settingsPageWidth">
                                        <option value="container">Conteneur (1200px)</option>
                                        <option value="container-fluid">Fluide (pleine largeur)</option>
                                        <option value="custom">Personnalisée</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">En-tête de page</label>
                                    <select class="form-select" id="settingsPageHeader">
                                        <option value="standard">Standard</option>
                                        <option value="hero">Hero grande</option>
                                        <option value="minimal">Minimaliste</option>
                                        <option value="none">Aucun</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Pied de page</label>
                                    <select class="form-select" id="settingsPageFooter">
                                        <option value="standard">Standard</option>
                                        <option value="minimal">Minimaliste</option>
                                        <option value="none">Aucun</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options avancées -->
                    <div class="mb-3">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-sliders-h me-2"></i>Options avancées
                        </h6>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settingsShowBreadcrumb" checked>
                            <label class="form-check-label" for="settingsShowBreadcrumb">
                                Afficher le fil d'Ariane
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settingsShowSidebar" checked>
                            <label class="form-check-label" for="settingsShowSidebar">
                                Afficher la barre latérale
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settingsAllowComments">
                            <label class="form-check-label" for="settingsAllowComments">
                                Autoriser les commentaires
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settingsShowShareButtons" checked>
                            <label class="form-check-label" for="settingsShowShareButtons">
                                Afficher les boutons de partage
                            </label>
                        </div>
                    </div>
                    
                    <!-- Thème et couleurs -->
                    <div class="mb-3">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-palette me-2"></i>Thème & Couleurs
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Couleur principale</label>
                                    <div class="input-group color-picker">
                                        <input type="color" class="form-control form-control-color" 
                                               id="settingsPrimaryColor" value="#007bff" title="Choisir une couleur">
                                        <input type="text" class="form-control" value="#007bff" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Police principale</label>
                                    <select class="form-select" id="settingsFontFamily">
                                        <option value="'Inter', sans-serif">Inter (moderne)</option>
                                        <option value="'Roboto', sans-serif">Roboto</option>
                                        <option value="'Open Sans', sans-serif">Open Sans</option>
                                        <option value="'Poppins', sans-serif">Poppins</option>
                                        <option value="'Montserrat', sans-serif">Montserrat</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveSettingsBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .color-picker {
        align-items: center;
    }
    
    .color-picker input[type="color"] {
        width: 50px;
        height: 38px;
        padding: 2px;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser les valeurs depuis la base de données
        const pageConfig = @json($menu->page_config ?? []);
        
        if (Object.keys(pageConfig).length > 0) {
            $('#settingsPageWidth').val(pageConfig.width || 'container');
            $('#settingsPageHeader').val(pageConfig.header || 'standard');
            $('#settingsPageFooter').val(pageConfig.footer || 'standard');
            $('#settingsShowBreadcrumb').prop('checked', pageConfig.show_breadcrumb !== false);
            $('#settingsShowSidebar').prop('checked', pageConfig.show_sidebar !== false);
            $('#settingsAllowComments').prop('checked', pageConfig.allow_comments === true);
            $('#settingsShowShareButtons').prop('checked', pageConfig.show_share_buttons !== false);
            
            if (pageConfig.primary_color) {
                $('#settingsPrimaryColor').val(pageConfig.primary_color);
                $('#settingsPrimaryColor').next('input').val(pageConfig.primary_color);
            }
            
            if (pageConfig.font_family) {
                $('#settingsFontFamily').val(pageConfig.font_family);
            }
        }
        
        // Mettre à jour la valeur du champ texte lorsque la couleur change
        $('#settingsPrimaryColor').on('input', function() {
            $(this).next('input').val(this.value);
        });
        
        // Sauvegarder les paramètres
        $('#saveSettingsBtn').on('click', function() {
            const settings = {
                width: $('#settingsPageWidth').val(),
                header: $('#settingsPageHeader').val(),
                footer: $('#settingsPageFooter').val(),
                show_breadcrumb: $('#settingsShowBreadcrumb').is(':checked'),
                show_sidebar: $('#settingsShowSidebar').is(':checked'),
                allow_comments: $('#settingsAllowComments').is(':checked'),
                show_share_buttons: $('#settingsShowShareButtons').is(':checked'),
                primary_color: $('#settingsPrimaryColor').val(),
                font_family: $('#settingsFontFamily').val()
            };
            
            // Mettre à jour le titre et le slug si modifiés
            const newTitle = $('#settingsPageTitle').val();
            const newSlug = $('#settingsPageSlug').val();
            
            $.ajax({
                url: `/menus/{{ $menu->id }}/page/update-settings`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    title: newTitle,
                    slug: newSlug,
                    config: settings
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('success', 'Paramètres sauvegardés avec succès');
                        $('#pageSettingsModal').modal('hide');
                        
                        // Mettre à jour le titre dans l'éditeur si changé
                        if (newTitle && newTitle !== '{{ $menu->title }}') {
                            $('#pageTitle').val(newTitle);
                        }
                    } else {
                        showNotification('danger', response.message || 'Erreur lors de la sauvegarde');
                    }
                },
                error: function(xhr) {
                    console.error('Save settings error:', xhr.responseText);
                    showNotification('danger', 'Erreur lors de la sauvegarde');
                }
            });
        });
        
        // Générer automatiquement le slug à partir du titre
        $('#settingsPageTitle').on('blur', function() {
            const title = $(this).val();
            const slugInput = $('#settingsPageSlug');
            
            if (title && (!slugInput.val() || slugInput.data('auto-generated'))) {
                const slug = title.toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.val(slug);
                slugInput.data('auto-generated', true);
            }
        });
        
        $('#settingsPageSlug').on('input', function() {
            $(this).data('auto-generated', false);
        });
    });
</script>