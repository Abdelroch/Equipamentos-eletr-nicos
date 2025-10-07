<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = ['name', 'email', 'phone', 'department', 'salary', 'hire_date', 'position', 'image'];

    protected $casts = [
        'hire_date' => 'date',
    ];

    // Relacionamentos
    public function projects()
    {
        return $this->hasMany(Project::class, 'responsible_id');
    }

    public function benefits()
    {
        return $this->hasMany(Benefit::class, 'employee_id');
    }
}
