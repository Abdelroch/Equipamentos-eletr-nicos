<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'nome' => 'Cliente Exemplo 1',
            'numero' => '123456789',
            'provincia' => 'Luanda',
            'imagem' => 'clients/client1.jpg',
        ]);

        Client::create([
            'nome' => 'Cliente Exemplo 2',
            'numero' => '987654321',
            'provincia' => 'Benguela',
            'imagem' => 'clients/client2.jpg',
        ]);
    }
}
