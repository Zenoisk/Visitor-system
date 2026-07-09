<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitorsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate, $endDate, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function query()
    {
        $query = Visitor::query()
            ->whereBetween('visit_date', [$this->startDate, $this->endDate])
            ->latest('visit_date');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Visitor No',
            'Full Name',
            'IC / Passport',
            'Phone',
            'Email',
            'Company',
            'Purpose of Visit',
            'Person to Meet',
            'Department',
            'Vehicle Plate',
            'Visit Date',
            'Status',
            'Checked In',
            'Checked Out',
        ];
    }

    public function map($visitor): array
    {
        return [
            $visitor->visitor_no,
            $visitor->full_name,
            $visitor->ic_passport_no,
            $visitor->phone,
            $visitor->email ?? '-',
            $visitor->company_name,
            $visitor->purpose_of_visit,
            $visitor->person_to_meet,
            $visitor->department,
            $visitor->vehicle_plate_no ?? '-',
            $visitor->visit_date?->format('d/m/Y'),
            ucfirst(str_replace('_', ' ', $visitor->status)),
            $visitor->checked_in_at?->format('d/m/Y H:i'),
            $visitor->checked_out_at?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF003F87'],
                ],
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            ],
        ];
    }
}
