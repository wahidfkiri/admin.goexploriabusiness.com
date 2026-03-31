<?php

namespace App\Helpers;

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
    public static function getInitials($nom, $prenom)
    {
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
     * Génère une couleur de progression
     */
    public static function getProgressColor($progress)
    {
        if ($progress < 30) {
            return '#ef476f';
        }
        if ($progress < 70) {
            return '#ffd166';
        }
        return '#06b48a';
    }
    
    /**
     * Vérifie si une date d'échéance est proche
     */
    public static function isDeadlineNear($dateStr)
    {
        if (!$dateStr) {
            return false;
        }
        
        $deadlineDate = \Carbon\Carbon::parse($dateStr);
        $today = now();
        $diffDays = $deadlineDate->diffInDays($today);
        
        return $diffDays >= 0 && $diffDays <= 7;
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