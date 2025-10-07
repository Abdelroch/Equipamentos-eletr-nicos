<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        Log::create([
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'accao' => 'Login',
            'descricao' => 'Usuário fez login no sistema',
        ]);

        Log::create([
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'accao' => 'Criação de Cliente',
            'descricao' => 'Cliente Exemplo 1 criado',
        ]);
    }
}
