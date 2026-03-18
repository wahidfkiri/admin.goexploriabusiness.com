@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-users"></i></span>
                Gestion des Abonnés
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <div class="btn-group">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('mail-subscribers.export', ['format' => 'csv']) }}">CSV</a></li>
                        <li><a class="dropdown-item" href="{{ route('mail-subscribers.export', ['format' => 'excel']) }}">Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('mail-subscribers.export', ['format' => 'pdf']) }}">PDF</a></li>
                    </ul>
                </div>
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload me-2"></i>Import
                </button>
                <a href="{{ route('mail-subscribers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvel Abonné
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
                        <option value="">Tous</option>
                        <option value="subscribed">Abonné</option>
                        <option value="unsubscribed">Désabonné</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterEtablissement" class="form-label-modern">Établissement</label>
                    <select class="form-select-modern" id="filterEtablissement">
                        <option value="">Tous</option>
                        @foreach($etablissements ?? [] as $etablissement)
                            <option value="{{ $etablissement->id }}">{{ $etablissement->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDate" class="form-label-modern">Date d'inscription</label>
                    <select class="form-select-modern" id="filterDate">
                        <option value="">Toutes</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="created_at">Date d'inscription</option>
                        <option value="email">Email</option>
                        <option value="nom">Nom</option>
                        <option value="prenom">Prénom</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total'] ?? 0 }}</div>
                        <div class="stats-label-modern">Total Abonnés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['subscribed'] ?? 0 }}</div>
                        <div class="stats-label-modern">Abonnés Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['unsubscribed'] ?? 0 }}</div>
                        <div class="stats-label-modern">Désabonnés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['etablissements'] ?? 0 }}</div>
                        <div class="stats-label-modern">Établissements</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['this_month'] ?? 0 }}</div>
                        <div class="stats-label-modern">Nouveaux (ce mois)</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Abonnés</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un abonné..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Table Container -->
                <div class="table-container-modern">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Abonné</th>
                                <th>Établissement</th>
                                <th>Statut</th>
                                <th>Inscription</th>
                                <th>Campagnes reçues</th>
                                <th>Engagement</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subscribersTableBody">
                            @forelse($subscribers as $subscriber)
                            <tr id="subscriber-row-{{ $subscriber->id }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input subscriber-checkbox" type="checkbox" value="{{ $subscriber->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="subscriber-info">
                                        <div class="subscriber-avatar" style="background: {{ getAvatarColor($subscriber->email) }}">
                                            {{ getInitials($subscriber->nom, $subscriber->prenom) }}
                                        </div>
                                        <div class="subscriber-details">
                                            <div class="subscriber-name">
                                                {{ $subscriber->prenom }} {{ $subscriber->nom }}
                                                @if(!$subscriber->is_subscribed)
                                                    <span class="badge bg-secondary ms-2">Désabonné</span>
                                                @endif
                                            </div>
                                            <div class="subscriber-email">
                                                <i class="fas fa-envelope me-1"></i>{{ $subscriber->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($subscriber->etablissement)
                                        <div class="etablissement-info">
                                            <div class="etablissement-name">{{ $subscriber->etablissement->name }}</div>
                                            <small class="text-muted">{{ $subscriber->etablissement->ville }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $subscriber->is_subscribed 
                                        ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Abonné</span>' 
                                        : '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Désabonné</span>' !!}
                                    @if($subscriber->unsubscribed_at)
                                        <small class="d-block text-muted">
                                            {{ $subscriber->unsubscribed_at->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $subscriber->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $subscriber->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="campaigns-count">
                                        <span class="count">{{ $subscriber->campaigns_count ?? 0 }}</span>
                                        <small class="text-muted">campagnes</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $totalCampaigns = $subscriber->campaigns_count ?? 0;
                                        $opened = $subscriber->opened_count ?? 0;
                                        $clicked = $subscriber->clicked_count ?? 0;
                                        $openRate = $totalCampaigns > 0 ? round(($opened / $totalCampaigns) * 100) : 0;
                                    @endphp
                                    <div class="engagement-stats">
                                        <div class="engagement-item" title="Ouvertures">
                                            <i class="fas fa-eye text-success"></i>
                                            <span>{{ $opened }}</span>
                                        </div>
                                        <div class="engagement-item" title="Clics">
                                            <i class="fas fa-mouse-pointer text-warning"></i>
                                            <span>{{ $clicked }}</span>
                                        </div>
                                        <div class="engagement-rate" style="background: {{ getEngagementColor($openRate) }}">
                                            {{ $openRate }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="subscriber-actions-modern">
                                        <a href="{{ route('mail-subscribers.show', $subscriber) }}" 
                                           class="action-btn-modern view-btn-modern" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('mail-subscribers.edit', $subscriber) }}"
                                           class="action-btn-modern edit-btn-modern" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($subscriber->is_subscribed)
                                            <button class="action-btn-modern unsubscribe-btn-modern" title="Désabonner" 
                                                    onclick="unsubscribeSubscriber({{ $subscriber->id }})">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button class="action-btn-modern resubscribe-btn-modern" title="Réabonner" 
                                                    onclick="resubscribeSubscriber({{ $subscriber->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                                onclick="showDeleteConfirmation({{ $subscriber->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state-modern">
                                        <div class="empty-icon-modern">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h3 class="empty-title-modern">Aucun abonné trouvé</h3>
                                        <p class="empty-text-modern">Commencez par ajouter vos premiers abonnés.</p>
                                        <div class="empty-actions">
                                            <a href="{{ route('mail-subscribers.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i>Ajouter manuellement
                                            </a>
                                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                                                <i class="fas fa-upload me-2"></i>Importer
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination and Bulk Actions -->
            <div class="card-footer-modern">
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <span class="selected-count" id="selectedCount">0 sélectionné(s)</span>
                    <div class="bulk-buttons">
                        <button class="btn btn-sm btn-outline-danger" onclick="bulkUnsubscribe()">
                            <i class="fas fa-ban me-1"></i>Désabonner
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="bulkResubscribe()">
                            <i class="fas fa-undo me-1"></i>Réabonner
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="bulkExport()">
                            <i class="fas fa-download me-1"></i>Exporter
                        </button>
                    </div>
                </div>
                
                @if($subscribers->hasPages())
                <div class="pagination-container-modern">
                    <div class="pagination-info-modern">
                        Affichage de {{ $subscribers->firstItem() }} à {{ $subscribers->lastItem() }} sur {{ $subscribers->total() }} abonnés
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="modern-pagination">
                            {{ $subscribers->links() }}
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ route('mail-subscribers.create') }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
    <!-- IMPORT MODAL -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload me-2"></i>Importer des abonnés
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mail-subscribers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Format accepté : CSV avec les colonnes : email, nom, prénom, etablissement_id (optionnel)
                        </div>
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="importEtablissement" class="form-label">Établissement par défaut</label>
                            <select class="form-select" id="importEtablissement" name="default_etablissement_id">
                                <option value="">Aucun</option>
                                @foreach($etablissements ?? [] as $etablissement)
                                    <option value="{{ $etablissement->id }}">{{ $etablissement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="skipFirstRow" name="skip_first_row" checked>
                            <label class="form-check-label" for="skipFirstRow">
                                Ignorer la première ligne (en-têtes)
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload me-2"></i>Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- UNSUBSCRIBE CONFIRMATION MODAL -->
    <div class="modal fade" id="unsubscribeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="unsubscribe-icon" style="color: #ef476f; font-size: 3rem;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h4 class="delete-title">Confirmer le désabonnement</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir désabonner cet abonné ?</p>
                    
                    <div class="subscriber-info-display" id="unsubscribeInfo">
                        <!-- Subscriber info will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-warning" id="confirmUnsubscribeBtn">
                        <i class="fas fa-ban me-2"></i>Désabonner
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cet abonné ?</p>
                    
                    <div class="subscriber-info-display" id="deleteInfo">
                        <!-- Subscriber info will be loaded here -->
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
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let allSubscribers = @json($subscribers->items());
        let subscriberToDelete = null;
        let subscriberToUnsubscribe = null;

        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            initializeBulkActions();
        });

        const setupEventListeners = () => {
            // Search
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(this.value);
                    }, 300);
                });
            }
            
            // Select all checkbox
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            
            // Individual checkboxes
            document.querySelectorAll('.subscriber-checkbox').forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });
            
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
            
            // Confirm unsubscribe button
            const confirmUnsubscribeBtn = document.getElementById('confirmUnsubscribeBtn');
            if (confirmUnsubscribeBtn) {
                confirmUnsubscribeBtn.addEventListener('click', unsubscribeSubscriber);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteSubscriber);
            }
        };

        // Perform search
        const performSearch = (term) => {
            const rows = document.querySelectorAll('#subscribersTableBody tr');
            
            rows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;
                
                const name = row.querySelector('.subscriber-name')?.textContent.toLowerCase() || '';
                const email = row.querySelector('.subscriber-email')?.textContent.toLowerCase() || '';
                const etablissement = row.querySelector('.etablissement-name')?.textContent.toLowerCase() || '';
                
                if (name.includes(term.toLowerCase()) || email.includes(term.toLowerCase()) || etablissement.includes(term.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        // Apply filters
        const applyFilters = () => {
            const status = document.getElementById('filterStatus').value;
            const etablissement = document.getElementById('filterEtablissement').value;
            const date = document.getElementById('filterDate').value;
            
            window.location.href = '{{ route("mail-subscribers.index") }}' + 
                '?status=' + status + 
                '&etablissement=' + etablissement + 
                '&date=' + date;
        };

        // Clear filters
        const clearFilters = () => {
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterEtablissement').value = '';
            document.getElementById('filterDate').value = '';
            window.location.href = '{{ route("mail-subscribers.index") }}';
        };

        // Initialize bulk actions
        const initializeBulkActions = () => {
            updateBulkActions();
        };

        // Update bulk actions visibility
        const updateBulkActions = () => {
            const checkboxes = document.querySelectorAll('.subscriber-checkbox:checked');
            const selectedCount = checkboxes.length;
            const bulkActions = document.getElementById('bulkActions');
            
            if (selectedCount > 0) {
                document.getElementById('selectedCount').textContent = selectedCount + ' sélectionné(s)';
                bulkActions.style.display = 'flex';
            } else {
                bulkActions.style.display = 'none';
            }
        };

        // Bulk unsubscribe
        const bulkUnsubscribe = () => {
            const selectedIds = Array.from(document.querySelectorAll('.subscriber-checkbox:checked')).map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Voulez-vous désabonner ${selectedIds.length} abonné(s) ?`)) {
                $.ajax({
                    url: '{{ route("mail-subscribers.bulk-unsubscribe") }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showAlert('success', response.message || 'Abonnés désabonnés avec succès');
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: function(xhr) {
                        showAlert('danger', 'Erreur lors du désabonnement');
                    }
                });
            }
        };

        // Bulk resubscribe
        const bulkResubscribe = () => {
            const selectedIds = Array.from(document.querySelectorAll('.subscriber-checkbox:checked')).map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Voulez-vous réabonner ${selectedIds.length} abonné(s) ?`)) {
                $.ajax({
                    url: '{{ route("mail-subscribers.bulk-resubscribe") }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showAlert('success', response.message || 'Abonnés réabonnés avec succès');
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: function(xhr) {
                        showAlert('danger', 'Erreur lors du réabonnement');
                    }
                });
            }
        };

        // Bulk export
        const bulkExport = () => {
            const selectedIds = Array.from(document.querySelectorAll('.subscriber-checkbox:checked')).map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            window.location.href = '{{ route("mail-subscribers.export") }}?ids=' + selectedIds.join(',');
        };

        // Show unsubscribe confirmation
        const showUnsubscribeConfirmation = (subscriberId) => {
            const subscriber = allSubscribers.find(s => s.id === subscriberId);
            
            if (!subscriber) return;
            
            subscriberToUnsubscribe = subscriber;
            
            document.getElementById('unsubscribeInfo').innerHTML = `
                <div class="subscriber-display">
                    <div class="subscriber-avatar-lg" style="background: ${getAvatarColor(subscriber.email)}">
                        ${getInitials(subscriber.prenom, subscriber.nom)}
                    </div>
                    <div class="subscriber-details">
                        <div class="subscriber-fullname">${subscriber.prenom} ${subscriber.nom}</div>
                        <div class="subscriber-email">${subscriber.email}</div>
                        ${subscriber.etablissement ? `<div class="subscriber-etablissement">${subscriber.etablissement.name}</div>` : ''}
                    </div>
                </div>
            `;
            
            const unsubscribeModal = new bootstrap.Modal(document.getElementById('unsubscribeModal'));
            unsubscribeModal.show();
        };

        // Unsubscribe subscriber
        const unsubscribeSubscriber = () => {
            if (!subscriberToUnsubscribe) return;
            
            const row = document.getElementById(`subscriber-row-${subscriberToUnsubscribe.id}`);
            if (row) {
                row.style.opacity = '0.5';
            }
            
            $.ajax({
                url: `/mail-subscribers/${subscriberToUnsubscribe.id}/unsubscribe`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    const unsubscribeModal = bootstrap.Modal.getInstance(document.getElementById('unsubscribeModal'));
                    unsubscribeModal.hide();
                    
                    showAlert('success', response.message || 'Abonné désabonné avec succès');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if (row) {
                        row.style.opacity = '';
                    }
                    showAlert('danger', 'Erreur lors du désabonnement');
                }
            });
        };

        // Resubscribe subscriber
        const resubscribeSubscriber = (subscriberId) => {
            const row = document.getElementById(`subscriber-row-${subscriberId}`);
            if (row) {
                row.style.opacity = '0.5';
            }
            
            if (confirm('Voulez-vous réabonner cet abonné ?')) {
                $.ajax({
                    url: `/mail-subscribers/${subscriberId}/resubscribe`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showAlert('success', response.message || 'Abonné réabonné avec succès');
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: function(xhr) {
                        if (row) {
                            row.style.opacity = '';
                        }
                        showAlert('danger', 'Erreur lors du réabonnement');
                    }
                });
            } else {
                if (row) {
                    row.style.opacity = '';
                }
            }
        };

        // Show delete confirmation
        const showDeleteConfirmation = (subscriberId) => {
            const subscriber = allSubscribers.find(s => s.id === subscriberId);
            
            if (!subscriber) return;
            
            subscriberToDelete = subscriber;
            
            document.getElementById('deleteInfo').innerHTML = `
                <div class="subscriber-display">
                    <div class="subscriber-avatar-lg" style="background: ${getAvatarColor(subscriber.email)}">
                        ${getInitials(subscriber.prenom, subscriber.nom)}
                    </div>
                    <div class="subscriber-details">
                        <div class="subscriber-fullname">${subscriber.prenom} ${subscriber.nom}</div>
                        <div class="subscriber-email">${subscriber.email}</div>
                    </div>
                </div>
            `;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        };

        // Delete subscriber
        const deleteSubscriber = () => {
            if (!subscriberToDelete) return;
            
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;"></span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression en cours...
            `;
            deleteBtn.disabled = true;
            
            const row = document.getElementById(`subscriber-row-${subscriberToDelete.id}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            $.ajax({
                url: `/mail-subscribers/${subscriberToDelete.id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    showAlert('success', response.message || 'Abonné supprimé avec succès');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    showAlert('danger', 'Erreur lors de la suppression');
                },
                complete: function() {
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                    subscriberToDelete = null;
                }
            });
        };

        // Helper functions
        const getAvatarColor = (email) => {
            let hash = 0;
            for (let i = 0; i < email.length; i++) {
                hash = email.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
                '#9b59b6', '#3498db', '#1abc9c', '#e74c3c'
            ];
            
            return colors[Math.abs(hash) % colors.length];
        };

        const getInitials = (prenom, nom) => {
            const first = prenom ? prenom[0] : '';
            const last = nom ? nom[0] : '';
            return (first + last).toUpperCase() || '?';
        };

        const getEngagementColor = (rate) => {
            if (rate < 20) return '#ef476f';
            if (rate < 50) return '#ffd166';
            return '#06b48a';
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
        /* Subscribers specific styles */
        .subscriber-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .subscriber-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .subscriber-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .subscriber-details {
            display: flex;
            flex-direction: column;
        }

        .subscriber-name {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .subscriber-email {
            font-size: 0.85rem;
            color: #666;
        }

        .etablissement-info {
            display: flex;
            flex-direction: column;
        }

        .etablissement-name {
            font-weight: 500;
            color: #333;
        }

        .date-info {
            display: flex;
            flex-direction: column;
        }

        .campaigns-count {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .campaigns-count .count {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .engagement-stats {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .engagement-item {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 0.9rem;
            color: #666;
        }

        .engagement-rate {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .subscriber-actions-modern {
            display: flex;
            gap: 5px;
            justify-content: center;
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

        .unsubscribe-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: white;
        }

        .unsubscribe-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #f39c12);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
            color: white;
        }

        .resubscribe-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .resubscribe-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
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

        .subscriber-display {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 15px 0;
        }

        .subscriber-fullname {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 5px;
        }

        .subscriber-etablissement {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

        .card-footer-modern {
            padding: 15px 20px;
            border-top: 1px solid #eaeaea;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .bulk-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .selected-count {
            font-weight: 600;
            color: var(--primary-color);
        }

        .bulk-buttons {
            display: flex;
            gap: 8px;
        }

        .empty-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
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
            .subscriber-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .engagement-stats {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
@endsection