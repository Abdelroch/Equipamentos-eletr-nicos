<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'balance',
        'description',
        'transaction_type',
        'amount',
        'transaction_date',
        'user_id',
        'category', // faltava aqui — sem isso, $guarded = [] abaixo não tem
                    // efeito nenhum sobre este campo, e Budget::create() descartava
                    // 'category' em silêncio, sem erro. Resultado: a validação de
                    // limite mensal por categoria no BudgetService nunca funcionava
                    // de verdade, porque getMonthlySpending() filtrava por uma
                    // coluna que ficava sempre NULL.
    ];

    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function financial()
    {
        return $this->hasOne(Financial::class);
    }
}
