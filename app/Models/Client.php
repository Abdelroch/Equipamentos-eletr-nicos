<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';

    protected $fillable = [
        'nome',
        'numero',
        'provincia',
        'imagem',
    ];

    // Relacionamentos
    public function sales()
    {
        return $this->hasMany(Sale::class, 'id_cliente');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }
}
