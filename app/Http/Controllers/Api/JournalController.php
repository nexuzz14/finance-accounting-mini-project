<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalLine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::with('journalLines');

        if ($request->has('start_date')) {
            $query->where('posting_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('posting_date', '<=', $request->end_date);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ref_no', 'like', "%{$search}%")
                ->orWhere('memo', 'like', "%{$search}%");
            });
        }

        $journals = $query->orderBy('posting_date', 'desc')->get();

        $data = $journals->map(function ($j) {
            return [
                'id'           => $j->id,
                'ref_no'       => $j->ref_no,
                'posting_date' => $j->posting_date->format('Y-m-d'),
                'memo'         => $j->memo,
                'total_debit'  => $j->journalLines->sum('debit'),
                'total_credit' => $j->journalLines->sum('credit'),
                'status'       => $j->status,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function show(Journal $journal)
    {
        $journal->load('journalLines.account');

        return response()->json([
            'success' => true,
            'data' => [
                'id'           => $journal->id,
                'ref_no'       => $journal->ref_no,
                'posting_date' => $journal->posting_date->format('Y-m-d'),
                'memo'         => $journal->memo,
                'status'       => $journal->status,
                'total_debit'  => $journal->journalLines->sum('debit'),
                'total_credit' => $journal->journalLines->sum('credit'),
                'lines'        => $journal->journalLines->map(function ($line) {
                    return [
                        'account_id'   => $line->account_id,
                        'account_code' => $line->account->code,
                        'account_name' => $line->account->name,
                        'debit'        => $line->debit,
                        'credit'       => $line->credit,
                    ];
                }),
            ]
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ref_no' => 'required|string|max:20|unique:journals',
            'posting_date' => 'required|date',
            'memo' => 'nullable|string|max:255',
            'status' => 'required|in:posted',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($validated['lines'])->sum('debit');
        $totalCredit = collect($validated['lines'])->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Journal must be balanced. Total debit must equal total credit.',
            ], 422);
        }

        foreach ($validated['lines'] as $line) {
            if ($line['debit'] == 0 && $line['credit'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Each line must have either debit or credit amount greater than 0.',
                ], 422);
            }
        }

        DB::transaction(function() use ($validated, &$journal) {
            $journal = Journal::create([
                'ref_no' => $validated['ref_no'],
                'posting_date' => $validated['posting_date'],
                'memo' => $validated['memo'],
                'status' => $validated['status'],
            ]);

            foreach ($validated['lines'] as $line) {
                JournalLine::create([
                    'journal_id' => $journal->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                ]);
            }
        });

        $journal->load('journalLines.account');

        return response()->json([
            'success' => true,
            'data' => $journal,
            'message' => 'Journal created successfully',
        ], 201);
    }

    public function update(Request $request, Journal $journal): JsonResponse
    {
        $validated = $request->validate([
            'ref_no' => 'required|string|max:20|unique:journals,ref_no,' . $journal->id,
            'posting_date' => 'required|date',
            'memo' => 'nullable|string|max:255',
            'status' => 'required|in:posted',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($validated['lines'])->sum('debit');
        $totalCredit = collect($validated['lines'])->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Journal must be balanced. Total debit must equal total credit.',
            ], 422);
        }

        DB::transaction(function() use ($validated, $journal) {
            $journal->update([
                'ref_no' => $validated['ref_no'],
                'posting_date' => $validated['posting_date'],
                'memo' => $validated['memo'],
                'status' => $validated['status'],
            ]);

            $journal->journalLines()->delete();

            foreach ($validated['lines'] as $line) {
                JournalLine::create([
                    'journal_id' => $journal->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                ]);
            }
        });

        $journal->load('journalLines.account');

        return response()->json([
            'success' => true,
            'data' => $journal,
            'message' => 'Journal updated successfully',
        ]);
    }

    public function destroy(Journal $journal): JsonResponse
    {
        $journal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Journal deleted successfully',
        ]);
    }
}
