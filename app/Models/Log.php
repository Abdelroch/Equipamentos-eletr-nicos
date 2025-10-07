<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $fillable = ['user_id', 'ip', 'accao', 'descricao'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
