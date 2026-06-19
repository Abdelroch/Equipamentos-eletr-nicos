<?php

//  macbook-pro-(retina,13-polegadas,-2015)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'product';

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_fornecedor');
    }
    public function sales()
    {
        return $this->hasMany(Sale::class, 'id_product');
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class, 'product_id');
    }

    protected $casts = [
        'imagens'  => 'array', // Converte JSON para array automaticamente
        'cores'    => 'array', // Lista de opções de cor, ex: ["Prateado","Dourado"]
        'tamanhos' => 'array', // Lista de opções de tamanho, ex: ["S","M","L"]
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            $slug = Str::slug($product->nome);
            $originalSlug = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i;
                $i++;
            }
            $product->slug = $slug;
        });
    }

}
