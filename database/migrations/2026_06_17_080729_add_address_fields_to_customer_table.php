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
        Schema::table('customer', function (Blueprint $table) {
            $table->string('address')->nullable()->after('phone_number');
            $table->string('bairro')->nullable()->after('address');
            $table->string('province')->nullable()->after('bairro');
            $table->string('reference_point')->nullable()->after('province');
            // ponto de referência para entrega
            $table->string('payment_method')->nullable()->after('reference_point');
            // BAI, BFA, Multicaixa Express, TPA, Numerário
        });
    }

    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'bairro',
                'province',
                'reference_point',
                'payment_method',
            ]);
        });
    }
};
