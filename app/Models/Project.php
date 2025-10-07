<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'budget', 'responsible_id'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relacionamentos
    public function responsible()
    {
        return $this->belongsTo(Employee::class, 'responsible_id');
    }
}
