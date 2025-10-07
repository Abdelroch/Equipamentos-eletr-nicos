<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sale';
    protected $fillable = ['id_cliente', 'id_product', 'quantidade', 'data_venda', 'total', 'budget_id'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function financials()
    {
        return $this->hasMany(Financial::class, 'sale_id');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'sale_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }
}
