<?php

namespace Vendor\Project\Helpers;

use Illuminate\Support\Carbon;

class Helper
{
    /**
     * Obtenir la couleur en fonction du pourcentage d'avancement
     *
     * @param int $progress
     * @return string
     */
    public static function getProgressColor($progress)
    {
        if ($progress < 30) return '#ef476f'; // Rouge pour faible progression
        if ($progress < 70) return '#ffd166'; // Jaune pour progression moyenne
        return '#06b48a'; // Vert pour bonne progression
    }

    /**
     * Obtenir la couleur en fonction du statut de la tâche
     *
     * @param string $status
     * @return string
     */
    public static function getTaskColor($status)
    {
        $colors = [
            'pending' => '#f39c12',      // Orange
            'in_progress' => '#3498db',   // Bleu
            'test' => '#9b59b6',          // Violet
            'integrated' => '#1abc9c',    // Turquoise
            'delivered' => '#2ecc71',      // Vert clair
            'approved' => '#27ae60',       // Vert foncé
            'cancelled' => '#e74c3c',      // Rouge
            'on_hold' => '#95a5a6',        // Gris
        ];
        
        return $colors[$status] ?? '#95a5a6';
    }

    /**
     * Générer une couleur unique basée sur le nom du projet
     *
     * @param string $projectName
     * @return string
     */
    public static function getProjectColor($projectName)
    {
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22',
            '#e84342', '#6c5ce7', '#00b894', '#0984e3',
            '#d63031', '#fdcb6e', '#e17055', '#636e72'
        ];
        
        $hash = 0;
        for ($i = 0; $i < strlen($projectName); $i++) {
            $hash = ord($projectName[$i]) + (($hash << 5) - $hash);
        }
        
