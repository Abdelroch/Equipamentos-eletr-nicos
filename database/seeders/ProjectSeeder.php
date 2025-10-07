<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::first();

        Project::create([
            'name' => 'Projeto Exemplo 1',
            'description' => 'Descrição do projeto 1',
            'start_date' => '2025-01-01',
            'end_date' => '2025-06-30',
            'status' => 'Em Andamento',
            'budget' => 100000.00,
            'responsible_id' => $employee->id,
        ]);

        Project::create([
            'name' => 'Projeto Exemplo 2',
            'description' => 'Descrição do projeto 2',
            'start_date' => '2025-02-01',
            'end_date' => null,
            'status' => 'Planejado',
            'budget' => 50000.00,
            'responsible_id' => $employee->id,
        ]);
    }
}
