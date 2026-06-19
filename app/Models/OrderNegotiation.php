<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderNegotiation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $table = "order_negotiations";

    protected $casts = [
        'original_price'  => 'decimal:2',
        'proposed_price'  => 'decimal:2',
        'delivery_cost'   => 'decimal:2',
        'total_price'     => 'decimal:2',
        'reserved_until'  => 'datetime',
        'reviewed_at'     => 'datetime',
        'proof_submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    /**
     * Indica se a reserva de stock ainda está dentro do prazo de 24h.
     */
    public function getIsReservationActiveAttribute(): bool
    {
        return $this->status === 'accepted'
            && $this->reserved_until !== null
            && $this->reserved_until->isFuture();
    }
}
