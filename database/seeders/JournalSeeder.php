<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\ChartOfAccount;

class JournalSeeder extends Seeder
{
     public function run(): void
    {
        // Get account IDs for reference
        $cash = ChartOfAccount::where('code', '1101')->first();
        $revenue = ChartOfAccount::where('code', '4101')->first();
        $expense = ChartOfAccount::where('code', '5101')->first();
        $accruedExpense = ChartOfAccount::where('code', '6101')->first();

        // Journal 1: Opening accrual
        $journal1 = Journal::create([
            'ref_no' => 'JV-2025-0001',
            'posting_date' => '2025-07-01',
            'memo' => 'Opening accrual',
            'status' => 'posted',
        ]);

        JournalLine::create([
            'journal_id' => $journal1->id,
            'account_id' => $accruedExpense->id,
            'debit' => 100000.00,
            'credit' => 0,
        ]);

        JournalLine::create([
            'journal_id' => $journal1->id,
            'account_id' => $cash->id,
            'debit' => 0,
            'credit' => 100000.00,
        ]);

        // Journal 2: Sales cash
        $journal2 = Journal::create([
            'ref_no' => 'JV-2025-0002',
            'posting_date' => '2025-07-15',
            'memo' => 'Sales cash',
            'status' => 'posted',
        ]);

        JournalLine::create([
            'journal_id' => $journal2->id,
            'account_id' => $cash->id,
            'debit' => 2800000.00,
            'credit' => 0,
        ]);

        JournalLine::create([
            'journal_id' => $journal2->id,
            'account_id' => $revenue->id,
            'debit' => 0,
            'credit' => 2800000.00,
        ]);

        // Journal 3: Utilities expense
        $journal3 = Journal::create([
            'ref_no' => 'JV-2025-0003',
            'posting_date' => '2025-07-20',
            'memo' => 'Utilities expense',
            'status' => 'posted',
        ]);

        JournalLine::create([
            'journal_id' => $journal3->id,
            'account_id' => $expense->id,
            'debit' => 1200000.00,
            'credit' => 0,
        ]);

        JournalLine::create([
            'journal_id' => $journal3->id,
            'account_id' => $cash->id,
            'debit' => 0,
            'credit' => 1200000.00,
        ]);
    }
}
