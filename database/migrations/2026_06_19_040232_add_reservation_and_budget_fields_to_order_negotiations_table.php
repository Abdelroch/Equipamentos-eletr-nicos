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
            $table->timestamp('reserved_until')->nullable()->after('status');
            $table->unsignedBigInteger('budget_id')->nullable()->after('reserved_until');

            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_negotiations', function (Blueprint $table) {
            $table->dropForeign(['budget_id']);
            $table->dropColumn(['reserved_until', 'budget_id']);
        });
    }
    
};
