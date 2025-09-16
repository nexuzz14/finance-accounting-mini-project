<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChartOfAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ChartOfAccount::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderBy('code')->get(); 

        return response()->json([
            'success' => true,
            'data' => $accounts,
        ]);
    }


    public function show(ChartOfAccount $chartOfAccount): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $chartOfAccount,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:chart_of_accounts',
            'name' => 'required|string|max:100',
            'normal_balance' => 'required|in:DR,CR',
            'is_active' => 'boolean',
        ]);

        $account = ChartOfAccount::create($validated);

        return response()->json([
            'success' => true,
            'data' => $account,
            'message' => 'Chart of Account created successfully',
        ], 201);
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|string|max:100',
            'normal_balance' => 'required|in:DR,CR',
            'is_active' => 'boolean',
        ]);

        $chartOfAccount->update($validated);

        return response()->json([
            'success' => true,
            'data' => $chartOfAccount,
            'message' => 'Chart of Account updated successfully',
        ]);
    }

    public function destroy(ChartOfAccount $chartOfAccount): JsonResponse
    {
        $chartOfAccount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chart of Account deleted successfully',
        ]);
    }
}
