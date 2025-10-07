<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Budget;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $funcionarios = Employee::all();
        return view('admin.employees.list.index', ['data' => ['funcionarios' => $funcionarios]]);
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'nullable|string|max:20',
        'department' => 'required|string|max:255',
        'hire_date' => 'required|date',
        'salary' => 'required|numeric|min:0',
        'position' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
    ]);

    DB::transaction(function () use ($validated, $request) {
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        $employee = Employee::create($validated);

        $budgetId = BudgetService::updateBudget(
            $validated['salary'],
            "Salário do funcionário {$employee->name}",
            'Despesa',
            $validated['hire_date'],
            auth()->id()
        );

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Criação de Funcionário',
            'descricao' => "Funcionário {$employee->name} criado.",
        ]);
    });

    return redirect()->route('admin.gestao.funcionarios')->with('funcionarioCadastrado', 'Funcionário cadastrado com sucesso.');
}

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
        ]);

        DB::transaction(function () use ($validated, $request, $employee) {
            if ($request->hasFile('image')) {
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
                $validated['image'] = $request->file('image')->store('employees', 'public');
            } else {
                $validated['image'] = $employee->image;
            }

            $employee->update($validated);

            $currentBalance = Budget::sum('amount') ?? 0;
            Budget::create([
                'balance' => $currentBalance - $validated['salary'],
                'description' => "Atualização do salário do funcionário {$employee->name}",
                'transaction_type' => 'Despesa',
                'amount' => -$validated['salary'],
                'transaction_date' => $validated['hire_date'],
                'user_id' => auth()->id(),
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de Funcionário',
                'descricao' => "Funcionário {$employee->name} atualizado.",
            ]);
        });

        return redirect()->route('admin.gestao.funcionarios')->with('funcionarioAtualizado', 'Funcionário atualizado com sucesso.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $employee = Employee::findOrFail($id);
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de Funcionário',
                'descricao' => "Funcionário {$employee->name} removido.",
            ]);

            $employee->delete();
        });

        return redirect()->route('admin.gestao.funcionarios')->with('funcionarioRemovido', 'Funcionário removido com sucesso.');
    }
}
