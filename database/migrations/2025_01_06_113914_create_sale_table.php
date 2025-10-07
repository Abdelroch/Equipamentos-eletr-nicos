<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->decimal('total', 12, 2)->default(0); // Valor total da venda
            $table->date('data_venda');
            $table->foreignId('id_cliente')->constrained('client')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('product')->onDelete('cascade');
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->onDelete('set null'); // Vincula ao orçamento
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale');
    }
};
