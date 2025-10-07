<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
   protected $table = 'benefits';
    protected $fillable = ['employee_id', 'benefit_type', 'amount', 'start_date', 'end_date', 'description'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relacionamentos
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
