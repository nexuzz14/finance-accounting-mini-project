<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected array $data;
    protected string $startDate;
    protected string $endDate;

    public function __construct(array $data, string $startDate, string $endDate)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $exportData = [];

        $exportData[] = ['TRIAL BALANCE REPORT'];
        $exportData[] = ['Period: ' . $this->startDate . ' to ' . $this->endDate];
        $exportData[] = [];

        $totalOpeningBalance = 0;
        $totalDebit = 0;
        $totalCredit = 0;
        $totalClosingBalance = 0;

        foreach ($this->data as $row) {
            $exportData[] = [
                $row['account_code'],
                $row['account_name'],
                number_format($row['opening_balance'], 2),
                number_format($row['debit'], 2),
                number_format($row['credit'], 2),
                number_format($row['closing_balance'], 2)
            ];

            $totalOpeningBalance += $row['opening_balance'];
            $totalDebit += $row['debit'];
            $totalCredit += $row['credit'];
            $totalClosingBalance += $row['closing_balance'];
        }

        $exportData[] = [];
        $exportData[] = [
            'TOTAL',
            '',
            number_format($totalOpeningBalance, 2),
            number_format($totalDebit, 2),
            number_format($totalCredit, 2),
            number_format($totalClosingBalance, 2)
        ];

        return $exportData;
    }

    public function headings(): array
    {
        return [
            ['Account Code', 'Account Name', 'Opening Balance', 'Debit', 'Credit', 'Closing Balance']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Trial Balance';
    }
}
