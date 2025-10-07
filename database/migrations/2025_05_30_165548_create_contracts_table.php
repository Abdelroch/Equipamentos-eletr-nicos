<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('sign_date');
            $table->date('expiration_date')->nullable();
            $table->string('status'); // e.g., Active, Expired, Terminated
            $table->foreignId('client_id')->constrained('client')->onDelete('cascade');
            $table->string('document_path')->nullable(); // Path to stored contract file
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
