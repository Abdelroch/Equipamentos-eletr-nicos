<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $fillable = ['tax_type', 'amount', 'due_date', 'status', 'sale_id', 'notes'];
    protected $casts = [
        'due_date' => 'date',
    ];

    // Relacionamentos
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
