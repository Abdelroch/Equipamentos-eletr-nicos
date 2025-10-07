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
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('numero');
            $table->string('pais');
            $table->string('provincia');
            $table->string('imagem');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function boot()
{
    if (Schema::hasTable('supplier')) {
        $suppliers = DB::table('supplier')->get();
        view()->share('suppliers', $suppliers);
    }
}
};
