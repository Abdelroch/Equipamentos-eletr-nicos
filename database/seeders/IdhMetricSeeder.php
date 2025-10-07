<?php

namespace Database\Seeders;

use App\Models\IdhMetric;
use Illuminate\Database\Seeder;

class IdhMetricSeeder extends Seeder
{
    public function run(): void
    {
        IdhMetric::create([
            'metric_name' => 'Nível de Educação',
            'value' => 0.75,
            'recorded_at' => '2025-01-01',
            'region' => 'Luanda',
            'notes' => 'Média de escolaridade na província',
        ]);

        IdhMetric::create([
            'metric_name' => 'Distribuição de Renda',
            'value' => 0.60,
            'recorded_at' => '2025-02-01',
            'region' => 'Benguela',
            'notes' => 'Índice de Gini',
        ]);
    }
}
