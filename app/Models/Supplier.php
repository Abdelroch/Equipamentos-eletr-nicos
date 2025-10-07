<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['nome', 'email', 'numero', 'pais', 'provincia', 'imagem'];

    // Relacionamentos
    public function products()
    {
        return $this->hasMany(Product::class, 'id_fornecedor');
    }
}
