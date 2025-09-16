<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $invoice1 = Invoice::where('invoice_no', 'INV-2025-0005')->first();
        $invoice3 = Invoice::where('invoice_no', 'INV-2025-0007')->first();

        $payments = [
            [
                'invoice_id' => $invoice1->id,
                'payment_ref' => 'PAY-2025-001',
                'paid_at' => '2025-07-12',
                'amount_paid' => 1000000.00,
                'method' => 'Bank Transfer',
            ],
            [
                'invoice_id' => $invoice1->id,
                'payment_ref' => 'PAY-2025-002',
                'paid_at' => '2025-07-22',
                'amount_paid' => 800000.00,
                'method' => 'Cash',
            ],
            [
                'invoice_id' => $invoice3->id,
                'payment_ref' => 'PAY-2025-003',
                'paid_at' => '2025-07-28',
                'amount_paid' => 555000.00,
                'method' => 'Bank Transfer',
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
