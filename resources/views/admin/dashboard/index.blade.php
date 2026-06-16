@extends('layouts.admin.body')

@section('title', 'Dashboard')

@section('conteudo')
    <div class="container-fluid">
        <h4 class="mb-3"><strong>Olá, {{ Auth::user()->name }}!</strong></h4>
        <p class="mb-4">A gestão eficiente começa aqui. Monitore todos os aspectos da MK LDA em tempo real!</p>

        <!-- Cards com Contagens -->
        <div class="row g-3">
            @foreach ([
                ['title' => 'Clientes', 'value' => $data['clientes'], 'icon' => 'ti-users', 'color' => 'primary'],
                ['title' => 'Produtos', 'value' => $data['produtos'], 'icon' => 'ti-package', 'color' => 'success'],
               /*  ['title' => 'Fornecedores', 'value' => $data['fornecedores'], 'icon' => 'ti-truck', 'color' => 'warning'], */
                ['title' => 'Vendas', 'value' => $data['vendas'], 'icon' => 'ti-shopping-cart', 'color' => 'danger'],
               /*  ['title' => 'Funcionários', 'value' => $data['funcionarios'], 'icon' => 'ti-user-circle', 'color' => 'info'],
                ['title' => 'Projetos', 'value' => $data['projetos'], 'icon' => 'ti-briefcase', 'color' => 'primary'],
                ['title' => 'Financeiros', 'value' => $data['financeiros'], 'icon' => 'ti-currency-dollar', 'color' => 'success'],
                ['title' => 'Contratos', 'value' => $data['contratos'], 'icon' => 'ti-file-text', 'color' => 'warning'],
                ['title' => 'Ordens de Produção', 'value' => $data['ordens_producao'], 'icon' => 'ti-tools', 'color' => 'danger'],
                ['title' => 'Impostos', 'value' => $data['impostos'], 'icon' => 'ti-wallet', 'color' => 'info'],
                ['title' => 'Benefícios', 'value' => $data['beneficios'], 'icon' => 'ti-gift', 'color' => 'primary'],
                ['title' => 'Métricas IDH', 'value' => $data['idh_metricas'], 'icon' => 'ti-chart-bar', 'color' => 'success'],
                ['title' => 'Atividades', 'value' => $data['atividades'], 'icon' => 'ti-file-description', 'color' => 'warning'], */
            ] as $card)
                <div class="col-md-4 col-lg-3">
                    <div class="card bg-light-{{ $card['color'] }} shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="ti {{ $card['icon'] }} fs-7 text-{{ $card['color'] }} me-3"></i>
                            <div>
                                <h6 class="mb-0 card-title">{{ $card['title'] }}</h6>
                                <p class="card-text fs-5">{{ $card['value'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Cards Adicionais -->
           <div lass="col-md-4 col-lg-3">
                <div class="shadow-sm card bg-light-info">
                    <div class="card-body d-flex align-items-center">
                        <i class="ti ti-currency-dollar fs-7 text-info me-3"></i>
                        <div>
                            <h6 class="mb-0 card-title">Valor arrecadado</h6>
                            <p class="card-text fs-5">{{ number_format($data['total_vendas'], 2, ',', '.') }} AOA</p>
                        </div>
                    </div>
                </div>
            </div>
             {{--<div class="col-md-4 col-lg-3">
                <div class="shadow-sm card bg-light-primary">
                    <div class="card-body d-flex align-items-center">
                        <i class="ti ti-chart-bar fs-7 text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0 card-title">Média IDH</h6>
                            <p class="card-text fs-5">{{ number_format($data['media_idh'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-md-4 col-lg-3">
                <div class="shadow-sm card bg-light-success">
                    <div class="card-body d-flex align-items-center">
                        <i class="ti ti-wallet fs-7 text-success me-3"></i>
                        <div>
                            <h6 class="mb-0 card-title">Saldo do Orçamento</h6>
                            <p class="card-text fs-5">{{ number_format($data['saldo_orcamento'], 2, ',', '.') }} AOA</p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <!-- Gráficos -->
        <div class="mt-5 row">
             <div class="col-lg-6">
                <div class="shadow-sm card" style="height: 400px; overflow: hidden;">
                    <div class="card-body" style="height: 100%;">
                        <h5 class="card-title">Visão Geral de Entidades</h5>
                        <canvas id="barChart" style="max-height: 300px; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shadow-sm card" style="height: 400px; overflow: hidden;">
                    <div class="card-body" style="height: 100%;">
                        <h5 class="card-title">Distribuição de Entidades</h5>
                        <canvas id="doughnutChart" style="max-height: 300px; height: 300px;"></canvas>
                    </div>
                </div>
            </div> --}}
            <div class="mt-4 col-lg-12">
                <div class="shadow-sm card" style="height: 400px; overflow: hidden;">
                    <div class="card-body" style="height: 100%;">
                        <h5 class="card-title">Tendência de Vendas (Últimos 6 Meses)</h5>
                        <canvas id="lineChart" style="max-height: 300px; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listas Recentes em Abas -->
        <div class="mt-5 shadow-sm card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Atividades Recentes</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="recentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="clientes-tab" data-bs-toggle="tab" data-bs-target="#clientes" type="button" role="tab">Clientes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="produtos-tab" data-bs-toggle="tab" data-bs-target="#produtos" type="button" role="tab">Produtos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fornecedores-tab" data-bs-toggle="tab" data-bs-target="#fornecedores" type="button" role="tab">Fornecedores</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vendas-tab" data-bs-toggle="tab" data-bs-target="#vendas" type="button" role="tab">Vendas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="funcionarios-tab" data-bs-toggle="tab" data-bs-target="#funcionarios" type="button" role="tab">Funcionários</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="projetos-tab" data-bs-toggle="tab" data-bs-target="#projetos" type="button" role="tab">Projetos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="financeiros-tab" data-bs-toggle="tab" data-bs-target="#financeiros" type="button" role="tab">Financeiros</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contratos-tab" data-bs-toggle="tab" data-bs-target="#contratos" type="button" role="tab">Contratos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ordens-tab" data-bs-toggle="tab" data-bs-target="#ordens-producao" type="button" role="tab">Ordens de Produção</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="impostos-tab" data-bs-toggle="tab" data-bs-target="#impostos" type="button" role="tab">Impostos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="beneficios-tab" data-bs-toggle="tab" data-bs-target="#beneficios" type="button" role="tab">Benefícios</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="metricas-idh-tab" data-bs-toggle="tab" data-bs-target="#metricas-idh" type="button" role="tab">Métricas IDH</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="atividades-tab" data-bs-toggle="tab" data-bs-target="#atividades" type="button" role="tab">Atividades</button>
                    </li>
                </ul>
                <div class="tab-content" id="recentTabsContent">
                    <div class="tab-pane fade show active" id="clientes" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosClientes as $cliente)
                                <li class="list-group-item">{{ $cliente->nome }}</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum cliente recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="produtos" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosProdutos as $produto)
                                <li class="list-group-item">{{ $produto->nome }}</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum produto recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="fornecedores" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosFornecedores as $fornecedor)
                                <li class="list-group-item">{{ $fornecedor->nome }}</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum fornecedor recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="vendas" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimasVendas as $venda)
                                <li class="list-group-item">{{ $venda->client->nome ?? 'Cliente removido' }} comprou {{ $venda->product->nome ?? 'Produto removido' }} ({{ number_format($venda->quantidade * ($venda->product->preco ?? 0), 2, ',', '.') }} AOA)</li>
                            @empty
                                <li class="text-center list-group-item">Nenhuma venda recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="funcionarios" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosFuncionarios as $funcionario)
                                <li class="list-group-item">{{ $funcionario->name }} ({{ $funcionario->position }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum funcionário recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="projetos" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosProjetos as $projeto)
                                <li class="list-group-item">{{ $projeto->name }} ({{ $projeto->status }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum projeto recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="financeiros" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosFinanceiros as $financeiro)
                                <li class="list-group-item">{{ $financeiro->transaction_type }}: {{ $financeiro->description ?? 'Sem descrição' }} ({{ number_format($financeiro->amount, 2, ',', '.') }} AOA)</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum registro financeiro recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="contratos" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosContratos as $contrato)
                                <li class="list-group-item">{{ $contrato->title }} ({{ $contrato->status }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum contrato recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="ordens-producao" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimasOrdensProducao as $ordem)
                                <li class="list-group-item">{{ $ordem->product->nome ?? 'Produto removido' }} (Qtd: {{ $ordem->quantity }}, {{ $ordem->status }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhuma ordem de produção recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="impostos" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosImpostos as $imposto)
                                <li class="list-group-item">{{ $imposto->tax_type }} ({{ number_format($imposto->amount, 2, ',', '.') }} AOA)</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum imposto recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="beneficios" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimosBeneficios as $beneficio)
                                <li class="list-group-item">{{ $beneficio->benefit_type }} ({{ $beneficio->amount ? number_format($beneficio->amount, 2, ',', '.') . ' AOA' : 'Sem valor' }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhum benefício recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="metricas-idh" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimasMetricasIdh as $metrica)
                                <li class="list-group-item">{{ $metrica->metric_name }} ({{ number_format($metrica->value, 2) }}, {{ $metrica->region ?? 'Sem região' }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhuma métrica IDH recente.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="atividades" role="tabpanel">
                        <ul class="mt-3 list-group">
                            @forelse($ultimasAtividades as $atividade)
                                <li class="list-group-item">{{ $atividade->user->name }}: {{ $atividade->accao }} ({{ $atividade->created_at->diffForHumans() }})</li>
                            @empty
                                <li class="text-center list-group-item">Nenhuma atividade recente.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 text-center">
            <p class="mb-0 fs-4">© MK LDA 2025 <a href="#" class="pe-1 text-primary text-decoration-underline">❤️</a></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de Barras
        const barCtx = document.getElementById('barChart');
        if (barCtx) {
            new Chart(barCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ["Clientes", "Produtos", "Fornecedores", "Vendas", "Funcionários", "Projetos", "Financeiros", "Contratos", "Ordens de Produção", "Impostos", "Benefícios", "Métricas IDH", "Atividades"],
                    datasets: [{
                        label: 'Quantidade Total',
                        data: [{{ $data['clientes'] }}, {{ $data['produtos'] }}, {{ $data['fornecedores'] }}, {{ $data['vendas'] }}, {{ $data['funcionarios'] }}, {{ $data['projetos'] }}, {{ $data['financeiros'] }}, {{ $data['contratos'] }}, {{ $data['ordens_producao'] }}, {{ $data['impostos'] }}, {{ $data['beneficios'] }}, {{ $data['idh_metricas'] }}, {{ $data['atividades'] }}],
                        backgroundColor: ["rgba(68, 146, 255, 0.7)", "rgba(40, 167, 69, 0.7)", "rgba(255, 193, 7, 0.7)", "rgba(220, 53, 69, 0.7)", "rgba(23, 162, 184, 0.7)", "rgba(102, 16, 242, 0.7)", "rgba(32, 201, 151, 0.7)", "rgba(253, 126, 20, 0.7)", "rgba(111, 66, 193, 0.7)", "rgba(232, 62, 140, 0.7)", "rgba(0, 123, 255, 0.7)", "rgba(40, 167, 69, 0.7)", "rgba(255, 193, 7, 0.7)"],
                        borderColor: "#121111",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 2,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: true, position: 'right', labels: { font: { size: 12 } } },
                        tooltip: { enabled: true }
                    }
                }
            });
        }

        // Gráfico de Rosca
        const doughnutCtx = document.getElementById('doughnutChart');
        if (doughnutCtx) {
            new Chart(doughnutCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ["Clientes", "Produtos", "Fornecedores", "Vendas", "Funcionários", "Projetos", "Financeiros", "Contratos", "Ordens de Produção", "Impostos", "Benefícios", "Métricas IDH", "Atividades"],
                    datasets: [{
                        label: 'Distribuição',
                        data: [{{ $data['clientes'] }}, {{ $data['produtos'] }}, {{ $data['fornecedores'] }}, {{ $data['vendas'] }}, {{ $data['funcionarios'] }}, {{ $data['projetos'] }}, {{ $data['financeiros'] }}, {{ $data['contratos'] }}, {{ $data['ordens_producao'] }}, {{ $data['impostos'] }}, {{ $data['beneficios'] }}, {{ $data['idh_metricas'] }}, {{ $data['atividades'] }}],
                        backgroundColor: ["rgba(68, 146, 255, 0.7)", "rgba(40, 167, 69, 0.7)", "rgba(255, 193, 7, 0.7)", "rgba(220, 53, 69, 0.7)", "rgba(23, 162, 184, 0.7)", "rgba(102, 16, 242, 0.7)", "rgba(32, 201, 151, 0.7)", "rgba(253, 126, 20, 0.7)", "rgba(111, 66, 193, 0.7)", "rgba(232, 62, 140, 0.7)", "rgba(0, 123, 255, 0.7)", "rgba(40, 167, 69, 0.7)", "rgba(255, 193, 7, 0.7)"],
                        borderColor: "#121111",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 1,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: { enabled: true }
                    }
                }
            });
        }

        // Gráfico de Linha
        const lineCtx = document.getElementById('lineChart');
        if (lineCtx) {
            const lineData = {
                labels: @json(count($meses) > 0 ? $meses : array_map(function($i) {
                    return (new DateTime())->modify("-" . (5 - $i) . " months")->format('Y-m');
                }, range(0, 5))),
                datasets: [{
                    label: 'Valor das Vendas (AOA)',
                    data: @json(count($valoresVendas) > 0 ? $valoresVendas : array_fill(0, 6, 0)),
                    borderColor: "#4492ff",
                    backgroundColor: "rgba(68, 146, 255, 0.2)",
                    fill: true,
                    tension: 0.4
                }]
            };
            new Chart(lineCtx.getContext('2d'), {
                type: 'line',
                data: lineData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 3,
                    scales: {
                        x: { title: { display: true, text: "Meses" } },
                        y: { beginAtZero: true, title: { display: true, text: "Valor (AOA)" } }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    </script>
@endsection
