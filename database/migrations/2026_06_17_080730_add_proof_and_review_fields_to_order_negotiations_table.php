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
        Schema::table('order_negotiations', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('notes');
            $table->timestamp('proof_submitted_at')->nullable()->after('payment_proof');
            $table->string('admin_notes')->nullable()->after('proof_submitted_at');
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // endereço de entrega capturado no momento da encomenda
            $table->string('delivery_address')->nullable()->after('delivery_location');
            $table->string('delivery_bairro')->nullable()->after('delivery_address');
            $table->string('delivery_reference')->nullable()->after('delivery_bairro');
        });
    }

    public function down(): void
    {
        Schema::table('order_negotiations', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'payment_proof',
                'proof_submitted_at',
                'admin_notes',
                'reviewed_at',
                'reviewed_by',
                'delivery_address',
                'delivery_bairro',
                'delivery_reference',
            ]);
        });
    }
};
