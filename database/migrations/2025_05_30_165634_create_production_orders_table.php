<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status');
            $table->text('raw_materials')->nullable();
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
