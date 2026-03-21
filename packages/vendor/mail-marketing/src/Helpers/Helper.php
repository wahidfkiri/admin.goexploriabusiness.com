<?php

namespace Vendor\MailMarketing\Helpers;

class Helper
{
    /**
     * Génère une couleur unique basée sur l'email
     */
    public static function getAvatarColor($email)
    {
        if (empty($email)) {
            return '#667eea';
        }
        
        $hash = 0;
        for ($i = 0; $i < strlen($email); $i++) {
            $hash = ord($email[$i]) + (($hash << 5) - $hash);
        }
        
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22',
            '#1abc9c', '#2c3e50', '#e84393', '#f39c12'
        ];
        
        return $colors[abs($hash) % count($colors)];
    }
    
    /**
     * Génère les initiales à partir du prénom et nom
     */
        public static function getInitials($nom = null, $prenom = null)
    {
        // Si un seul argument est passé, on essaie de le traiter
        if ($prenom === null && is_array($nom)) {
            $prenom = $nom['prenom'] ?? null;
            $nom = $nom['nom'] ?? null;
        } elseif ($prenom === null && is_object($nom)) {
            $prenom = $nom->prenom ?? null;
            $nom = $nom->nom ?? null;
        } elseif ($prenom === null && is_string($nom)) {
            // Si un seul string est passé, on le traite comme nom complet
            $parts = explode(' ', trim($nom));
            if (count($parts) >= 2) {
                $prenom = $parts[0];
                $nom = end($parts);
            } else {
                $prenom = '';
                $nom = $parts[0];
            }
        }
        
        $first = $prenom ? mb_substr($prenom, 0, 1) : '';
        $last = $nom ? mb_substr($nom, 0, 1) : '';
        
        $initials = strtoupper($first . $last);
        
        return $initials ?: '?';
    }
    
    /**
     * Génère une couleur basée sur le taux d'engagement
     */
    public static function getEngagementColor($rate)
    {
        if ($rate < 20) {
            return '#ef476f';
        }
        if ($rate < 50) {
            return '#ffd166';
        }
        return '#06b48a';
    }
    
    /**
     * Formate un statut de campagne
     */
    public static function getCampaignStatusBadge($status)
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary"><i class="fas fa-pencil-alt me-1"></i>Brouillon</span>',
            'scheduled' => '<span class="badge bg-info"><i class="fas fa-clock me-1"></i>Planifiée</span>',
            'sending' => '<span class="badge bg-warning"><i class="fas fa-spinner me-1"></i>En cours</span>',
            'sent' => '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Envoyée</span>',
            'cancelled' => '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Annulée</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Génère une couleur pour une campagne basée sur son nom
     */
    public static function getCampaignColor($campaignName)
    {
        if (empty($campaignName)) {
            return '#667eea';
        }
        
        $hash = 0;
        for ($i = 0; $i < strlen($campaignName); $i++) {
            $hash = ord($campaignName[$i]) + (($hash << 5) - $hash);
        }
        
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22',
            '#1abc9c', '#2c3e50', '#e84393', '#f39c12'
        ];
        
        return $colors[abs($hash) % count($colors)];
    }

    
    
    /**
     * Formate un statut de campagne
     */
    public static function getStatusBadge($status, $color = null)
    {
        $statusColors = [
            'Planification' => 'info',
            'En cours' => 'primary',
            'En pause' => 'warning',
            'Terminé' => 'success',
            'Annulé' => 'danger',
            'draft' => 'secondary',
            'scheduled' => 'info',
            'sending' => 'warning',
            'sent' => 'success',
            'cancelled' => 'danger',
        ];
        
        $badgeColor = $color ?? ($statusColors[$status] ?? 'secondary');
        $statusLabels = [
            'draft' => 'Brouillon',
            'scheduled' => 'Planifiée',
            'sending' => 'En cours',
            'sent' => 'Envoyée',
            'cancelled' => 'Annulée',
        ];
        
        $label = $statusLabels[$status] ?? $status;
        
        return '<span class="badge bg-' . $badgeColor . '"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>' . $label . '</span>';
    }
    
    
    /**
     * Formate un statut d'abonné
     */
    public static function getSubscriberStatusBadge($isSubscribed, $unsubscribedAt = null)
    {
        if ($isSubscribed) {
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Abonné</span>';
        }
        
        $date = $unsubscribedAt ? ' (' . date('d/m/Y', strtotime($unsubscribedAt)) . ')' : '';
        return '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Désabonné' . $date . '</span>';
    }

    
    
    /**
     * Génère une couleur pour un utilisateur basée sur son nom
     */
    public static function getUserColor($userName)
    {
        if (empty($userName)) {
            return '#667eea';
        }
        
        $colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6'];
        $index = strlen($userName) % count($colors);
        
        return $colors[$index];
    }
    
    /**
     * Formate une date
     */
    public static function formatDate($date, $format = 'd/m/Y H:i')
    {
        if (!$date) {
            return '—';
        }
        
        return date($format, strtotime($date));
    }
    
    /**
     * Tronque un texte
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }
}