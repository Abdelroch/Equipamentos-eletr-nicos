<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário principal (admin)
        $admin = User::create([
            'name' => 'MK MAIN',
            'email' => 'geral@mklda.ao',
            'password' => Hash::make('mklimitada@2025'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Usuários adicionais de exemplo
        $gestor = User::create([
            'name' => 'Gestor Exemplo',
            'email' => 'gestor@mklda.ao',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $gestor->assignRole('gestor');

        $contabilista = User::create([
            'name' => 'Contabilista Exemplo',
            'email' => 'contabilista@mklda.ao',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $contabilista->assignRole('contabilista');
    }
}
