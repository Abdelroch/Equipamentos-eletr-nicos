<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdhMetric extends Model
{
    protected $table = 'idh_metrics';
    protected $guarded = [];
    protected $casts = [
        'recorded_at' => 'date',
    ];
}
