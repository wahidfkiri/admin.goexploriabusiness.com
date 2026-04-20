<?php

namespace Vendor\Administration\Exports;

use App\Models\Etablissement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EtablissementsSubscriptionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Etablissement::with(['currentAbonnement.plan']);
        
        if (!empty($this->filters['subscription_status'])) {
            if ($this->filters['subscription_status'] === 'paid') {
                $query->whereNotNull('current_abonnement_id')
                      ->where('subscription_expires_at', '>=', now());
            } elseif ($this->filters['subscription_status'] === 'unpaid') {
                $query->where(function($q) {
                    $q->whereNull('current_abonnement_id')
                      ->orWhere('subscription_expires_at', '<', now());
                });
            }
        }
        
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('email_contact', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'ÉTABLISSEMENT',
            'VILLE',
            'CONTACT',
            'EMAIL',
            'STATUT ABONNEMENT',
            'PLAN ACTUEL',
            'DATE EXPIRATION',
            'JOURS RESTANTS',
            'MONTANT PAYÉ',
            'DERNIER PAIEMENT'
        ];
    }

    public function map($etablissement): array
    {
        $currentSub = $etablissement->currentAbonnement;
        $daysRemaining = '';
        $amountPaid = 0;
        $lastPayment = '';
        
        if ($currentSub && $currentSub->end_date >= now()) {
            $daysRemaining = $currentSub->daysRemaining() . ' jours';
            $amountPaid = $currentSub->amount_paid;
            
            $lastPaymentObj = $currentSub->paiements()->latest()->first();
            if ($lastPaymentObj) {
                $lastPayment = $lastPaymentObj->created_at->format('d/m/Y');
            }
        }
        
        return [
            $etablissement->name,
            $etablissement->ville ?? 'N/A',
            $etablissement->phone ?? 'N/A',
            $etablissement->email_contact ?? 'N/A',
            $etablissement->subscription_status_label,
            $currentSub ? ($currentSub->plan->name ?? 'N/A') : 'Aucun',
            $currentSub ? $currentSub->end_date->format('d/m/Y') : 'N/A',
            $daysRemaining ?: 'N/A',
            $amountPaid ? number_format($amountPaid, 0, ',', ' ') . ' FCFA' : '0 FCFA',
            $lastPayment ?: 'Aucun'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        $lastRow = $sheet->getHighestRow();
        
        // Borders
        $sheet->getStyle('A1:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);
        
        // Color status column
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('E' . $i)->getValue();
            
            if ($status === 'Actif') {
                $sheet->getStyle('E' . $i)->getFont()->getColor()->setRGB('059669');
                $sheet->getStyle('E' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D1FAE5');
            } elseif ($status === 'Expiré' || $status === 'Aucun') {
                $sheet->getStyle('E' . $i)->getFont()->getColor()->setRGB('DC2626');
                $sheet->getStyle('E' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEE2E2');
            }
        }
        
        // Freeze header
        $sheet->freezePane('A2');
        
        return [];
    }

    public function title(): string
    {
        return 'Établissements - Statut abonnement';
    }
}