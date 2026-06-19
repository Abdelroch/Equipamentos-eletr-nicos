<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->json('cores')->nullable();      // ou string/text se preferir
            $table->json('tamanhos')->nullable();
        });
    }

    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['cores', 'tamanhos']);
        });
    }
};
