<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            // Nullable por enquanto: o comando de migração de dados vai
            // preencher a partir de id_cliente antes de tornarmos obrigatório.
            $table->foreignId('customer_id')
                ->nullable()
                ->after('id_cliente')
                ->constrained('customer')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};
