<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderNegotiation extends Model
{
    //

    use SoftDeletes;

    protected $guarded = [];

    public $table = "order_negotiations";

    protected $casts = [
        'original_price' => 'decimal:2',
        'proposed_price' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
