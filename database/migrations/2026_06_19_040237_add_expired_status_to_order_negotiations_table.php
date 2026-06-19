<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE order_negotiations MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'rejected',
            'awaiting_confirmation',
            'confirmed',
            'cancelled',
            'expired'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Reverte para a lista original, sem 'expired'.
        // Atenção: se já existirem linhas com status='expired', isto falha.
        // Nesse caso, actualize-as manualmente antes do rollback.
        DB::statement("ALTER TABLE order_negotiations MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'rejected',
            'awaiting_confirmation',
            'confirmed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};
