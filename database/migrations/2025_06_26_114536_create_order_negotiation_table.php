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
        Schema::create('order_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->references('id')->on('users');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->references('id')->on('product');
            $table->integer('quantity')->default(1);
            $table->decimal('original_price', 15, 2);
            $table->decimal('proposed_price', 15, 2)->nullable();
            $table->decimal('delivery_cost', 15, 2)->nullable();
            $table->decimal('total_price', 15, 2);
            $table->string('delivery_location')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_negotiation');
    }
};
