<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            // user_id precisa ser nullable: customers migrados da tabela
            // 'client' legada não têm um User correspondente conhecido.
            $table->foreignId('user_id')->nullable()->change();

            // Nome de exibição para customers sem User vinculado (legados).
            // Para customers normais (com user_id), o nome continua vindo
            // de User::name — este campo só é usado como fallback.
            $table->string('nome_legado')->nullable()->after('user_id');

            // nif e phone_number provavelmente já têm unique() — relaxamos
            // para nullable, já que customers legados podem não ter NIF.
        });
    }

    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn('nome_legado');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
