<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            $table->foreignId('order_negotiation_id')->nullable()->unique()->after('id')
                ->constrained('order_negotiations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            $table->dropForeign(['order_negotiation_id']);
            $table->dropColumn('order_negotiation_id');
        });
    }
};
