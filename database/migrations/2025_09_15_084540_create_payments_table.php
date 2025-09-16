<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('payment_ref', 30)->nullable()->unique();
            $table->date('paid_at');
            $table->decimal('amount_paid', 18, 2);
            $table->string('method', 50)->nullable();
            $table->timestamps();

            $table->index(['invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