        return $colors[abs($hash) % count($colors)];
    }

    /**
     * Obtenir une couleur pour un utilisateur
     *
     * @param string $userName
     * @return string
     */
    public static function getUserColor($userName)
    {
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', 
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22'
        ];
        
        $index = (strlen($userName ?? '') % count($colors));
        return $colors[$index];
    }

    /**
     * Obtenir les initiales d'un nom
     *
     * @param string|null $name
     * @return string
     */
    public static function getInitials($name)
    {
        if (!$name) return '?';
        
        $names = explode(' ', $name);
        $initials = '';
        
        foreach ($names as $n) {
            if (!empty(trim($n))) {
                $initials .= strtoupper(substr(trim($n), 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Générer un badge de statut HTML
     *
     * @param string $status
     * @param string|null $color
     * @return string
     */
    public static function getStatusBadge($status, $color = null)
    {
        $statusColors = [
            'En attente' => 'warning',
            'pending' => 'warning',
            'En cours' => 'primary',
            'in_progress' => 'primary',
            'En test' => 'info',
            'test' => 'info',
            'Intégré' => 'purple',
            'integrated' => 'purple',
            'Livré' => 'success',
            'delivered' => 'success',
            'Approuvé' => 'success',
            'approved' => 'success',
            'Annulé' => 'danger',
            'cancelled' => 'danger',
            'Planification' => 'info',
            'planning' => 'info',
            'En pause' => 'warning',
            'on_hold' => 'warning',
            'Terminé' => 'success',
            'completed' => 'success'
        ];
        
        $badgeColor = $statusColors[$status] ?? $statusColors[$color] ?? 'secondary';
        
        // Si la couleur est 'purple', on utilise une classe personnalisée
        if ($badgeColor === 'purple') {
            return '<span class="badge" style="background: #9b59b6; color: white;"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>' . $status . '</span>';
        }
        
        return '<span class="badge bg-' . $badgeColor . '"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>' . $status . '</span>';
    }

    /**
     * Générer un badge de priorité HTML
     *
     * @param string $priority
     * @return string
     */
    public static function getPriorityBadge($priority)
    {
        $badges = [
            'haute' => '<span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i>Haute</span>',
            'high' => '<span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i>Haute</span>',
            'moyenne' => '<span class="badge bg-warning"><i class="fas fa-minus me-1"></i>Moyenne</span>',
            'medium' => '<span class="badge bg-warning"><i class="fas fa-minus me-1"></i>Moyenne</span>',
            'basse' => '<span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i>Basse</span>',
            'low' => '<span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i>Basse</span>'
        ];
        
        return $badges[$priority] ?? $badges['moyenne'];
    }

    /**
     * Formater un montant en euros
     *
     * @param float|null $amount
     * @return string
     */
    public static function formatCurrency($amount)
    {
        if ($amount === null || $amount === '') return '0 €';
        return number_format($amount, 2, ',', ' ') . ' €';
    }

    /**
     * Formater une date en français
     *
     * @param string|Carbon|null $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        if (!$date) return 'Non définie';
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format($format);
    }

    /**
     * Obtenir le temps restant avant une échéance
     *
     * @param string|Carbon|null $dueDate
     * @param string|null $status
     * @return array
     */
    public static function getTimeRemaining($dueDate, $status = null)
    {
        if (!$dueDate) return ['text' => 'Non définie', 'class' => ''];
        
        if (is_string($dueDate)) {
            $dueDate = Carbon::parse($dueDate);
        }
        
        // Si la tâche est terminée
        if (in_array($status, ['approved', 'delivered', 'completed', 'terminé'])) {
            return ['text' => 'Terminée', 'class' => 'text-success'];
        }
        
        $now = Carbon::now();
        
        if ($dueDate->isPast()) {
            $days = $dueDate->diffInDays($now);
            return [
                'text' => 'En retard (' . $days . 'j)',
                'class' => 'text-danger fw-bold'
            ];
        }
        
        $days = $now->diffInDays($dueDate);
        
        if ($days == 0) {
            $hours = $now->diffInHours($dueDate);
            return [
                'text' => 'Aujourd\'hui (' . $hours . 'h)',
                'class' => 'text-warning fw-bold'
            ];
        } elseif ($days <= 7) {
            return [
                'text' => 'J-' . $days,
                'class' => 'text-warning'
            ];
        } else {
            return [
                'text' => 'J-' . $days,
                'class' => 'text-muted'
            ];
        }
    }

    /**
     * Obtenir l'icône pour un statut
     *
     * @param string $status
     * @return string
     */
    public static function getStatusIcon($status)
    {
        $icons = [
            'pending' => 'fas fa-clock',
            'in_progress' => 'fas fa-spinner fa-pulse',
            'test' => 'fas fa-vial',
            'integrated' => 'fas fa-code-branch',
            'delivered' => 'fas fa-truck',
            'approved' => 'fas fa-check-circle',
            'cancelled' => 'fas fa-times-circle',
            'planning' => 'fas fa-calendar-alt',
            'on_hold' => 'fas fa-pause-circle',
            'completed' => 'fas fa-check-double'
        ];
        
        return $icons[$status] ?? 'fas fa-tasks';
    }

    /**
     * Traduire un statut en français
     *
     * @param string $status
     * @return string
     */
    public static function getStatusTranslation($status)
    {
        $translations = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'test' => 'En test',
            'integrated' => 'Intégré',
            'delivered' => 'Livré',
            'approved' => 'Approuvé',
            'cancelled' => 'Annulé',
            'planning' => 'Planification',
            'on_hold' => 'En pause',
            'completed' => 'Terminé'
        ];
        
        return $translations[$status] ?? $status;
    }

    /**
     * Calculer la progression d'une tâche basée sur son statut
     *
     * @param string $status
     * @return int
     */
    public static function calculateTaskProgress($status)
    {
        $progressMap = [
            'pending' => 0,
            'in_progress' => 25,
            'test' => 50,
            'integrated' => 75,
            'delivered' => 90,
            'approved' => 100,
            'cancelled' => 0,
            'planning' => 10,
            'on_hold' => 25,
            'completed' => 100
        ];
        
        return $progressMap[$status] ?? 0;
    }

    /**
     * Calculer la progression d'un projet basée sur ses tâches
     *
     * @param \Illuminate\Support\Collection $tasks
     * @return array
     */
    public static function calculateProjectProgress($tasks)
    {
        $total = $tasks->count();
        
        if ($total === 0) {
            return [
                'percentage' => 0,
                'completed' => 0,
                'in_progress' => 0,
                'pending' => 0,
                'overdue' => 0
            ];
        }
        
        $completed = $tasks->filter(function($task) {
            return in_array($task->status, ['approved', 'delivered', 'completed']);
        })->count();
        
        $inProgress = $tasks->filter(function($task) {
            return in_array($task->status, ['in_progress', 'test', 'integrated']);
        })->count();
        
        $pending = $tasks->filter(function($task) {
            return in_array($task->status, ['pending', 'planning']);
        })->count();
        
        $overdue = $tasks->filter(function($task) {
            return $task->due_date && Carbon::parse($task->due_date)->isPast() && 
                   !in_array($task->status, ['approved', 'delivered', 'completed', 'cancelled']);
        })->count();
        
        $percentage = round(($completed / $total) * 100);
        
        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'overdue' => $overdue,
            'total' => $total
        ];
    }

    /**
     * Tronquer un texte à une longueur donnée
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    public static function truncateText($text, $length = 50, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Obtenir l'icône pour un type de fichier
     *
     * @param string $extension
     * @return string
     */
    public static function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'xls' => 'fas fa-file-excel text-success',
            'xlsx' => 'fas fa-file-excel text-success',
            'ppt' => 'fas fa-file-powerpoint text-warning',
            'pptx' => 'fas fa-file-powerpoint text-warning',
            'jpg' => 'fas fa-file-image text-info',
            'jpeg' => 'fas fa-file-image text-info',
            'png' => 'fas fa-file-image text-info',
            'gif' => 'fas fa-file-image text-info',
            'zip' => 'fas fa-file-archive text-secondary',
            'rar' => 'fas fa-file-archive text-secondary',
            'txt' => 'fas fa-file-alt text-secondary',
            'mp4' => 'fas fa-file-video text-info',
            'mp3' => 'fas fa-file-audio text-info'
        ];
        
        return $icons[strtolower($extension)] ?? 'fas fa-file text-secondary';
    }

    /**
     * Obtenir le temps écoulé depuis une date
     *
     * @param string|Carbon $date
     * @return string
     */
    public static function getTimeAgo($date)
    {
        if (!$date) return '';
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->diffForHumans();
    }
}