<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('benefit_type');
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->onDelete('set null');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
