<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idh_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name'); // e.g., Education Level, Income Distribution
            $table->decimal('value', 8, 2);
            $table->date('recorded_at');
            $table->string('region')->nullable(); // e.g., Province or Country
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idh_metrics');
    }
};
