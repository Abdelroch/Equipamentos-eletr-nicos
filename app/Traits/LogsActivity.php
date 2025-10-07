<?php
namespace App\Traits;

trait LogsActivity
{
    protected function logActivity($action, $description, $request)
    {
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => $action,
            'descricao' => $description,
        ]);
    }
}
