<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = ['title', 'description', 'sign_date', 'expiration_date', 'status', 'client_id', 'document_path'];

    protected $casts = [
        'sign_date' => 'date',
        'expiration_date' => 'date',
    ];

    // Relacionamentos
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
