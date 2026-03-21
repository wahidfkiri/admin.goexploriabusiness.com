@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">{{ $campaign->nom }}</h1>
                        <p class="page-subtitle-modern">Détails et statistiques de la campagne</p>
                    </div>
                </div>
                <div class="page-header-right">
                    <a href="{{ route('mail-campaigns.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche - 8 colonnes -->
            <div class="col-lg-8">
                <!-- Carte Informations -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="card-title-modern">Informations de la campagne</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Nom</span>
                                <span class="info-value">{{ $campaign->nom }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Sujet</span>
                                <span class="info-value">{{ $campaign->sujet }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Statut</span>
                                <span class="info-value">
                                    @if($campaign->status === 'draft')
                                        <span class="badge-draft"><i class="fas fa-pencil-alt"></i> Brouillon</span>
                                    @elseif($campaign->status === 'scheduled')
                                        <span class="badge-scheduled"><i class="fas fa-clock"></i> Planifiée</span>
                                    @elseif($campaign->status === 'sending')
                                        <span class="badge-sending"><i class="fas fa-spinner"></i> En cours</span>
                                    @elseif($campaign->status === 'sent')
                                        <span class="badge-sent"><i class="fas fa-check-circle"></i> Envoyée</span>
                                    @elseif($campaign->status === 'cancelled')
                                        <span class="badge-cancelled"><i class="fas fa-times-circle"></i> Annulée</span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Thème</span>
                                <span class="info-value">
                                    @php
                                        $themes = [
                                            'modern' => ['name' => 'Moderne', 'icon' => 'fas fa-chart-line', 'color' => '#667eea'],
                                            'elegant' => ['name' => 'Élégant', 'icon' => 'fas fa-feather-alt', 'color' => '#8b6b4d'],
                                            'dynamic' => ['name' => 'Dynamique', 'icon' => 'fas fa-bolt', 'color' => '#ff6b6b'],
                                        ];
                                        $theme = $campaign->options['theme'] ?? 'modern';
                                    @endphp
                                    <span class="theme-badge" style="background: {{ $themes[$theme]['color'] ?? '#667eea' }}20; color: {{ $themes[$theme]['color'] ?? '#667eea' }};">
                                        <i class="{{ $themes[$theme]['icon'] ?? 'fas fa-palette' }}"></i> {{ $themes[$theme]['name'] ?? 'Moderne' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date de création</span>
                                <span class="info-value">{{ $campaign->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($campaign->scheduled_at)
                            <div class="info-item">
                                <span class="info-label">Planifiée le</span>
                                <span class="info-value">{{ $campaign->scheduled_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                            @if($campaign->sent_at)
                            <div class="info-item">
                                <span class="info-label">Envoyée le</span>
                                <span class="info-value">{{ $campaign->sent_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                            <div class="info-item">
                                <span class="info-label">Créé par</span>
                                <span class="info-value">
                                    <div class="user-mini">
                                        <div class="user-avatar-mini">{{ substr($campaign->createdBy->name ?? 'Admin', 0, 2) }}</div>
                                        <span>{{ $campaign->createdBy->name ?? 'Administrateur' }}</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="section-subtitle">Contenu de l'email</h4>
                            <div class="content-preview">
                                {!! $campaign->contenu !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Liste des destinataires -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="card-title-modern">Destinataires</h3>
                        <span class="card-badge">{{ $stats['total'] ?? 0 }} abonnés</span>
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Abonné</th>
                                        <th>Email</th>
                                        <th>Statut d'envoi</th>
                                        <th>Ouverture</th>
                                        <th>Clic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campaign->subscribers as $subscriber)
                                    <tr>
                                        <td>
                                            <div class="subscriber-mini">
                                                <div class="subscriber-avatar-mini" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getAvatarColor($subscriber->email) }}">
                                                    {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($subscriber->nom, $subscriber->prenom) }}
                                                </div>
                                                <span>{{ $subscriber->prenom ?? '' }} {{ $subscriber->nom ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>
                                            @if($subscriber->pivot->sent_at)
                                                <span class="badge-success"><i class="fas fa-check"></i> Envoyé</span>
                                            @elseif($subscriber->pivot->failed_at)
                                                <span class="badge-danger"><i class="fas fa-exclamation"></i> Échec</span>
                                            @else
                                                <span class="badge-warning"><i class="fas fa-clock"></i> En attente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscriber->pivot->opened_at)
                                                <span class="badge-success">{{ \Carbon\Carbon::parse($subscriber->pivot->opened_at)->format('d/m H:i') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscriber->pivot->clicked_at)
                                                <span class="badge-success">{{ \Carbon\Carbon::parse($subscriber->pivot->clicked_at)->format('d/m H:i') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty-small">
                                                <i class="fas fa-inbox"></i>
                                                <p>Aucun destinataire pour cette campagne</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - 4 colonnes -->
            <div class="col-lg-4">
                <!-- Carte Statistiques -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="card-title-modern">Statistiques</h3>
                    </div>
                    <div class="card-body-modern">
                        @php
                            $total = $stats['total'] ?? 0;
                            $sent = $stats['sent'] ?? 0;
                            $opened = $stats['opened'] ?? 0;
                            $clicked = $stats['clicked'] ?? 0;
                            $openRate = $sent > 0 ? round(($opened / $sent) * 100) : 0;
                            $clickRate = $sent > 0 ? round(($clicked / $sent) * 100) : 0;
                        @endphp
                        
                        <div class="stats-dashboard">
                            <div class="stat-circle">
                                <div class="circle-progress" data-value="{{ $openRate }}">
                                    <span class="circle-value">{{ $openRate }}%</span>
                                </div>
                                <span class="circle-label">Taux d'ouverture</span>
                            </div>
                            <div class="stat-circle">
                                <div class="circle-progress" data-value="{{ $clickRate }}">
                                    <span class="circle-value">{{ $clickRate }}%</span>
                                </div>
                                <span class="circle-label">Taux de clic</span>
                            </div>
                        </div>
                        
                        <div class="stats-list mt-4">
                            <div class="stat-row">
                                <span class="stat-label">Total destinataires</span>
                                <span class="stat-number">{{ $total }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Envoyés avec succès</span>
                                <span class="stat-number success">{{ $sent }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Ouvertures</span>
                                <span class="stat-number info">{{ $opened }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Clics</span>
                                <span class="stat-number warning">{{ $clicked }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Échecs</span>
                                <span class="stat-number danger">{{ $stats['failed'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Actions -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="card-title-modern">Actions</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="action-buttons-vertical">
                            @if($campaign->status === 'draft')
                                <button onclick="sendCampaign({{ $campaign->id }})" class="action-btn send">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Envoyer maintenant</span>
                                </button>
                                <a href="{{ route('mail-campaigns.edit', $campaign) }}" class="action-btn edit">
                                    <i class="fas fa-edit"></i>
                                    <span>Modifier la campagne</span>
                                </a>
                            @endif
                            
                            @if($campaign->status === 'sent')
                                <button onclick="duplicateCampaign({{ $campaign->id }})" class="action-btn duplicate">
                                    <i class="fas fa-copy"></i>
                                    <span>Dupliquer cette campagne</span>
                                </button>
                            @endif
                            
                            <button onclick="previewCampaign({{ $campaign->id }})" class="action-btn preview">
                                <i class="fas fa-eye"></i>
                                <span>Aperçu de l'email</span>
                            </button>
                            
                            <button onclick="exportStats({{ $campaign->id }})" class="action-btn export">
                                <i class="fas fa-download"></i>
                                <span>Exporter les statistiques</span>
                            </button>
                            
                            @if(in_array($campaign->status, ['draft', 'scheduled']))
                                <button onclick="deleteCampaign({{ $campaign->id }})" class="action-btn delete">
                                    <i class="fas fa-trash"></i>
                                    <span>Supprimer la campagne</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sendCampaign(id) {
            if(confirm('⚠️ Envoyer cette campagne immédiatement ?')) {
                $.post(`/mail-campaigns/${id}/send`, {
                    _token: '{{ csrf_token() }}'
                }).done(() => {
                    showNotification('Campagne envoyée avec succès', 'success');
                    setTimeout(() => location.reload(), 1500);
                }).fail(() => {
                    showNotification('Erreur lors de l\'envoi', 'danger');
                });
            }
        }
        
        function duplicateCampaign(id) {
            $.post(`/mail-campaigns/${id}/duplicate`, {
                _token: '{{ csrf_token() }}'
            }).done((response) => {
                showNotification('Campagne dupliquée', 'success');
                setTimeout(() => {
                    window.location.href = `/mail-campaigns/${response.id}/edit`;
                }, 1000);
            });
        }
        
        function previewCampaign(id) {
            window.open(`/mail-campaigns/${id}/preview`, '_blank', 'width=800,height=600');
        }
        
        function exportStats(id) {
            window.location.href = `/mail-campaigns/${id}/export/opens`;
        }
        
        function deleteCampaign(id) {
            if(confirm('Supprimer définitivement cette campagne ?')) {
                $.ajax({
                    url: `/mail-campaigns/${id}`,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'}
                }).done(() => {
                    showNotification('Campagne supprimée', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("mail-campaigns.index") }}';
                    }, 1000);
                });
            }
        }
        
        function showNotification(message, type) {
            const toast = $(`
                <div class="notification-toast ${type}">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `);
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(300, () => toast.remove()), 3000);
        }
        
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
        
        .card-body-modern {
            padding: 24px;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Badges */
        .badge-draft, .badge-scheduled, .badge-sending, .badge-sent, .badge-cancelled {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-draft { background: #f1f5f9; color: #475569; }
        .badge-scheduled { background: #eef2ff; color: #667eea; }
        .badge-sending { background: #fff3e0; color: #ffb347; }
        .badge-sent { background: #e8f5e9; color: #06b48a; }
        .badge-cancelled { background: #ffebee; color: #ef476f; }
        
        .badge-success, .badge-danger, .badge-warning {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .badge-success { background: #e8f5e9; color: #06b48a; }
        .badge-danger { background: #ffebee; color: #ef476f; }
        .badge-warning { background: #fff3e0; color: #ffb347; }
        
        .theme-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .section-subtitle {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eef2f6;
        }
        
        .content-preview {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .user-mini {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .user-avatar-mini {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .subscriber-mini {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .subscriber-avatar-mini {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
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
            font-size: 20px;
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
            align-items: center;
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
            font-weight: 700;
            font-size: 16px;
        }
        
        .stat-number.success { color: #06b48a; }
        .stat-number.info { color: #667eea; }
        .stat-number.warning { color: #ffb347; }
        .stat-number.danger { color: #ef476f; }
        
        /* Action Buttons */
        .action-buttons-vertical {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: none;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            width: 100%;
        }
        
        .action-btn.send { background: linear-gradient(135deg, #06b48a, #049a72); color: white; }
        .action-btn.edit { background: #ffd166; color: #2d3748; }
        .action-btn.duplicate { background: #9b59b6; color: white; }
        .action-btn.preview { background: #667eea; color: white; }
        .action-btn.export { background: #f1f5f9; color: #475569; }
        .action-btn.delete { background: #ef476f; color: white; }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .empty-small {
            text-align: center;
            padding: 30px;
        }
        
        .empty-small i {
            font-size: 36px;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .notification-toast.success { background: #06b48a; color: white; }
        .notification-toast.danger { background: #ef476f; color: white; }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
            
            .page-title-modern {
                font-size: 22px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-dashboard {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
@endsection