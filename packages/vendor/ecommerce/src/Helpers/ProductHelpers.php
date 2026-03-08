<?php

namespace Vendor\Ecommerce\Helpers;

class ProductHelpers
{
    public static function getTypeBadge($type)
    {
        $badges = [
            'produit_physique' => '<span class="badge bg-info"><i class="fas fa-cube me-1"></i>Produit physique</span>',
            'produit_numerique' => '<span class="badge bg-primary"><i class="fas fa-file-download me-1"></i>Produit numérique</span>',
            'service' => '<span class="badge bg-success"><i class="fas fa-cogs me-1"></i>Service</span>',
            'prestation' => '<span class="badge bg-warning"><i class="fas fa-briefcase me-1"></i>Prestation</span>',
            'forfait' => '<span class="badge bg-danger"><i class="fas fa-gift me-1"></i>Forfait</span>',
            'abonnement' => '<span class="badge bg-secondary"><i class="fas fa-sync-alt me-1"></i>Abonnement</span>',
            'licence' => '<span class="badge bg-dark"><i class="fas fa-certificate me-1"></i>Licence</span>',
            'hebergement' => '<span class="badge bg-purple"><i class="fas fa-server me-1"></i>Hébergement</span>',
            'maintenance' => '<span class="badge bg-orange"><i class="fas fa-wrench me-1"></i>Maintenance</span>',
            'formation' => '<span class="badge bg-teal"><i class="fas fa-chalkboard-teacher me-1"></i>Formation</span>'
        ];
        return $badges[$type] ?? '<span class="badge bg-secondary">Autre</span>';
    }

    public static function getBillingUnitText($unit)
    {
        $units = [
            'unite' => 'À l\'unité',
            'heure' => 'À l\'heure',
            'jour' => 'Par jour',
            'mois' => 'Par mois',
            'an' => 'Par an',
            'forfait' => 'Forfait',
            'projet' => 'Par projet'
        ];
        return $units[$unit] ?? $unit;
    }

    public static function getBillingPeriodText($period)
    {
        $periods = [
            'mensuel' => 'Mensuel',
            'trimestriel' => 'Trimestriel',
            'semestriel' => 'Semestriel',
            'annuel' => 'Annuel'
        ];
        return $periods[$period] ?? $period;
    }

    public static function getStockStatus($product)
    {
        if (!in_array($product->main_type, ['produit_physique', 'produit_numerique'])) {
            return ['badge' => '<span class="badge bg-secondary">N/A</span>', 'status' => 'non_applicable'];
        }
        
        if ($product->stock_management == 'non') {
            return ['badge' => '<span class="badge bg-info">Stock non géré</span>', 'status' => 'non_geré'];
        }
        
        if ($product->stock_management == 'sur_commande') {
            return ['badge' => '<span class="badge bg-warning">Sur commande</span>', 'status' => 'sur_commande'];
        }
        
        $stock = $product->current_stock ?? 0;
        $minStock = $product->minimum_stock ?? 0;
        
        if ($stock <= 0) {
            return ['badge' => '<span class="badge bg-danger">Rupture de stock</span>', 'status' => 'rupture'];
        }
        
        if ($stock <= $minStock) {
            return ['badge' => '<span class="badge bg-warning">Stock faible</span>', 'status' => 'faible'];
        }
        
        return ['badge' => '<span class="badge bg-success">En stock</span>', 'status' => 'disponible'];
    }

    public static function getTypeLabel($type)
    {
        $labels = [
            'produit_physique' => 'Produit physique',
            'produit_numerique' => 'Produit numérique',
            'service' => 'Service',
            'prestation' => 'Prestation',
            'forfait' => 'Forfait',
            'abonnement' => 'Abonnement',
            'licence' => 'Licence',
            'hebergement' => 'Hébergement',
            'maintenance' => 'Maintenance',
            'formation' => 'Formation'
        ];
        return $labels[$type] ?? $type;
    }

    public static function getServiceInfo($product)
    {
        if (!in_array($product->main_type, ['service', 'prestation'])) {
            return null;
        }

        return [
            'duration_minutes' => $product->estimated_duration_minutes,
            'duration_hours' => $product->estimated_duration_minutes ? 
                round($product->estimated_duration_minutes / 60, 1) : null,
            'requires_appointment' => (bool) $product->requires_appointment
        ];
    }

    public static function getSubscriptionInfo($product)
    {
        if ($product->main_type != 'abonnement') {
            return null;
        }

        return [
            'billing_period' => $product->billing_period,
            'billing_period_label' => self::getBillingPeriodText($product->billing_period),
            'has_commitment' => (bool) $product->has_commitment,
            'commitment_months' => $product->commitment_months,
            'commitment_text' => $product->has_commitment ? 
                $product->commitment_months . ' mois' : 'Sans engagement'
        ];
    }
}