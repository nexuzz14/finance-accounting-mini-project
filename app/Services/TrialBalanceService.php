<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalLine;
use Illuminate\Support\Facades\DB;

class TrialBalanceService
{
    public function generate(string $startDate, string $endDate): array
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $trialBalance = [];

        foreach ($accounts as $account) {
            $openingBalance = $this->getOpeningBalance($account->id, $startDate);

            $periodMovements = JournalLine::whereHas('journal', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('posting_date', [$startDate, $endDate]);
            })
            ->where('account_id', $account->id)
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

            $debit = $periodMovements->total_debit ?? 0;
            $credit = $periodMovements->total_credit ?? 0;

            if ($account->normal_balance == 'DR') {
                $closingBalance = $openingBalance + $debit - $credit;
            } else {
                $closingBalance = $openingBalance + $credit - $debit;
            }

            $trialBalance[] = [
                'account_code' => $account->code,
                'account_name' => $account->name,
                'normal_balance' => $account->normal_balance,
                'opening_balance' => round($openingBalance, 2),
                'debit' => round($debit, 2),
                'credit' => round($credit, 2),
                'closing_balance' => round($closingBalance, 2)
            ];
        }

        return $trialBalance;
    }

    private function getOpeningBalance(int $accountId, string $startDate): float
    {
        $account = ChartOfAccount::find($accountId);

        $movements = JournalLine::whereHas('journal', function ($query) use ($startDate) {
            $query->where('posting_date', '<', $startDate);
        })
        ->where('account_id', $accountId)
        ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
        ->first();

        $totalDebit = $movements->total_debit ?? 0;
        $totalCredit = $movements->total_credit ?? 0;

        if ($account->normal_balance == 'DR') {
            return $totalDebit - $totalCredit;
        } else {
            return $totalCredit - $totalDebit;
        }
    }
}
