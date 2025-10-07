<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{

    public function index()
    {
        $projetos = Project::with('responsible')->get();
        $Funcionario = Employee::all();
        return view('admin.projects.list.index', ['data' => ['projetos' => $projetos, 'funcionarios' => $Funcionario]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:Planejado,Em Andamento,Concluído,Cancelado',
            'budget' => 'required|numeric|min:0',
            'responsible_id' => 'required|exists:employees,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $project = Project::create($validated);

            $currentBalance = Budget::sum('amount') ?? 0;
            Budget::create([
                'balance' => $currentBalance - $validated['budget'],
                'description' => "Orçamento do projeto {$project->name}",
                'transaction_type' => 'Despesa',
                'amount' => -$validated['budget'],
                'transaction_date' => $validated['start_date'],
                'user_id' => auth()->id(),
            ]);

            $project->update(['budget_id' => Budget::latest()->first()->id]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Criação de Projeto',
                'descricao' => "Projeto {$project->name} criado.",
            ]);
        });

        return redirect()->route('admin.gestao.projetos')->with('projetoCadastrado', 'Projeto cadastrado com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:Planejado,Em Andamento,Concluído,Cancelado',
            'budget' => 'required|numeric|min:0',
            'responsible_id' => 'required|exists:employees,id',
        ]);

        DB::transaction(function () use ($validated, $request, $project) {
            if ($project->budget_id) {
                $oldBudget = Budget::findOrFail($project->budget_id);
                $currentBalance = Budget::sum('amount') - $oldBudget->amount;
                $oldBudget->delete();
            } else {
                $currentBalance = Budget::sum('amount') ?? 0;
            }

            $project->update($validated);

            Budget::create([
                'balance' => $currentBalance - $validated['budget'],
                'description' => "Atualização do orçamento do projeto {$project->name}",
                'transaction_type' => 'Despesa',
                'amount' => -$validated['budget'],
                'transaction_date' => $validated['start_date'],
                'user_id' => auth()->id(),
            ]);

            $project->update(['budget_id' => Budget::latest()->first()->id]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de Projeto',
                'descricao' => "Projeto {$project->name} atualizado.",
            ]);
        });

        return redirect()->route('admin.gestao.projetos')->with('projetoAtualizado', 'Projeto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            if ($project->budget_id) {
                $budget = Budget::findOrFail($project->budget_id);
                $budget->delete();
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de Projeto',
                'descricao' => "Projeto {$project->name} removido.",
            ]);

            $project->delete();
        });

        return redirect()->route('admin.gestao.projetos')->with('projetoRemovido', 'Projeto removido com sucesso.');
    }
}
