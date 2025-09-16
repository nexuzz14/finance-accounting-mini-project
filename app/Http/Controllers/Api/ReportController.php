<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TrialBalanceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrialBalanceExport;

class ReportController extends Controller
{
    protected TrialBalanceService $trialBalanceService;

    public function __construct(TrialBalanceService $trialBalanceService)
    {
        $this->trialBalanceService = $trialBalanceService;
    }

    public function trialBalance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start'
        ]);

        $trialBalance = $this->trialBalanceService->generate(
            $validated['start'],
            $validated['end']
        );

        return response()->json([
            'success' => true,
            'data' => $trialBalance,
            'period' => [
                'start' => $validated['start'],
                'end' => $validated['end']
            ]
        ]);
    }

    public function exportTrialBalance(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'format' => 'required|in:xlsx,csv'
        ]);

        $trialBalance = $this->trialBalanceService->generate(
            $validated['start'],
            $validated['end']
        );

        $fileName = 'trial_balance_' . $validated['start'] . '_to_' . $validated['end'] . '.' . $validated['format'];

        return Excel::download(
            new TrialBalanceExport($trialBalance, $validated['start'], $validated['end']),
            $fileName
        );
    }
}
