<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts');
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->timestamps();

            $table->index(['journal_id']);
            $table->index(['account_id']);
            $table->index(['account_id', 'journal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_lines');
    }
};

