<?php

namespace Database\Seeders;

use App\Models\Benefit;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::first();

        Benefit::create([
            'employee_id' => $employee->id,
            'benefit_type' => 'Plano de Saúde',
            'amount' => 200.00,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'description' => 'Plano de saúde corporativo',
        ]);

        Benefit::create([
            'employee_id' => $employee->id,
            'benefit_type' => 'Bônus',
            'amount' => 1000.00,
            'start_date' => '2025-06-01',
            'end_date' => null,
            'description' => 'Bônus por desempenho',
        ]);
    }
}
