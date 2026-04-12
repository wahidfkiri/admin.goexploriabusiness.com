<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâche mise à jour</title>
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
        .task-details {
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
        .changes-section {
            background-color: #fff3cd;
            border: 1px solid #ffecb5;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .changes-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
        .change-item {
            margin-bottom: 8px;
            padding-left: 15px;
            border-left: 3px solid #ffc107;
        }
        .old-value {
            text-decoration: line-through;
            color: #dc3545;
        }
        .new-value {
            color: #28a745;
            font-weight: bold;
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
        .status-pending { background-color: #ffc107; color: #856404; }
        .status-in_progress { background-color: #17a2b8; color: white; }
        .status-test { background-color: #6f42c1; color: white; }
        .status-integrated { background-color: #20c997; color: white; }
        .status-delivered { background-color: #28a745; color: white; }
        .status-approved { background-color: #007bff; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h2>✏️ Tâche mise à jour</h2>
    </div>
    
    <div class="content">
        <p>Bonjour,</p>
        
        <p>La tâche <strong>{{ $task->name }}</strong> a été mise à jour par <strong>{{ $updater->name }}</strong>.</p>
        
        @if(!empty($changes))
        <div class="changes-section">
            <div class="changes-title">📝 Modifications effectuées :</div>
            @foreach($changes as $field => $change)
            <div class="change-item">
                <strong>{{ $change['label'] }} :</strong><br>
                <span class="old-value">Ancien: {{ $change['old'] }}</span><br>
                <span class="new-value">Nouveau: {{ $change['new'] }}</span>
            </div>
            @endforeach
        </div>
        @endif
        
        <div class="task-details">
            <h3 style="margin-top: 0; color: #28a745;">{{ $task->name }}</h3>
            
            <div class="detail-row">
                <span class="detail-label">Projet:</span>
                <span>{{ $task->project->name ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Assigné à:</span>
                <span>{{ $task->user->name ?? 'Non assigné' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Statut:</span>
                <span class="status-badge status-{{ $task->status }}">
                    {{ $task->formatted_status }}
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Priorité:</span>
                @php
                    $metadata = json_decode($task->metadata, true);
                    $priority = $metadata['priority'] ?? 'medium';
                @endphp
                <span class="priority-{{ $priority }}">
                    @if($priority == 'low') Basse
                    @elseif($priority == 'medium') Moyenne
                    @elseif($priority == 'high') Haute
                    @elseif($priority == 'urgent') Urgente
                    @endif
                </span>
            </div>
            
            @if($task->due_date)
            <div class="detail-row">
                <span class="detail-label">Date d'échéance:</span>
                <span>{{ $task->due_date->format('d/m/Y H:i') }}</span>
            </div>
            @endif
            
            @if($task->delivery_date)
            <div class="detail-row">
                <span class="detail-label">Date de livraison:</span>
                <span>{{ $task->delivery_date->format('d/m/Y H:i') }}</span>
            </div>
            @endif
            
            @if($task->contract_number)
            <div class="detail-row">
                <span class="detail-label">N° Contrat:</span>
                <span>{{ $task->contract_number }}</span>
            </div>
            @endif
            
            @if($task->contact_name)
            <div class="detail-row">
                <span class="detail-label">Contact:</span>
                <span>{{ $task->contact_name }}</span>
            </div>
            @endif
            
            @if($task->country || $task->location)
            <div class="detail-row">
                <span class="detail-label">Lieu:</span>
                <span>{{ $task->country }} {{ $task->location ? '- ' . $task->location : '' }}</span>
            </div>
            @endif
            
            @if($task->details)
            <div class="detail-row">
                <span class="detail-label">Description:</span>
                <div style="margin-top: 5px;">{!! nl2br(e($task->details)) !!}</div>
            </div>
            @endif
        </div>
        
        @if($task->files_count > 0)
        <p style="margin-top: 15px;">
            <strong>📎 {{ $task->files_count }} fichier(s)</strong> attaché(s) à cette tâche.
        </p>
        @endif
        
        <div style="text-align: center;">
            <a href="{{ $taskUrl }}" class="button">Voir la tâche</a>
        </div>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement par le système de gestion de tâches.</p>
        <p>© {{ date('Y') }} - Tous droits réservés</p>
    </div>
</body>
</html>