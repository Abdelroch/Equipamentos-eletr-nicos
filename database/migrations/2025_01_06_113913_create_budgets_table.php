<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 12, 2)->default(0); // Saldo atual da empresa
            $table->text('description')->nullable(); // Descrição da transação que afetou o orçamento
            $table->string('transaction_type'); // Tipo: Receita, Despesa, Empréstimo, Saque
            $table->decimal('amount', 12, 2); // Valor da transação
            $table->date('transaction_date'); // Data da transação
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuário responsável
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};