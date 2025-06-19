<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashFlowExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{

    protected $cashflows;
    protected $totalIn;
    protected $totalOut;

    public function __construct($cashflows)
    {
        $this->cashflows = $cashflows;
        $this->totalIn = $cashflows->where('is_cash_in', true)->sum('nominal');
        $this->totalOut = $cashflows->where('is_cash_in', false)->sum('nominal');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->cashflows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Kas Masuk',
            'Kas Keluar',
            'Deskripsi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->transaction_date->format('Y-m-d'),
            ucfirst(str_replace('_', ' ', $row->type)),
            $row->is_cash_in ? number_format($row->amount, 0, ',', '.') : '',
            !$row->is_cash_in ? number_format($row->amount, 0, ',', '.') : '',
            $row->description,
        ];
    }

    public function registerEvents(): array
    {
        $rowCount = count($this->cashflows) + 2;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($rowCount) {
                $sheet = $event->sheet;
                $sheet->setCellValue('A' . $rowCount, 'Total Kas Masuk:');
                $sheet->setCellValue('E' . $rowCount, number_format($this->totalIn, 0, ',', '.'));
                $sheet->setCellValue('A' . ($rowCount + 1), 'Total Kas Keluar:');
                $sheet->setCellValue('E' . ($rowCount + 1), number_format($this->totalOut, 0, ',', '.'));
                $sheet->setCellValue('A' . ($rowCount + 2), 'Saldo Kas:');
                $sheet->setCellValue('E' . ($rowCount + 2), number_format($this->totalIn - $this->totalOut, 0, ',', '.'));
            },
        ];
    }
}
