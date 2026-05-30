<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->nullable();
            $table->text('descricao');
            $table->decimal('preco', 15, 2)->default(0.00); // ← sem ->change()
            $table->integer('quantidade_disponivel')->default(0);
            $table->string('categoria');
            $table->string('imagem')->default('default.jpg');
            $table->json('imagens')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('status')->nullable();
            $table->enum('estado_venda', [
                'disponivel','reservado','ofertado','vendido','cancelado','pendente'
            ])->default('disponivel');
            $table->unsignedBigInteger('id_fornecedor'); // ← separado do constrained
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_fornecedor')
                ->references('id')
                ->on('supplier')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('product');
        Schema::enableForeignKeyConstraints();
    }
};
