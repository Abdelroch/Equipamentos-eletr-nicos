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
    ];
    Protected $guarded = [];
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
