@extends('layouts.admin.body')
@section('title', 'Relatórios')

@section('conteudo')
    <h1>Relatório Financeiro</h1>

    <form method="GET" action="{{ route('admin.reports.financial') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Data Inicial</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label>Data Final</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label>Tipo</label>
                <select name="transaction_type" class="form-control">
                    <option value="">Todos</option>
                    <option value="Receita" {{ request('transaction_type') == 'Receita' ? 'selected' : '' }}>Receita</option>
                    <option value="Despesa" {{ request('transaction_type') == 'Despesa' ? 'selected' : '' }}>Despesa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Categoria</label>
                <select name="category" class="form-control">
                    <option value="">Todas</option>
                    <option value="Salário">Salário</option>
                    <option value="Imposto">Imposto</option>
                    <option value="Projeto">Projeto</option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Usuário</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->transaction_type }}</td>
                    <td>{{ number_format($transaction->amount, 2, ',', '.') }} KZ</td>
                    <td>{{ $transaction->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert alert-info">
        <strong>Total Receitas:</strong> {{ number_format($totalRevenue, 2, ',', '.') }} KZ<br>
        <strong>Total Despesas:</strong> {{ number_format($totalExpense, 2, ',', '.') }} KZ
    </div>

    <canvas id="salesChart" height="100"></canvas>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($meses),
                    datasets: [{
                        label: 'Receitas por Mês',
                        data: @json($valores),
                        borderColor: '#007bff',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    @endsection
@endsection
