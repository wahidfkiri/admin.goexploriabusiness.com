<?php

namespace Vendor\AdsManager\Helpers;

class Helper
{
    public static function getAvatarColor(string $seed): string
    {
        $colors = [
            '#45b7d1','#96ceb4','#feca57','#ff6b6b',
            '#9b59b6','#3498db','#1abc9c','#e74c3c',
            '#34495e','#f1c40f','#2ecc71','#e67e22',
        ];
        $hash = 0;
        for ($i = 0; $i < strlen($seed); $i++) {
            $hash = ord($seed[$i]) + (($hash << 5) - $hash);
        }
        return $colors[abs($hash) % count($colors)];
    }

    public static function getInitials(string $name): string
    {
        $parts = explode(' ', trim($name));
        $init  = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $init .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $init ?: '?';
    }

    public static function getStatusBadge(string $status): string
    {
        $map = [
            'draft'    => ['bg-secondary', 'fa-pencil-alt',  'Brouillon'],
            'pending'  => ['bg-warning',   'fa-clock',        'En attente'],
            'active'   => ['bg-success',   'fa-check-circle', 'Active'],
            'paused'   => ['bg-info',      'fa-pause-circle', 'Pausée'],
            'expired'  => ['bg-dark',      'fa-calendar-times','Expirée'],
            'rejected' => ['bg-danger',    'fa-times-circle', 'Rejetée'],
        ];
        [$bg, $icon, $label] = $map[$status] ?? ['bg-secondary', 'fa-circle', $status];
        return '<span class="badge ' . $bg . '"><i class="fas ' . $icon . ' me-1"></i>' . $label . '</span>';
    }

    public static function getPricingLabel(string $model): string
    {
        return [
            'cpm'  => 'CPM',
            'cpc'  => 'CPC',
            'cpa'  => 'CPA',
            'flat' => 'Forfait',
        ][$model] ?? $model;
    }

    public static function formatCurrency(float $amount, string $currency = '$'): string
    {
        return number_format($amount, 3, ',', ' ') . ' ' . $currency;
    }

    public static function formatNumber(int $number): string
    {
        if ($number >= 1_000_000) return round($number / 1_000_000, 1) . 'M';
        if ($number >= 1_000)     return round($number / 1_000, 1)     . 'k';
        return (string)$number;
    }

    public static function getCtrColor(float $ctr): string
    {
        if ($ctr < 1)  return '#ef476f';
        if ($ctr < 3)  return '#ffd166';
        return '#06b48a';
    }

    public static function getFormatDimensions(string $format): array
    {
        return config('ads-manager.ad_formats.' . $format, ['width' => 300, 'height' => 250]);
    }

    public static function truncate(string $text, int $length = 80, string $suffix = '…'): string
    {
        return mb_strlen($text) <= $length ? $text : mb_substr($text, 0, $length) . $suffix;
    }
}