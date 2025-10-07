<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    protected $table = 'production_orders';
    protected $fillable = ['product_id', 'quantity', 'start_date', 'end_date', 'status', 'raw_materials'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'raw_materials' => 'array', // JSON decodificado como array
    ];

    // Relacionamentos
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
