<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closing_periods', function (Blueprint $table) {
            $table->id();
            $table->char('period', 7)->unique(); // YYYY-MM format
            $table->boolean('is_locked')->default(false);
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closing_periods');
    }
};
