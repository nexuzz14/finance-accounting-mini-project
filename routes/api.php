<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChartOfAccountController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;


Route::apiResource('chart-of-accounts', ChartOfAccountController::class);
Route::apiResource('journals', JournalController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('payments', PaymentController::class);
Route::get('trial-balance', [ReportController::class, 'trialBalance']);
Route::get('trial-balance/export', [ReportController::class, 'exportTrialBalance']);

