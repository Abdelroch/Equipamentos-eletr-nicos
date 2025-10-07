<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    protected $table = 'financials';
    protected $fillable = ['type', 'amount', 'due_date', 'status', 'description', 'sale_id'];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relacionamentos
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
