<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Invoice::with('payments');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                ->orWhere('customer', 'like', "%{$search}%");
            });
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }


    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load('payments');

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $invoice->id,
                'invoice_no'  => $invoice->invoice_no,
                'invoice_date'=> $invoice->invoice_date->format('Y-m-d'),
                'customer'    => $invoice->customer,
                'amount'      => $invoice->amount,
                'tax_amount'  => $invoice->tax_amount,
                'status'      => $invoice->status,
                'total_amount'=> $invoice->total_amount,
                'payments'    => $invoice->payments->map(function ($p) {
                    return [
                        'payment_ref' => $p->payment_ref,
                        'paid_at'     => $p->paid_at->format('Y-m-d'),
                        'amount_paid' => $p->amount_paid,
                        'method'      => $p->method,
                    ];
                })
            ]
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string|max:20|unique:invoices',
            'invoice_date' => 'required|date',
            'customer' => 'required|string|max:120',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'numeric|min:0',
        ]);

        $invoice = Invoice::create($validated);

        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice created successfully',
        ], 201);
    }

    public function update(Request $request, Invoice $invoice): JsonResponse
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string|max:20|unique:invoices,invoice_no,' . $invoice->id,
            'invoice_date' => 'required|date',
            'customer' => 'required|string|max:120',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'numeric|min:0',
        ]);

        $invoice->update($validated);
        $invoice->updateStatus(); 

        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully',
        ]);
    }
}
