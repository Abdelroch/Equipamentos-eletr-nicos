<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('tax_type');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->string('status');
            $table->foreignId('sale_id')->nullable()->constrained('sale')->onDelete('set null');
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
