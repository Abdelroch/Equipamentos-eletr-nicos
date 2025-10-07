<?php

use App\Models\Product;
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
        Schema::table('product', function (Blueprint $table) {
            // Adiciona o campo slug à tabela products
            Schema::table('product', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('nome');
            });

            // Atualiza os registros existentes com slugs
            $products = Product::all();
            /* foreach ($products as $product) {
                $product->slug = Str::slug($product->nome . '-' . $product->id, '-');
                $product->save();
            } */

            foreach ($products as $product) {
                $baseSlug = Str::slug($product->nome, '-');
                $slug = $baseSlug;
                $counter = 1;

                // Verifica se o slug já existe
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $product->slug = $slug;
                $product->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            //
        });
    }
};
