@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-calendar-alt"></i></span>
                Calendrier des Projets
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-outline-primary" id="calendarTodayBtn">
                        <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                    </button>
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-view="dayGridMonth">Mois</a></li>
                        <li><a class="dropdown-item" href="#" data-view="timeGridWeek">Semaine</a></li>
                        <li><a class="dropdown-item" href="#" data-view="timeGridDay">Jour</a></li>
                        <li><a class="dropdown-item" href="#" data-view="listMonth">Liste</a></li>
                    </ul>
                </div>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Projet
                </a>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres du Calendrier</h3>
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
                    <label for="filterClient" class="form-label-modern">Client</label>
                    <select class="form-select-modern" id="filterClient">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterUser" class="form-label-modern">Responsable</label>
                    <select class="form-select-modern" id="filterUser">
                        <option value="">Tous</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterStatus" class="form-label-modern">Statut Projet</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterActive" class="form-label-modern">État</label>
                    <select class="form-select-modern" id="filterActive">
                        <option value="">Tous</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDisplay" class="form-label-modern">Afficher</label>
                    <select class="form-select-modern" id="filterDisplay">
                        <option value="all">Tous les événements</option>
                        <option value="projects">Projets uniquement</option>
                        <option value="tasks">Tâches uniquement</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalProjects">0</div>
                        <div class="stats-label-modern">Projets</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeProjects">0</div>
                        <div class="stats-label-modern">En cours</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-play-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalTasks">0</div>
                        <div class="stats-label-modern">Tâches</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="pendingTasks">0</div>
                        <div class="stats-label-modern">Échéances</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="overdueItems">0</div>
                        <div class="stats-label-modern">En retard</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #dc3545, #b02a37);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Legend Section -->
        <div class="legend-container">
            <div class="legend-title">
                <i class="fas fa-tag me-2"></i>Légende
            </div>
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-color" style="background: #4a6cf7;"></span>
                    <span>Planification</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #06b48a;"></span>
                    <span>En cours</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #ffb347;"></span>
                    <span>En pause</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #28a745;"></span>
                    <span>Terminé</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #dc3545;"></span>
                    <span>Annulé</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: #b87dc9;"></span>
                    <span>Tâche</span>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt me-2"></i>Planning des Projets
                </h3>
                <div class="calendar-navigation">
                    <button class="btn btn-sm btn-outline-secondary" id="calendarPrevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="calendar-title" id="calendarTitle">Chargement...</span>
                    <button class="btn btn-sm btn-outline-secondary" id="calendarNextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement du calendrier...</span>
                    </div>
                </div>
                
                <!-- Calendar Container -->
                <div id="calendarContainer" style="min-height: 600px; display: none;"></div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun événement trouvé</h3>
                    <p class="empty-text-modern">Aucun projet ou tâche n'est planifié pour cette période.</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer un projet
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ route('projects.create') }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
    <!-- EVENT DETAILS MODAL -->
    <div class="modal fade event-detail-modal" id="eventDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalTitle">Détails de l'événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Event details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Fermer
                    </button>
                    <a href="#" class="btn btn-primary" id="eventModalLink">
                        <i class="fas fa-eye me-2"></i>Voir détails
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Configuration
        let calendar = null;
        let currentFilters = {};
        let currentView = 'dayGridMonth';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            initializeCalendar();
            loadStatistics();
            setupEventListeners();
        });

        // AJAX setup
        const setupAjax = () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        };

        // Initialize FullCalendar
        const initializeCalendar = () => {
            const calendarEl = document.getElementById('calendarContainer');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: currentView,
                locale: 'fr',
                firstDay: 1, // Lundi
                height: 'auto',
                headerToolbar: false, // We'll use custom controls
                
                // Event sources
                events: function(fetchInfo, successCallback, failureCallback) {
                    showLoading();
                    
                    const filters = {
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr,
                        ...currentFilters,
                        events: true
                    };
                    
                    $.ajax({
                        url: '{{ route("projects.calendar") }}',
                        type: 'GET',
                        data: filters,
                        success: function(events) {
                            hideLoading();
                            successCallback(events);
                            
                            // Update empty state
                            if (events.length === 0) {
                                document.getElementById('emptyState').style.display = 'block';
                                calendarEl.style.display = 'none';
                            } else {
                                document.getElementById('emptyState').style.display = 'none';
                                calendarEl.style.display = 'block';
                            }
                            
                            // Update statistics
                            updateEventStats(events);
                        },
                        error: function(xhr) {
                            hideLoading();
                            failureCallback(xhr);
                            showError('Erreur lors du chargement des événements');
                        }
                    });
                },
                
                // Event rendering
                eventDidMount: function(info) {
                    // Add tooltip
                    if (info.event.extendedProps.description) {
                        info.el.setAttribute('title', info.event.extendedProps.description);
                    }
                    
                    // Add custom class based on type
                    if (info.event.extendedProps.type === 'task') {
                        info.el.classList.add('calendar-event-task');
                    }
                    
                    // Add overdue class if applicable
                    if (info.event.extendedProps.is_overdue) {
                        info.el.classList.add('calendar-event-overdue');
                    }
                },
                
                // Event click
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                
                // Date click
                dateClick: function(info) {
                    // You could add a quick create form here
                    console.log('Date clicked:', info.dateStr);
                },
                
                // Loading state
                loading: function(isLoading) {
                    if (isLoading) {
                        showLoading();
                    } else {
                        hideLoading();
                        updateCalendarTitle();
                    }
                }
            });
            
            calendar.render();
            document.getElementById('calendarContainer').style.display = 'block';
            hideLoading();
        };

        // Load statistics
        const loadStatistics = () => {
            $.ajax({
                url: '{{ route("projects.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalProjects').textContent = stats.total || 0;
                        document.getElementById('activeProjects').textContent = stats.in_progress || 0;
                        document.getElementById('totalTasks').textContent = stats.total_tasks || 0;
                        document.getElementById('pendingTasks').textContent = stats.pending_tasks || 0;
                        document.getElementById('overdueItems').textContent = stats.overdue_projects || 0;
                    }
                },
                error: function() {
                    // Default values
                    document.getElementById('totalProjects').textContent = '0';
                    document.getElementById('activeProjects').textContent = '0';
                    document.getElementById('totalTasks').textContent = '0';
                    document.getElementById('pendingTasks').textContent = '0';
                    document.getElementById('overdueItems').textContent = '0';
                }
            });
        };

        // Update statistics from calendar events
        const updateEventStats = (events) => {
            const projects = events.filter(e => e.extendedProps?.type === 'project');
            const tasks = events.filter(e => e.extendedProps?.type === 'task');
            const overdue = events.filter(e => e.extendedProps?.is_overdue);
            
            document.getElementById('totalProjects').textContent = projects.length;
            document.getElementById('totalTasks').textContent = tasks.length;
            document.getElementById('overdueItems').textContent = overdue.length;
        };

        // Update calendar title
        const updateCalendarTitle = () => {
            if (calendar) {
                const title = calendar.view.title;
                document.getElementById('calendarTitle').textContent = title;
            }
        };

        // Show event details
        const showEventDetails = (event) => {
            const props = event.extendedProps;
            const isProject = props.type === 'project';
            
            let statusClass = '';
            if (props.status_color) {
                statusClass = `badge-${props.status_color}`;
            }
            
            const modalBody = document.getElementById('eventModalBody');
            const modalTitle = document.getElementById('eventModalTitle');
            const modalLink = document.getElementById('eventModalLink');
            
            modalTitle.textContent = event.title;
            
            if (isProject) {
                modalLink.href = props.url;
                
                modalBody.innerHTML = `
                    <div class="event-detail">
                        <div class="event-header" style="background: ${event.backgroundColor}; padding: 15px; border-radius: 10px; color: white; margin-bottom: 20px;">
                            <h4>${event.title}</h4>
                            ${props.contract_number ? `<p class="mb-0"><i class="fas fa-file-contract me-2"></i>Contrat: ${props.contract_number}</p>` : ''}
                        </div>
                        
                        <div class="event-info-grid">
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-building me-2"></i>Client
                                </div>
                                <div class="event-info-value">${props.client || 'Non assigné'}</div>
                            </div>
                            
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-user me-2"></i>Responsable
                                </div>
                                <div class="event-info-value">${props.user || 'Non assigné'}</div>
                            </div>
                            
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-tag me-2"></i>Statut
                                </div>
                                <div class="event-info-value">
                                    <span class="badge ${statusClass}">${props.status}</span>
                                </div>
                            </div>
                            
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-calendar me-2"></i>Période
                                </div>
                                <div class="event-info-value">
                                    ${formatDateRange(event.start, event.end)}
                                </div>
                            </div>
                            
                            ${props.progress !== undefined ? `
                            <div class="event-info-item full-width">
                                <div class="event-info-label">
                                    <i class="fas fa-chart-line me-2"></i>Avancement
                                </div>
                                <div class="event-info-value">
                                    <div class="progress-modern" style="height: 10px; width: 100%;">
                                        <div class="progress-bar-modern" style="width: ${props.progress}%; background: ${getProgressColor(props.progress)};"></div>
                                    </div>
                                    <span class="progress-percentage">${props.progress}%</span>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${props.description ? `
                            <div class="event-info-item full-width">
                                <div class="event-info-label">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </div>
                                <div class="event-info-value description-text">
                                    ${props.description}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            } else {
                // Task details
                modalLink.href = props.url;
                
                modalBody.innerHTML = `
                    <div class="event-detail">
                        <div class="event-header" style="background: ${event.backgroundColor}; padding: 15px; border-radius: 10px; color: white; margin-bottom: 20px;">
                            <h4>${event.title}</h4>
                            <p class="mb-0"><i class="fas fa-project-diagram me-2"></i>Projet: ${props.project_name}</p>
                        </div>
                        
                        <div class="event-info-grid">
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-user me-2"></i>Assigné à
                                </div>
                                <div class="event-info-value">${props.assigned_to || 'Non assigné'}</div>
                            </div>
                            
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-tag me-2"></i>Statut
                                </div>
                                <div class="event-info-value">
                                    <span class="badge ${statusClass}">${props.status}</span>
                                </div>
                            </div>
                            
                            <div class="event-info-item">
                                <div class="event-info-label">
                                    <i class="fas fa-calendar me-2"></i>Date d'échéance
                                </div>
                                <div class="event-info-value">
                                    ${formatDate(event.start)}
                                </div>
                            </div>
                            
                            ${props.description ? `
                            <div class="event-info-item full-width">
                                <div class="event-info-label">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </div>
                                <div class="event-info-value description-text">
                                    ${props.description}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
            modal.show();
        };

        // Format date
        const formatDate = (dateStr) => {
            if (!dateStr) return 'Non définie';
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        };

        // Format date range
        const formatDateRange = (start, end) => {
            if (!start) return 'Non définie';
            if (!end) return formatDate(start);
            
            const startDate = new Date(start);
            const endDate = new Date(end);
            endDate.setDate(endDate.getDate() - 1); // Adjust for FullCalendar exclusive end
            
            return `${startDate.toLocaleDateString('fr-FR')} - ${endDate.toLocaleDateString('fr-FR')}`;
        };

        // Get progress color
        const getProgressColor = (progress) => {
            if (progress < 30) return '#ef476f';
            if (progress < 70) return '#ffd166';
            return '#06b48a';
        };

        // Show loading state
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
        };

        // Hide loading state
        const hideLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'none';
        };

        // Show error
        const showError = (message) => {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-custom-modern alert-dismissible fade show';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        };

        // Setup event listeners
        const setupEventListeners = () => {
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
                applyFiltersBtn.addEventListener('click', () => {
                    currentFilters = {
                        client_id: document.getElementById('filterClient').value,
                        user_id: document.getElementById('filterUser').value,
                        status: document.getElementById('filterStatus').value,
                        is_active: document.getElementById('filterActive').value,
                        display: document.getElementById('filterDisplay').value,
                    };
                    
                    if (calendar) {
                        calendar.refetchEvents();
                    }
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterClient').value = '';
                    document.getElementById('filterUser').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterActive').value = '';
                    document.getElementById('filterDisplay').value = 'all';
                    currentFilters = {};
                    
                    if (calendar) {
                        calendar.refetchEvents();
                    }
                });
            }
            
            // Calendar navigation
            document.getElementById('calendarTodayBtn').addEventListener('click', () => {
                if (calendar) {
                    calendar.today();
                    updateCalendarTitle();
                }
            });
            
            document.getElementById('calendarPrevBtn').addEventListener('click', () => {
                if (calendar) {
                    calendar.prev();
                    updateCalendarTitle();
                }
            });
            
            document.getElementById('calendarNextBtn').addEventListener('click', () => {
                if (calendar) {
                    calendar.next();
                    updateCalendarTitle();
                }
            });
            
            // View change
            document.querySelectorAll('[data-view]').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const view = e.target.closest('.dropdown-item').dataset.view;
                    if (calendar && view) {
                        currentView = view;
                        calendar.changeView(view);
                        updateCalendarTitle();
                    }
                });
            });
        };
    </script>

    <style>
        /* Styles spécifiques pour la page calendrier */
        .legend-container {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .legend-title {
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }
        
        .legend-items {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        
        .calendar-navigation {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .calendar-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            min-width: 200px;
            text-align: center;
        }
        
        /* FullCalendar custom styles */
        .fc {
            font-family: inherit;
        }
        
        .fc .fc-toolbar {
            display: none; /* We use custom navigation */
        }
        
        .fc .fc-daygrid-day-frame {
            min-height: 100px;
        }
        
        .fc .fc-daygrid-day-number {
            color: #333;
            font-weight: 500;
        }
        
        .fc .fc-col-header-cell-cushion {
            color: #666;
            font-weight: 600;
            text-decoration: none;
        }
        
        .fc .fc-event {
            border: none;
            padding: 3px 5px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-bottom: 2px;
        }
        
        .fc .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .fc .fc-event-title {
            font-weight: 500;
        }
        
        .fc .fc-day-today {
            background: rgba(74, 108, 247, 0.05);
        }
        
        .calendar-event-task {
            opacity: 0.9;
        }
        
        .calendar-event-task .fc-event-title:before {
            content: '📋 ';
        }
        
        .calendar-event-overdue {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 5px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }
        
        /* Event detail modal */
        .event-detail-modal .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .event-detail-modal .modal-header {
            background: linear-gradient(135deg, #4a6cf7, #3a56e4);
            color: white;
            border-bottom: none;
            padding: 20px;
        }
        
        .event-detail-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .event-detail-modal .modal-body {
            padding: 20px;
        }
        
        .event-detail-modal .modal-footer {
            border-top: 1px solid #eaeaea;
            padding: 20px;
        }
        
        .event-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .event-info-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
        }
        
        .event-info-item.full-width {
            grid-column: 1 / -1;
        }
        
        .event-info-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .event-info-value {
            font-weight: 600;
            color: #333;
        }
        
        .description-text {
            max-height: 150px;
            overflow-y: auto;
            line-height: 1.6;
        }
        
        .progress-percentage {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
            display: block;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .legend-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .calendar-navigation {
                flex-direction: column;
                width: 100%;
            }
            
            .calendar-title {
                width: 100%;
            }
            
            .event-info-grid {
                grid-template-columns: 1fr;
            }
            
            .fc .fc-daygrid-day-frame {
                min-height: 80px;
            }
            
            .fc .fc-event {
                font-size: 0.75rem;
                padding: 2px;
            }
        }
        
        /* Print styles */
        @media print {
            .page-actions,
            .filter-section-modern,
            .legend-container,
            .stats-grid,
            .fab-modern,
            .modal {
                display: none !important;
            }
            
            .fc {
                font-size: 12pt;
            }
            
            .fc .fc-event {
                background: #000 !important;
                color: #000 !important;
                border: 1px solid #ccc !important;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection