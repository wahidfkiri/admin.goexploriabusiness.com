@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-bullhorn"></i></span>
                Gestion des Campagnes Email
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ route('mail-campaigns.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Campagne
                </a>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres</h3>
                <div class="filter-actions-modern">
                    <button class="btn btn-sm btn-outline-secondary" id="clearFiltersBtn">
                        <i class="fas fa-times me-1"></i>Effacer
                    </button>
                    <button class="btn btn-sm btn-primary" id="applyFiltersBtn">
                        <i class="fas fa-check me-1"></i>Appliquer
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="draft">Brouillon</option>
                        <option value="scheduled">Planifiée</option>
                        <option value="sending">En cours d'envoi</option>
                        <option value="sent">Envoyée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDate" class="form-label-modern">Période</label>
                    <select class="form-select-modern" id="filterDate">
                        <option value="">Toutes les dates</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="quarter">Ce trimestre</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="created_at">Date de création</option>
                        <option value="nom">Nom</option>
                        <option value="scheduled_at">Date planifiée</option>
                        <option value="sent_at">Date d'envoi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterOrder" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterOrder">
                        <option value="desc">Plus récent d'abord</option>
                        <option value="asc">Plus ancien d'abord</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalCampaigns">{{ $stats['total'] ?? 0 }}</div>
                        <div class="stats-label-modern">Total Campagnes</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="sentCampaigns">{{ $stats['sent'] ?? 0 }}</div>
                        <div class="stats-label-modern">Campagnes Envoyées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="scheduledCampaigns">{{ $stats['scheduled'] ?? 0 }}</div>
                        <div class="stats-label-modern">Campagnes Planifiées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="draftCampaigns">{{ $stats['draft'] ?? 0 }}</div>
                        <div class="stats-label-modern">Brouillons</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                    <a href="{{route('mail-subscribers.index')}}" style="text-decoration:none">
                        <div class="stats-value-modern" id="totalSubscribers">{{ $stats['total_subscribers'] ?? 0 }}</div>
                        <div class="stats-label-modern">Abonnés</div>
                    </a>
                    </div>
                    <a href="{{route('mail-subscribers.index')}}" style="text-decoration:none">
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-users"></i>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Campagnes</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une campagne..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Table Container -->
                <div class="table-container-modern" id="tableContainer">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Campagne</th>
                                <th>Statut</th>
                                <th>Planification</th>
                                <th>Envoi</th>
                                <th>Statistiques</th>
                                <th>Créé par</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="campaignsTableBody">
                            @forelse($campaigns as $campaign)
                            <tr id="campaign-row-{{ $campaign->id }}">
                                <td>
                                    <div class="campaign-name-cell">
                                        <div class="campaign-name-modern">
                                            <div class="campaign-icon-modern" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getCampaignColor($campaign->nom) }}">
                                                <i class="fas fa-envelope-open-text"></i>
                                            </div>
                                            <div>
                                                <div class="campaign-name-text">
                                                    {{ $campaign->nom }}
                                                    @if($campaign->status === 'draft')
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">Brouillon</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-tag me-1"></i>{{ $campaign->sujet }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {!! \Vendor\MailMarketing\Helpers\Helper::getStatusBadge($campaign->status) !!}
                                </td>
                                <td>
                                    @if($campaign->scheduled_at)
                                        <div class="schedule-info">
                                            <div class="schedule-date">
                                                <i class="fas fa-calendar me-1"></i>{{ $campaign->scheduled_at->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $campaign->scheduled_at->format('H:i') }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">Non planifiée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($campaign->sent_at)
                                        <div class="sent-info">
                                            <div class="sent-date">
                                                <i class="fas fa-check-circle text-success me-1"></i>{{ $campaign->sent_at->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">{{ $campaign->sent_at->format('H:i') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Non envoyée</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="stats-container">
                                        <div class="stat-item" title="Envoyés">
                                            <i class="fas fa-paper-plane text-primary"></i>
                                            <span>{{ $campaign->stats['sent'] ?? 0 }}</span>
                                        </div>
                                        <div class="stat-item" title="Ouverts">
                                            <i class="fas fa-eye text-success"></i>
                                            <span>{{ $campaign->stats['opened'] ?? 0 }}</span>
                                        </div>
                                        <div class="stat-item" title="Clics">
                                            <i class="fas fa-mouse-pointer text-warning"></i>
                                            <span>{{ $campaign->stats['clicked'] ?? 0 }}</span>
                                        </div>
                                        @php
                                            $openRate = $campaign->stats['sent'] > 0 ? round(($campaign->stats['opened'] / $campaign->stats['sent']) * 100) : 0;
                                        @endphp
                                        <div class="stat-percentage" title="Taux d'ouverture">
                                            {{ $openRate }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($campaign->createdBy)
                                        <div class="user-info">
                                            <div class="user-avatar-sm" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getUserColor($campaign->createdBy->name) }}">
                                                {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($campaign->createdBy->name) }}
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name">{{ $campaign->createdBy->name }}</div>
                                                <small class="text-muted">{{ $campaign->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="campaign-actions-modern">
                                        <a href="{{ route('mail-campaigns.show', $campaign) }}" 
                                           class="action-btn-modern view-btn-modern" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($campaign->status === 'draft')
                                            <a href="{{ route('mail-campaigns.edit', $campaign) }}"
                                               class="action-btn-modern edit-btn-modern" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button class="action-btn-modern send-btn-modern" title="Envoyer maintenant" 
                                                    onclick="showSendConfirmation({{ $campaign->id }})">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        @endif
                                        
                                        @if($campaign->status === 'sent')
                                            <button class="action-btn-modern duplicate-btn-modern" title="Dupliquer" 
                                                    onclick="duplicateCampaign({{ $campaign->id }})">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($campaign->status, ['draft', 'scheduled', 'cancelled']))
                                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                                    onclick="showDeleteConfirmation({{ $campaign->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state-modern">
                                        <div class="empty-icon-modern">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <h3 class="empty-title-modern">Aucune campagne trouvée</h3>
                                        <p class="empty-text-modern">Commencez par créer votre première campagne email.</p>
                                        <a href="{{ route('mail-campaigns.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i>Créer une campagne
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($campaigns->hasPages())
            <div class="pagination-container-modern">
                <div class="pagination-info-modern">
                    Affichage de {{ $campaigns->firstItem() }} à {{ $campaigns->lastItem() }} sur {{ $campaigns->total() }} campagnes
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="modern-pagination">
                        {{-- Previous Page Link --}}
                        @if($campaigns->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link-modern"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link-modern" href="{{ $campaigns->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($campaigns->getUrlRange(1, $campaigns->lastPage()) as $page => $url)
                            @if($page == $campaigns->currentPage())
                                <li class="page-item active">
                                    <span class="page-link-modern">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link-modern" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($campaigns->hasMorePages())
                            <li class="page-item">
                                <a class="page-link-modern" href="{{ $campaigns->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link-modern"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ route('mail-campaigns.create') }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
    <!-- SEND CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="sendConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="send-icon" style="color: #06b48a; font-size: 3rem;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4 class="delete-title">Confirmer l'envoi</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir envoyer cette campagne ?</p>
                    
                    <div class="campaign-to-send" id="campaignToSendInfo">
                        <!-- Campaign info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> L'envoi sera traité en arrière-plan.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-success" id="confirmSendBtn">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer maintenant
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la suppression</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette campagne ?</p>
                    
                    <div class="campaign-to-delete" id="campaignToDeleteInfo">
                        <!-- Campaign info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allCampaigns = @json($campaigns->items());
        let campaignToDelete = null;
        let campaignToSend = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });

        // Setup event listeners
        const setupEventListeners = () => {
            // Search input
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(this.value);
                    }, 500);
                });
            }
            
            // Toggle filter section
            const toggleFilterBtn = document.getElementById('toggleFilterBtn');
            const filterSection = document.getElementById('filterSection');
            
            if (toggleFilterBtn && filterSection) {
                toggleFilterBtn.addEventListener('click', () => {
                    const isVisible = filterSection.style.display === 'block';
                    filterSection.style.display = isVisible ? 'none' : 'block';
                    toggleFilterBtn.innerHTML = isVisible 
                        ? '<i class="fas fa-sliders-h me-2"></i>Filtres'
                        : '<i class="fas fa-times me-2"></i>Masquer les filtres';
                });
            }
            
            // Apply filters
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', applyFilters);
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearFilters);
            }
            
            // Confirm send button
            const confirmSendBtn = document.getElementById('confirmSendBtn');
            if (confirmSendBtn) {
                confirmSendBtn.addEventListener('click', sendCampaign);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteCampaign);
            }
        };

        // Perform search
        const performSearch = (term) => {
            const rows = document.querySelectorAll('#campaignsTableBody tr');
            
            rows.forEach(row => {
                const campaignName = row.querySelector('.campaign-name-text')?.textContent.toLowerCase() || '';
                const campaignSubject = row.querySelector('.text-muted')?.textContent.toLowerCase() || '';
                
                if (campaignName.includes(term.toLowerCase()) || campaignSubject.includes(term.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        // Apply filters
        const applyFilters = () => {
            const status = document.getElementById('filterStatus').value;
            const date = document.getElementById('filterDate').value;
            
            // Redirect with filters
            window.location.href = '{{ route("mail-campaigns.index") }}' + 
                '?status=' + status + 
                '&date=' + date;
        };

        // Clear filters
        const clearFilters = () => {
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDate').value = '';
            window.location.href = '{{ route("mail-campaigns.index") }}';
        };

        // Show send confirmation modal
        const showSendConfirmation = (campaignId) => {
            const campaign = allCampaigns.find(c => c.id === campaignId);
            
            if (!campaign) {
                showAlert('danger', 'Campagne non trouvée');
                return;
            }
            
            campaignToSend = campaign;
            
            document.getElementById('campaignToSendInfo').innerHTML = `
                <div class="campaign-info">
                    <div class="campaign-info-icon" style="background: ${getCampaignColor(campaign.nom)}">
                        <i class="fas fa-envelope-open-text fa-2x"></i>
                    </div>
                    <div>
                        <div class="campaign-info-name">${campaign.nom}</div>
                        <div class="campaign-info-details">
                            <div><strong>Sujet:</strong> ${campaign.sujet}</div>
                            <div><strong>Destinataires:</strong> ${campaign.stats?.total || 0} abonnés</div>
                        </div>
                    </div>
                </div>
            `;
            
            const sendModal = new bootstrap.Modal(document.getElementById('sendConfirmationModal'));
            sendModal.show();
        };

        // Send campaign
        const sendCampaign = () => {
            if (!campaignToSend) return;
            
            const sendBtn = document.getElementById('confirmSendBtn');
            sendBtn.innerHTML = `
                <span class="btn-text" style="display: none;"></span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Envoi...</span>
                </div>
                Envoi en cours...
            `;
            sendBtn.disabled = true;
            
            $.ajax({
                url: `/mail-campaigns/${campaignToSend.id}/send`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    const sendModal = bootstrap.Modal.getInstance(document.getElementById('sendConfirmationModal'));
                    sendModal.hide();
                    
                    showAlert('success', response.message || 'Campagne envoyée avec succès !');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de l\'envoi: ' + (xhr.responseJSON?.message || xhr.statusText));
                },
                complete: function() {
                    sendBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer maintenant
                        </span>
                    `;
                    sendBtn.disabled = false;
                    campaignToSend = null;
                }
            });
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (campaignId) => {
            const campaign = allCampaigns.find(c => c.id === campaignId);
            
            if (!campaign) {
                showAlert('danger', 'Campagne non trouvée');
                return;
            }
            
            campaignToDelete = campaign;
            
            document.getElementById('campaignToDeleteInfo').innerHTML = `
                <div class="campaign-info">
                    <div class="campaign-info-icon" style="background: ${getCampaignColor(campaign.nom)}">
                        <i class="fas fa-envelope-open-text fa-2x"></i>
                    </div>
                    <div>
                        <div class="campaign-info-name">${campaign.nom}</div>
                        <div class="campaign-info-details">
                            <div><strong>Sujet:</strong> ${campaign.sujet}</div>
                            <div><strong>Statut:</strong> ${getStatusText(campaign.status)}</div>
                        </div>
                    </div>
                </div>
            `;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        };

        // Delete campaign
        const deleteCampaign = () => {
            if (!campaignToDelete) return;
            
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;"></span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression en cours...
            `;
            deleteBtn.disabled = true;
            
            const row = document.getElementById(`campaign-row-${campaignToDelete.id}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            $.ajax({
                url: `/mail-campaigns/${campaignToDelete.id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        showAlert('success', response.message || 'Campagne supprimée avec succès !');
                        
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                const tbody = document.getElementById('campaignsTableBody');
                                if (tbody.children.length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    if (row) row.classList.remove('deleting-row');
                    showAlert('danger', 'Erreur lors de la suppression: ' + (xhr.responseJSON?.message || xhr.statusText));
                },
                complete: function() {
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                    campaignToDelete = null;
                }
            });
        };

        // Duplicate campaign
        const duplicateCampaign = (campaignId) => {
            const row = document.getElementById(`campaign-row-${campaignId}`);
            if (row) {
                row.style.opacity = '0.5';
            }
            
            $.ajax({
                url: `/mail-campaigns/${campaignId}/duplicate`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    showAlert('success', 'Campagne dupliquée avec succès !');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    if (row) {
                        row.style.opacity = '';
                    }
                    showAlert('danger', 'Erreur lors de la duplication: ' + (xhr.responseJSON?.message || xhr.statusText));
                }
            });
        };

        // Helper functions
        const getCampaignColor = (campaignName) => {
            let hash = 0;
            for (let i = 0; i < campaignName.length; i++) {
                hash = campaignName.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
                '#9b59b6', '#3498db', '#1abc9c', '#e74c3c'
            ];
            
            return colors[Math.abs(hash) % colors.length];
        };

        const getUserColor = (userName) => {
            const colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6'];
            const index = (userName?.length || 0) % colors.length;
            return colors[index];
        };

        const getInitials = (name) => {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        };

        const getStatusText = (status) => {
            const statusMap = {
                'draft': 'Brouillon',
                'scheduled': 'Planifiée',
                'sending': 'En cours',
                'sent': 'Envoyée',
                'cancelled': 'Annulée'
            };
            return statusMap[status] || status;
        };

        const getStatusBadge = (status) => {
            const badges = {
                'draft': '<span class="badge bg-secondary"><i class="fas fa-pencil-alt me-1"></i>Brouillon</span>',
                'scheduled': '<span class="badge bg-info"><i class="fas fa-clock me-1"></i>Planifiée</span>',
                'sending': '<span class="badge bg-warning"><i class="fas fa-spinner me-1"></i>En cours</span>',
                'sent': '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Envoyée</span>',
                'cancelled': '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Annulée</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
        };

        const showAlert = (type, message) => {
            const existingAlert = document.querySelector('.alert-custom-modern');
            if (existingAlert) existingAlert.remove();
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        };
    </script>

    <style>
        /* Campaign specific styles */
        .campaign-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .campaign-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .campaign-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .stats-container {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
            color: #666;
        }

        .stat-item i {
            font-size: 1rem;
        }

        .stat-percentage {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .schedule-info, .sent-info {
            display: flex;
            flex-direction: column;
        }

        .schedule-date, .sent-date {
            font-weight: 500;
            font-size: 0.9rem;
            color: #333;
        }

        .campaign-actions-modern {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btn-modern {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .send-btn-modern {
            background: linear-gradient(135deg, #06b48a, #049a72);
            color: white;
        }

        .send-btn-modern:hover {
            background: linear-gradient(135deg, #049a72, #037a5a);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(6, 180, 138, 0.3);
            color: white;
        }

        .duplicate-btn-modern {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .duplicate-btn-modern:hover {
            background: linear-gradient(135deg, #8e44ad, #7a3b93);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3);
            color: white;
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
            color: white;
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
            color: white;
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
            color: white;
        }

        .campaign-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .campaign-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .campaign-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .campaign-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .campaign-info-details div {
            margin-bottom: 2px;
        }

        .send-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Animation for deleting row */
        .deleting-row {
            animation: slideOut 0.3s ease forwards;
            opacity: 0.5;
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .stats-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .campaign-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .campaign-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
        }
        /* Petit avatar (32x32) - Utilisé dans les tableaux */
        .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 8px; /* Coins légèrement arrondis pour un look moderne */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            flex-shrink: 0; /* Empêche l'avatar de rétrécir */
        }

        .user-avatar-sm:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
@endsection