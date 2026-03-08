<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau projet créé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .project-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .detail-row {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #28a745;
            width: 150px;
            display: inline-block;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        .priority-high {
            color: #dc3545;
            font-weight: bold;
        }
        .priority-medium {
            color: #ffc107;
            font-weight: bold;
        }
        .priority-low {
            color: #28a745;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-planning { background-color: #6c757d; color: white; }
        .status-in_progress { background-color: #007bff; color: white; }
        .status-on_hold { background-color: #ffc107; color: black; }
        .status-completed { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📊 Nouveau projet créé</h2>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $project->responsible->name ?? 'Responsable' }}</strong>,</p>
        
        <p>Un nouveau projet a été créé par <strong>{{ $creator->name }}</strong> et vous avez été désigné comme responsable.</p>
        
        <div class="project-details">
            <h3 style="margin-top: 0; color: #28a745;">{{ $project->name }}</h3>
            
            <div class="detail-row">
                <span class="detail-label">Statut:</span>
                @php
                    $statusClass = 'status-' . str_replace('_', '-', $project->status);
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    @switch($project->status)
                        @case('planning') Planification @break
                        @case('in_progress') En cours @break
                        @case('on_hold') En pause @break
                        @case('completed') Terminé @break
                        @case('cancelled') Annulé @break
                        @default {{ $project->status }}
                    @endswitch
                </span>
            </div>
            
            @php
                $metadata = json_decode($project->metadata, true);
                $priority = $metadata['priority'] ?? 'medium';
            @endphp
            <div class="detail-row">
                <span class="detail-label">Priorité:</span>
                <span class="priority-{{ $priority }}">
                    @if($priority == 'low') Basse
                    @elseif($priority == 'medium') Moyenne
                    @elseif($priority == 'high') Haute
                    @elseif($priority == 'urgent') Urgente
                    @endif
                </span>
            </div>
            
            @if($project->contract_number)
            <div class="detail-row">
                <span class="detail-label">N° Contrat:</span>
                <span>{{ $project->contract_number }}</span>
            </div>
            @endif
            
            @if($project->client)
            <div class="detail-row">
                <span class="detail-label">Client:</span>
                <span>{{ $project->client->name ?? 'N/A' }}</span>
            </div>
            @endif
            
            @if($project->start_date)
            <div class="detail-row">
                <span class="detail-label">Date de début:</span>
                <span>{{ $project->start_date->format('d/m/Y') }}</span>
            </div>
            @endif
            
            @if($project->end_date)
            <div class="detail-row">
                <span class="detail-label">Date de fin:</span>
                <span>{{ $project->end_date->format('d/m/Y') }}</span>
            </div>
            @endif
            
            @if($project->estimated_hours)
            <div class="detail-row">
                <span class="detail-label">Heures estimées:</span>
                <span>{{ $project->estimated_hours }} h</span>
            </div>
            @endif
            
            @if($project->estimated_budget)
            <div class="detail-row">
                <span class="detail-label">Budget estimé:</span>
                <span>{{ number_format($project->estimated_budget, 2, ',', ' ') }} €</span>
            </div>
            @endif
            
            @if($project->contact_name || $project->contact_email || $project->contact_phone)
            <div class="detail-row">
                <span class="detail-label">Contact:</span>
                <div>
                    @if($project->contact_name) {{ $project->contact_name }}<br> @endif
                    @if($project->contact_email) <a href="mailto:{{ $project->contact_email }}">{{ $project->contact_email }}</a><br> @endif
                    @if($project->contact_phone) {{ $project->contact_phone }} @endif
                </div>
            </div>
            @endif
            
            @if($project->description)
            <div class="detail-row">
                <span class="detail-label">Description:</span>
                <div style="margin-top: 5px;">{!! nl2br(e($project->description)) !!}</div>
            </div>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $projectUrl }}" class="button">Voir le projet</a>
        </div>
        
        <p style="margin-top: 20px; font-style: italic; color: #666;">
            En tant que responsable, vous pouvez maintenant commencer à planifier les tâches de ce projet.
        </p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement par le système de gestion de projets.</p>
        <p>© {{ date('Y') }} - Tous droits réservés</p>
    </div>
</body>
</html>