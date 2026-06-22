<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $table = "customer";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    /**
     * Nome de exibição: prioriza o User vinculado (fluxo normal de registro
     * no site); cai para 'nome_legado' apenas para customers migrados da
     * antiga tabela 'client', que não têm User correspondente.
     */
    public function getNomeAttribute(): string
    {
        return $this->user->name ?? $this->nome_legado ?? 'Cliente sem nome';
    }
}
