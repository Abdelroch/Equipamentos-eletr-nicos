<?php

namespace App\Http\Controllers\Admin\IdhMetric;

use App\Http\Controllers\Controller;
use App\Models\IdhMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        $metricas = IdhMetric::all();
        return view('admin.idh_metrics.list.index', ['data' => ['metricas_idh' => $metricas]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric_name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'recorded_at' => 'required|date',
            'region' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $metrica = IdhMetric::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Criação de Métrica IDH',
            'descricao' => "Métrica {$metrica->metric_name} criada.",
        ]);

        return redirect()->route('admin.gestao.idh-metricas')->with('metricaCadastrada', 'Métrica IDH cadastrada');
    }

    public function update(Request $request, $id)
    {
        $metrica = IdhMetric::findOrFail($id);
        $validated = $request->validate([
            'metric_name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'recorded_at' => 'required|date',
            'region' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $metrica->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Atualização de Métrica IDH',
            'descricao' => "Métrica {$metrica->metric_name} atualizada.",
        ]);

        return redirect()->route('admin.gestao.idh-metricas')->with('metricaAtualizada', 'Métrica IDH atualizada');
    }

    public function destroy($id)
    {
        $metrica = IdhMetric::findOrFail($id);
        $metrica->delete();

        Log::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'accao' => 'Exclusão de Métrica IDH',
            'descricao' => "Métrica {$metrica->metric_name} removida.",
        ]);

        return redirect()->route('admin.gestao.idh-metricas')->with('metricaRemovida', 'Métrica IDH removida');
    }
}
