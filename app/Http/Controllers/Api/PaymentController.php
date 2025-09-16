<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Payment::with('invoice:id,invoice_no');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('payment_ref', 'like', "%{$search}%")
                ->orWhereHas('invoice', function($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                        ->orWhere('customer', 'like', "%{$search}%");
                });
        }

        $payments = $query->orderBy('paid_at', 'desc')->get();

        $payments = $payments->map(function($payment) {
            return [
                'id'          => $payment->id,
                'payment_ref' => $payment->payment_ref,
                'paid_at'     => $payment->paid_at->format('Y-m-d'),
                'amount_paid' => $payment->amount_paid,
                'method'      => $payment->method,
                'invoice_no'  => $payment->invoice->invoice_no ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }


    public function show($id): JsonResponse
    {
        $payment = Payment::with('invoice:id,invoice_no,amount,tax_amount,status')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $payment->id,
                'payment_ref' => $payment->payment_ref,
                'paid_at'     => $payment->paid_at->format('Y-m-d H:i:s'),
                'amount_paid' => $payment->amount_paid,
                'method'      => $payment->method,
                'invoice_no'  => $payment->invoice->invoice_no,
                'invoice_total' => $payment->invoice->total_amount,
                'invoice_status' => $payment->invoice->status,
            ]
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_ref' => 'nullable|string|max:30|unique:payments',
            'paid_at' => 'required|date',
            'amount_paid' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:50',
        ]);

        $payment = Payment::create($validated);
        $payment->load('invoice');

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment created successfully',
        ], 201);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully',
        ]);
    }
}
