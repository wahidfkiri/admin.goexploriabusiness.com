@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Welcome Section -->
        <div class="welcome-card">
            <h2 class="welcome-title">Bonjour, {{auth()->user()->name}} 👋</h2>
            <p class="welcome-text">
                Gérez votre plateforme GO EXPLORIA, créez des sites web modernes, et suivez les performances de vos clients. 
                Vous avez {{ $totalMessagesNonLus ?? 3 }} nouveaux messages et {{ $totalTasksEnAttente ?? 12 }} tâches en attente aujourd'hui.
            </p>
            <button class="btn btn-light">
                <i class="fas fa-rocket me-2"></i>Commencer maintenant
            </button>
        </div>
        
        <!-- Stats Row -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color);">
                            <i class="fas fa-globe"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalEtablissements ?? 0 }}</div>
                    <div class="stats-label">Établissements inscrits</div>
                    <div class="stats-change positive">
                        <i class="fas fa-arrow-up"></i> 8.5% ce mois
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(6, 214, 160, 0.1); color: var(--accent-color);">
                            <i class="fas fa-flag"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalPays ?? 0 }}</div>
                    <div class="stats-label">Pays enregistrés</div>
                    <div class="stats-change positive">
                        <i class="fas fa-arrow-up"></i> 12.3% depuis hier
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(255, 209, 102, 0.1); color: #e6a100;">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalProjetsEnCours ?? 0 }}</div>
                    <div class="stats-label">Projets en cours</div>
                    <div class="stats-change positive">
                        <i class="fas fa-arrow-up"></i> 5.7% cette semaine
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(239, 71, 111, 0.1); color: var(--danger-color);">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalTasks ?? 0 }}</div>
                    <div class="stats-label">Tasks totales</div>
                    <div class="stats-change negative">
                        <i class="fas fa-arrow-down"></i> 2.1% cette semaine
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Deuxième ligne de stats -->
        <div class="row mt-3">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="fas fa-users"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalUsers ?? 0 }}</div>
                    <div class="stats-label">Tous les utilisateurs</div>
                    <div class="stats-change positive">
                        <i class="fas fa-arrow-up"></i> 3.2% ce mois
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-header">
                        <div class="stats-icon-container" style="background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <button class="stats-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="stats-value">{{ $totalTemplates ?? 0 }}</div>
                    <div class="stats-label">Templates disponibles</div>
                    <div class="stats-change positive">
                        <i class="fas fa-arrow-up"></i> 4 nouveaux ce mois
                    </div>
                </div>
            </div>
            
            <!-- Vous pouvez ajouter d'autres stats ici si nécessaire -->
        </div>
        
        <!-- Activity & Projects Row -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="activity-card">
                    <h5 class="section-title">
                        <i class="fas fa-history"></i>
                        Activité récente
                    </h5>
                    
                    <div class="activity-list">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="activity-item">
                                <div class="activity-icon" style="background-color: {{ $activity['color'] ?? 'var(--primary-color)' }};">
                                    <i class="fas {{ $activity['icon'] ?? 'fa-plus' }}"></i>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-title">{{ $activity['title'] ?? 'Nouvelle activité' }}</div>
                                    <div class="activity-desc">{{ $activity['description'] ?? '' }}</div>
                                </div>
                                <div class="activity-time">{{ $activity['time'] ?? 'Il y a quelques minutes' }}</div>
                            </div>
                        @empty
                            <div class="activity-item">
                                <div class="activity-info">
                                    <div class="activity-title">Aucune activité récente</div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="activity-card">
                    <h5 class="section-title">
                        <i class="fas fa-tasks"></i>
                        Projets en cours
                    </h5>
                    
                    @php
function getProgressColor($progress) {
    if ($progress >= 100) return '#4361ee';
    if ($progress >= 50) return '#06d6a0';
    if ($progress >= 25) return '#e6a100';
    return '#ef476f';
}
@endphp

<!-- Dans votre boucle -->
@forelse($projects ?? [] as $project)
    <div class="project-card">
        <div class="project-header">
            <div>
                <div class="project-title">{{ $project->nom ?? $project->title ?? 'Projet sans nom' }}</div>
                <div class="project-desc">{!! substr($project->description, 0, 30) ?? $project->etablissement->nom ?? 'Description du projet' !!}</div>
            </div>
            <span class="project-status status-{{ $project->statut ?? 'active' }}">
                {{ $project->statut ?? 'En cours' }}
            </span>
        </div>
        <div class="project-progress">
            <div class="progress-info">
                <span>Progression</span>
                <span>{{ $project->progression ?? 75 }}%</span>
            </div>
            <div class="progress-bar">
                <!-- CORRECTION: Enlever $this-> -->
                <div class="progress-fill" style="width: {{ $project->progression ?? 75 }}%; background-color: {{ getProgressColor($project->progression ?? 75) }};"></div>
            </div>
        </div>
    </div>
@empty
    <div class="project-card">
        <div class="project-header">
            <div>
                <div class="project-title">Aucun projet en cours</div>
            </div>
        </div>
    </div>
@endforelse
                </div>
            </div>
        </div>
        
        <!-- Le reste de votre code... -->
    </main>
@endsection
