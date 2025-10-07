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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->decimal('preco', 10, 2)->nullable(true)->default(0);
            $table->integer('quantidade_disponivel')->default(0);
            $table->string('categoria');
            $table->string('imagem')->default('default.jpg');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('id_fornecedor')
                ->constrained('supplier')
                ->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
