<?php

namespace App\Helpers;

class ViewHelper
{
    public static function getAvatarColor($name): string
    {
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', 
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22'
        ];
        
        if (!$name) {
            return $colors[0];
        }
        
        $index = abs(crc32($name)) % count($colors);
        return $colors[$index];
    }

    public static function getInitials($name): string
    {
        if (!$name) {
            return '?';
        }
        
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    public static function getActivityColor($description): string
    {
        $colors = [
            'created' => '#06b48a',
            'updated' => '#45b7d1',
            'deleted' => '#ef476f',
            'status' => '#ffd166'
        ];
        
        if (str_contains($description, 'créé')) return $colors['created'];
        if (str_contains($description, 'supprim')) return $colors['deleted'];
        if (str_contains($description, 'statut')) return $colors['status'];
        
        return $colors['updated'];
    }

    public static function getActivityIcon($description): string
    {
        $icons = [
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'status' => 'fa-exchange-alt'
        ];
        
        if (str_contains($description, 'créé')) return $icons['created'];
        if (str_contains($description, 'supprim')) return $icons['deleted'];
        if (str_contains($description, 'statut')) return $icons['status'];
        
        return $icons['updated'];
    }

    public static function formatCurrency($amount): string
    {
        return number_format($amount ?? 0, 2, ',', ' ') . ' €';
    }

    public static function formatDate($date, $format = 'd/m/Y'): string
    {
        if (!$date) {
            return 'N/A';
        }
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format($format);
    }

    public static function getStatusBadge($status, $formattedStatus = null): string
    {
        $colors = [
            'planning' => 'info',
            'in_progress' => 'primary',
            'on_hold' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        
        $color = $colors[$status] ?? 'secondary';
        $label = $formattedStatus ?? $status;
        
        return "<span class=\"badge bg-{$color}\"><i class=\"fas fa-circle me-1\" style=\"font-size: 0.5rem;\"></i>{$label}</span>";
    }
}