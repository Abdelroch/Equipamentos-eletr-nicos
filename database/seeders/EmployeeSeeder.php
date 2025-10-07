<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'name' => 'Funcionário Exemplo 1',
            'email' => 'funcionario1@mklda.ao',
            'phone' => '912345678',
            'department' => 'RH',
            'salary' => 50000.00,
            'hire_date' => '2024-01-01',
            'position' => 'Analista de RH',
            'image' => 'employees/employee1.jpg',
        ]);

        Employee::create([
            'name' => 'Funcionário Exemplo 2',
            'email' => 'funcionario2@mklda.ao',
            'phone' => '923456789',
            'department' => 'Financeiro',
            'salary' => 60000.00,
            'hire_date' => '2024-02-01',
            'position' => 'Contador',
            'image' => 'employees/employee2.jpg',
        ]);
    }
}
