<?php

namespace App\Helpers;

class Helper
{
    /**
     * Get color for status
     */
    public static function getStatusColor($status)
    {
        $colors = [
            'gray' => '#6c757d',
            'blue' => '#4a6cf7',
            'yellow' => '#ffb347',
            'purple' => '#9b59b6',
            'indigo' => '#6610f2',
            'green' => '#28a745',
            'red' => '#dc3545',
        ];
        
        return $colors[$status] ?? $colors['gray'];
    }

    /**
     * Get gradient for status
     */
    public static function getStatusGradient($status)
    {
        $gradients = [
            'gray' => '#6c757d, #5a6268',
            'blue' => '#4a6cf7, #3a56e4',
            'yellow' => '#ffb347, #f39c12',
            'purple' => '#9b59b6, #8e44ad',
            'indigo' => '#6610f2, #520dc2',
            'green' => '#28a745, #1e7e34',
            'red' => '#dc3545, #b02a37',
        ];
        
        return $gradients[$status] ?? $gradients['gray'];
    }

    /**
     * Get priority badge HTML
     */
    public static function getPriorityBadge($priority)
    {
        $badges = [
            'low' => '<span class="badge bg-info">Basse</span>',
            'medium' => '<span class="badge bg-warning text-dark">Moyenne</span>',
            'high' => '<span class="badge bg-danger">Haute</span>',
            'urgent' => '<span class="badge bg-danger" style="animation: pulse 2s infinite;">Urgent</span>',
        ];
        
        return $badges[$priority] ?? $badges['medium'];
    }

    /**
     * Get user initials from name
     */
    public static function getInitials($name)
    {
        if (!$name) return '?';
        
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get color for user avatar
     */
    public static function getUserColor($name)
    {
        $colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6', '#3498db', '#1abc9c', '#e74c3c'];
        $index = strlen($name ?? '') % count($colors);
        return $colors[$index];
    }

    /**
     * Get progress color based on percentage
     */
    public static function getProgressColor($progress)
    {
        if ($progress < 30) return '#ef476f';
        if ($progress < 70) return '#ffd166';
        return '#06b48a';
    }

    /**
     * Format date to French format
     */
    public static function formatDate($date, $format = 'd/m/Y H:i')
    {
        if (!$date) return 'N/A';
        return $date instanceof \Carbon\Carbon ? $date->format($format) : \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = '€')
    {
        return number_format($amount ?? 0, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get status badge HTML
     */
    public static function getStatusBadge($status, $color = null)
    {
        $statusColors = [
            'planning' => 'info',
            'in_progress' => 'primary',
            'on_hold' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            'pending' => 'secondary',
            'test' => 'info',
            'integrated' => 'purple',
            'delivered' => 'indigo',
            'approved' => 'success',
        ];
        
        $badgeColor = $color ?? $statusColors[$status] ?? 'secondary';
        
        return '<span class="badge bg-' . $badgeColor . '">' . ucfirst(str_replace('_', ' ', $status)) . '</span>';
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}