@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">{{ $subscriber->prenom }} {{ $subscriber->nom }}</h1>
                        <p class="page-subtitle-modern">{{ $subscriber->email }}</p>
                    </div>
                </div>
                <div class="page-header-right">
                    <a href="{{ route('mail-subscribers.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche - Profil et informations -->
            <div class="col-lg-4">
                <!-- Carte Profil -->
                <div class="card-modern">
                    <div class="card-body-modern text-center">
                        <div class="profile-avatar" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getAvatarColor($subscriber->email) }}">
                            {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($subscriber->nom, $subscriber->prenom) }}
                        </div>
                        <h3 class="profile-name">{{ $subscriber->prenom }} {{ $subscriber->nom }}</h3>
                        <p class="profile-email">{{ $subscriber->email }}</p>
                        
                        <div class="profile-status">
                            @if($subscriber->is_subscribed)
                                <span class="badge-active"><i class="fas fa-circle"></i> Abonné actif</span>
                            @else
                                <span class="badge-inactive"><i class="fas fa-circle"></i> Désabonné</span>
                                @if($subscriber->unsubscribed_at)
                                    <small class="d-block text-muted mt-1">
                                        depuis le {{ $subscriber->unsubscribed_at->format('d/m/Y') }}
                                    </small>
                                @endif
                            @endif
                        </div>
                        
                        @if($subscriber->etablissement)
                            <div class="profile-etablissement">
                                <i class="fas fa-building"></i>
                                <span>{{ $subscriber->etablissement->name }}</span>
                                @if($subscriber->etablissement->ville)
                                    <small class="text-muted"> - {{ $subscriber->etablissement->ville }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-footer-modern">
                        <div class="profile-actions">
                            <a href="{{ route('mail-subscribers.edit', $subscriber) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            @if($subscriber->is_subscribed)
                                <button onclick="unsubscribeSubscriber({{ $subscriber->id }})" class="btn-unsubscribe">
                                    <i class="fas fa-ban"></i> Désabonner
                                </button>
                            @else
                                <button onclick="resubscribeSubscriber({{ $subscriber->id }})" class="btn-resubscribe">
                                    <i class="fas fa-undo"></i> Réabonner
                                </button>
                            @endif
                            <button onclick="showDeleteConfirmation({{ $subscriber->id }})" class="btn-delete">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Statistiques -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="card-title-modern">Statistiques d'engagement</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="stats-dashboard">
                            <div class="stat-circle">
                                <div class="circle-progress" data-value="{{ $stats['open_rate'] }}">
                                    <span class="circle-value">{{ $stats['open_rate'] }}%</span>
                                </div>
                                <span class="circle-label">Taux d'ouverture</span>
                            </div>
                            <div class="stat-circle">
                                <div class="circle-progress" data-value="{{ $stats['click_rate'] }}">
                                    <span class="circle-value">{{ $stats['click_rate'] }}%</span>
                                </div>
                                <span class="circle-label">Taux de clic</span>
                            </div>
                        </div>
                        
                        <div class="stats-list mt-4">
                            <div class="stat-row">
                                <span class="stat-label">Campagnes reçues</span>
                                <span class="stat-number">{{ $stats['total_campaigns'] }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Emails ouverts</span>
                                <span class="stat-number success">{{ $stats['opened'] }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Clics effectués</span>
                                <span class="stat-number warning">{{ $stats['clicked'] }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Date d'inscription</span>
                                <span class="stat-number">{{ $subscriber->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($subscriber->unsubscribed_at)
                            <div class="stat-row">
                                <span class="stat-label">Date de désabonnement</span>
                                <span class="stat-number danger">{{ $subscriber->unsubscribed_at->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Historique des campagnes -->
            <div class="col-lg-8">
                <!-- Carte Historique des campagnes -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="card-title-modern">Campagnes reçues</h3>
                        <span class="card-badge">{{ $campaigns->total() }} campagnes</span>
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Campagne</th>
                                        <th>Sujet</th>
                                        <th>Date d'envoi</th>
                                        <th>Ouverture</th>
                                        <th>Clic</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campaigns as $campaign)
                                    <tr>
                                        <td>
                                            <div class="campaign-name-cell">
                                                <i class="fas fa-envelope"></i>
                                                <span>{{ $campaign->nom }}</span>
                                            </div>
                                        </td>
                                        <td class="campaign-subject">{{ Str::limit($campaign->sujet, 40) }}</td>
                                        <td>
                                            @if($campaign->pivot->sent_at)
                                                {{ \Carbon\Carbon::parse($campaign->pivot->sent_at)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($campaign->pivot->opened_at)
                                                <span class="badge-success">
                                                    <i class="fas fa-eye"></i>
                                                    {{ \Carbon\Carbon::parse($campaign->pivot->opened_at)->format('d/m/Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Non ouvert</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($campaign->pivot->clicked_at)
                                                <span class="badge-warning">
                                                    <i class="fas fa-mouse-pointer"></i>
                                                    {{ \Carbon\Carbon::parse($campaign->pivot->clicked_at)->format('d/m/Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Non cliqué</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($campaign->pivot->opened_at && $campaign->pivot->clicked_at)
                                                <span class="badge-active">Engagé</span>
                                            @elseif($campaign->pivot->opened_at)
                                                <span class="badge-warning">Ouvert</span>
                                            @elseif($campaign->pivot->sent_at)
                                                <span class="badge-secondary">Envoyé</span>
                                            @else
                                                <span class="badge-secondary">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <h4>Aucune campagne reçue</h4>
                                                <p>Cet abonné n'a encore reçu aucune campagne.</p>
                                                <a href="{{ route('mail-campaigns.create') }}" class="btn-primary">
                                                    <i class="fas fa-plus-circle"></i> Créer une campagne
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($campaigns->hasPages())
                    <div class="card-footer-modern">
                        <div class="pagination-modern">
                            {{ $campaigns->links() }}
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Carte Dernières activités -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3 class="card-title-modern">Dernières activités</h3>
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="activity-timeline">
                            @forelse($recentActivities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        @if($activity->event_type === 'open')
                                            <i class="fas fa-eye text-success"></i>
                                        @elseif($activity->event_type === 'click')
                                            <i class="fas fa-mouse-pointer text-warning"></i>
                                        @else
                                            <i class="fas fa-envelope text-info"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">
                                            @if($activity->event_type === 'open')
                                                Ouverture d'email
                                            @elseif($activity->event_type === 'click')
                                                Clic sur un lien
                                            @else
                                                Réception d'email
                                            @endif
                                        </div>
                                        <div class="timeline-campaign">
                                            @if($activity->campaign)
                                                <i class="fas fa-bullhorn"></i> {{ $activity->campaign->nom }}
                                            @endif
                                        </div>
                                        @if($activity->event_type === 'click' && isset($activity->payload['url']))
                                            <div class="timeline-url">
                                                <i class="fas fa-link"></i> {{ Str::limit($activity->payload['url'], 50) }}
                                            </div>
                                        @endif
                                        <div class="timeline-date">
                                            <i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-activities">
                                    <i class="fas fa-history"></i>
                                    <p>Aucune activité récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                    
                    <div class="subscriber-info-display">
                        <div class="subscriber-avatar-lg" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getAvatarColor($subscriber->email) }}">
                            {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($subscriber->nom, $subscriber->prenom) }}
                        </div>
                        <div class="subscriber-details">
                            <div class="subscriber-fullname">{{ $subscriber->prenom }} {{ $subscriber->nom }}</div>
                            <div class="subscriber-email">{{ $subscriber->email }}</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Toutes les données associées seront supprimées.
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

    <script>
        let subscriberId = {{ $subscriber->id }};
        
        function unsubscribeSubscriber(id) {
            if (confirm('Désabonner cet abonné ?')) {
                $.post(`/mail-subscribers/${id}/unsubscribe`, {
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    window.location.reload();
                });
            }
        }
        
        function resubscribeSubscriber(id) {
            if (confirm('Réabonner cet abonné ?')) {
                $.post(`/mail-subscribers/${id}/resubscribe`, {
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    window.location.reload();
                });
            }
        }
        
        function showDeleteConfirmation(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }
        
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
            $.ajax({
                url: `/mail-subscribers/${subscriberId}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    window.location.href = '{{ route("mail-subscribers.index") }}';
                }
            });
        });
        
        // Progress circles
        $(document).ready(function() {
            $('.circle-progress').each(function() {
                let value = $(this).data('value');
                let color = value > 50 ? '#06b48a' : value > 20 ? '#ffd166' : '#ef476f';
                $(this).css('background', `conic-gradient(${color} 0% ${value}%, #e2e8f0 ${value}% 100%)`);
            });
        });
    </script>
    <style>
        /* Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 28px 32px;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .page-header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .page-title-modern {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .page-subtitle-modern {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0;
            font-size: 14px;
        }
        
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateX(-3px);
        }
        
        /* Cards */
        .card-modern {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .card-header-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid #eef2f6;
            background: #fafbfc;
        }
        
        .card-header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .card-title-modern {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            flex: 1;
        }
        
        .card-badge {
            background: #eef2ff;
            color: #667eea;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .card-footer-modern {
            padding: 16px 24px;
            border-top: 1px solid #eef2f6;
            background: #fafbfc;
        }
        
        /* Profile Card */
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .profile-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .profile-email {
            color: #64748b;
            margin-bottom: 15px;
        }
        
        .profile-etablissement {
            margin-top: 15px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .profile-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-edit, .btn-unsubscribe, .btn-resubscribe, .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-edit {
            background: #eef2ff;
            color: #667eea;
        }
        
        .btn-unsubscribe {
            background: #fff3e0;
            color: #ffb347;
        }
        
        .btn-resubscribe {
            background: #e8f5e9;
            color: #06b48a;
        }
        
        .btn-delete {
            background: #ffebee;
            color: #ef476f;
        }
        
        .btn-edit:hover, .btn-unsubscribe:hover, .btn-resubscribe:hover, .btn-delete:hover {
            transform: translateY(-2px);
        }
        
        /* Stats */
        .stats-dashboard {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        
        .stat-circle {
            text-align: center;
        }
        
        .circle-progress {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            background: #e2e8f0;
        }
        
        .circle-value {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .circle-label {
            font-size: 12px;
            color: #64748b;
        }
        
        .stats-list {
            border-top: 1px solid #eef2f6;
            padding-top: 16px;
        }
        
        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .stat-row:last-child {
            border-bottom: none;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 13px;
        }
        
        .stat-number {
            font-weight: 600;
            font-size: 14px;
        }
        
        .stat-number.success { color: #06b48a; }
        .stat-number.warning { color: #ffb347; }
        .stat-number.danger { color: #ef476f; }
        
        /* Badges */
        .badge-active, .badge-inactive, .badge-success, .badge-warning, .badge-secondary {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .badge-active { background: #e8f5e9; color: #06b48a; }
        .badge-inactive { background: #ffebee; color: #ef476f; }
        .badge-success { background: #e8f5e9; color: #06b48a; }
        .badge-warning { background: #fff3e0; color: #ffb347; }
        .badge-secondary { background: #f1f5f9; color: #64748b; }
        
        .campaign-name-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .campaign-name-cell i {
            color: #667eea;
        }
        
        .campaign-subject {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Timeline */
        .activity-timeline {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .timeline-item {
            display: flex;
            gap: 15px;
            padding: 15px 20px;
            border-bottom: 1px solid #eef2f6;
            transition: background 0.2s;
        }
        
        .timeline-item:hover {
            background: #f8fafc;
        }
        
        .timeline-icon {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .timeline-content {
            flex: 1;
        }
        
        .timeline-title {
            font-weight: 600;
            font-size: 14px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .timeline-campaign {
            font-size: 12px;
            color: #667eea;
            margin-bottom: 2px;
        }
        
        .timeline-url {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 4px;
            word-break: break-all;
        }
        
        .timeline-date {
            font-size: 11px;
            color: #94a3b8;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 15px;
        }
        
        .empty-activities {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        
        .empty-activities i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .subscriber-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
        
        .subscriber-fullname {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .subscriber-email {
            font-size: 12px;
            color: #64748b;
        }
        
        .subscriber-info-display {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            margin: 15px 0;
        }
        
        .delete-icon {
            font-size: 48px;
            color: #ef476f;
            margin-bottom: 20px;
        }
        
        .delete-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .delete-message {
            color: #64748b;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header-modern {
                padding: 20px;
            }
            
            .page-header-left {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }
            
            .stats-dashboard {
                flex-direction: column;
                align-items: center;
            }
            
            .profile-actions {
                flex-direction: column;
            }
            
            .profile-actions a, .profile-actions button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection