<?php

namespace Vendor\Administration\Exports;

use App\Models\Abonnement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbonnementsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    protected $filters;
    protected $totalAmount = 0;
    protected $rowCount = 0;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Abonnement::with(['etablissement', 'plan']);
        
        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        
        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }
        
        if (!empty($this->filters['plan_id'])) {
            $query->where('plan_id', $this->filters['plan_id']);
        }
        
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('start_date', '>=', $this->filters['date_from']);
        }
        
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('end_date', '<=', $this->filters['date_to']);
        }
        
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('etablissement', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%");
            })->orWhere('reference', 'like', "%{$search}%");
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'RÉFÉRENCE',
            'ÉTABLISSEMENT',
            'VILLE',
            'CONTACT',
            'PLAN',
            'MONTANT (FCFA)',
            'DATE DE DÉBUT',
            'DATE DE FIN',
            'JOURS RESTANTS',
            'STATUT',
            'STATUT PAIEMENT',
            'DATE DE CRÉATION',
            'RENOUVELLEMENT AUTO'
        ];
    }

    public function map($abonnement): array
    {
        $this->rowCount++;
        $this->totalAmount += $abonnement->amount_paid;
        
        $daysRemaining = '';
        if ($abonnement->status === 'active' && $abonnement->end_date >= now()) {
            $daysRemaining = $abonnement->daysRemaining() . ' jours';
        } elseif ($abonnement->end_date < now()) {
            $daysRemaining = 'Expiré';
        } else {
            $daysRemaining = 'Terminé';
        }
        
        return [
            $abonnement->reference,
            $abonnement->etablissement->name ?? 'N/A',
            $abonnement->etablissement->ville ?? 'N/A',
            $abonnement->etablissement->phone ?? 'N/A',
            $abonnement->plan->name ?? 'N/A',
            (float) $abonnement->amount_paid,
            $abonnement->start_date->format('d/m/Y'),
            $abonnement->end_date->format('d/m/Y'),
            $daysRemaining,
            $this->getStatusLabel($abonnement->status),
            $this->getPaymentStatusLabel($abonnement->payment_status),
            $abonnement->created_at->format('d/m/Y H:i'),
            $abonnement->auto_renew ? 'Oui' : 'Non'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366F1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Style for all cells
        $sheet->getStyle('A1:M' . ($this->rowCount + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);
        
        // Style for status column
        $lastRow = $this->rowCount + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('J' . $i)->getValue();
            $paymentStatus = $sheet->getCell('K' . $i)->getValue();
            
            // Color status cells
            if ($status === 'Actif') {
                $sheet->getStyle('J' . $i)->getFont()->getColor()->setRGB('059669');
                $sheet->getStyle('J' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D1FAE5');
            } elseif ($status === 'Expiré') {
                $sheet->getStyle('J' . $i)->getFont()->getColor()->setRGB('DC2626');
                $sheet->getStyle('J' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEE2E2');
            } elseif ($status === 'Annulé') {
                $sheet->getStyle('J' . $i)->getFont()->getColor()->setRGB('D97706');
                $sheet->getStyle('J' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEF3C7');
            }
            
            // Color payment status cells
            if ($paymentStatus === 'Payé') {
                $sheet->getStyle('K' . $i)->getFont()->getColor()->setRGB('059669');
                $sheet->getStyle('K' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D1FAE5');
            } elseif ($paymentStatus === 'Impayé') {
                $sheet->getStyle('K' . $i)->getFont()->getColor()->setRGB('DC2626');
                $sheet->getStyle('K' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEE2E2');
            } elseif ($paymentStatus === 'Partiel') {
                $sheet->getStyle('K' . $i)->getFont()->getColor()->setRGB('D97706');
                $sheet->getStyle('K' . $i)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEF3C7');
            }
        }
        
        // Add summary row
        $summaryRow = $this->rowCount + 3;
        $sheet->setCellValue('A' . $summaryRow, 'TOTAL GÉNÉRAL');
        $sheet->setCellValue('F' . $summaryRow, $this->totalAmount);
        $sheet->setCellValue('I' . $summaryRow, $this->rowCount . ' abonnements');
        
        $sheet->getStyle('A' . $summaryRow . ':I' . $summaryRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F5F9'],
            ],
        ]);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(18);
        $sheet->getColumnDimension('L')->setWidth(18);
        $sheet->getColumnDimension('M')->setWidth(18);
        
        // Freeze header row
        $sheet->freezePane('A2');
        
        return [];
    }

    public function title(): string
    {
        return 'Liste des abonnements';
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'active' => 'Actif',
            'expired' => 'Expiré',
            'cancelled' => 'Annulé',
            'pending' => 'En attente'
        ];
        return $labels[$status] ?? $status;
    }

    private function getPaymentStatusLabel($status)
    {
        $labels = [
            'paid' => 'Payé',
            'unpaid' => 'Impayé',
            'partial' => 'Partiel',
            'refunded' => 'Remboursé'
        ];
        return $labels[$status] ?? $status;
    }
}