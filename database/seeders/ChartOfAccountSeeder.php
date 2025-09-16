<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1101', 'name' => 'Cash', 'normal_balance' => 'DR', 'is_active' => true],
            ['code' => '1201', 'name' => 'Accounts Receivable', 'normal_balance' => 'DR', 'is_active' => true],
            ['code' => '2101', 'name' => 'Accounts Payable', 'normal_balance' => 'CR', 'is_active' => true],
            ['code' => '4101', 'name' => 'Revenue', 'normal_balance' => 'CR', 'is_active' => true],
            ['code' => '5101', 'name' => 'Expense', 'normal_balance' => 'DR', 'is_active' => true],
            ['code' => '6101', 'name' => 'Accrued Expense', 'normal_balance' => 'CR', 'is_active' => true],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
