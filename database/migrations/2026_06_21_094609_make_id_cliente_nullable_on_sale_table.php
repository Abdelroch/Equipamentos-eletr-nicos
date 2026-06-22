<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('sale', 'id_cliente')) {
            // Raw SQL em vez de ->change() para não depender do pacote
            // doctrine/dbal. id_cliente está deprecated (substituída por
            // customer_id), por isso só precisa de aceitar NULL — não
            // precisamos de mais validações de tipo aqui.
            DB::statement('ALTER TABLE `sale` MODIFY `id_cliente` BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale', 'id_cliente')) {
            DB::statement('ALTER TABLE `sale` MODIFY `id_cliente` BIGINT UNSIGNED NOT NULL');
        }
    }
};
