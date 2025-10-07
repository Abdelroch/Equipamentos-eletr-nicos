@extends('layouts.admin.body')

@section('title', 'Relatório de Produtos')

@section('conteudo')
    <div class="container-fluid">
        <!-- Título da Página -->
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3><strong>Relatório de Produtos</strong></h3>
            </div>
        </div>

        <!-- Seção de Filtros -->
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="shadow card">
                    <div class="card-body">
                        <form action="{{ route('admin.reports.products') }}" method="GET">
                            <div class="row mb-4">
                                <!-- Campo de Pesquisa -->
                                <div class="col-md-3 mb-3">
                                    <label for="search" class="form-label">Pesquisar (Nome/Descrição/Status):</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $filters['search'] ?? '' }}" placeholder="Digite para pesquisar...">
                                </div>

                                <!-- Filtro de Status -->
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Status:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Bom" {{ $filters['status'] == 'Bom' ? 'selected' : '' }}>Bom</option>
                                        <option value="Avariado" {{ $filters['status'] == 'Avariado' ? 'selected' : '' }}>Avariado</option>
                                        <option value="Em Análise" {{ $filters['status'] == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                        <option value="Reparado" {{ $filters['status'] == 'Reparado' ? 'selected' : '' }}>Reparado</option>
                                        <option value="Irreparável" {{ $filters['status'] == 'Irreparável' ? 'selected' : '' }}>Irreparável</option>
                                    </select>
                                </div>

                                <!-- Filtro de Categoria -->
                                <div class="col-md-3 mb-3">
                                    <label for="category" class="form-label">Categoria:</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Todas</option>
                                        <option value="smartphones" {{ $filters['category'] == 'smartphones' ? 'selected' : '' }}>Smartphones</option>
                                        <option value="laptops" {{ $filters['category'] == 'laptops' ? 'selected' : '' }}>Laptops</option>
                                        <option value="desktops" {{ $filters['category'] == 'desktops' ? 'selected' : '' }}>Computadores Desktop</option>
                                        <option value="tablets" {{ $filters['category'] == 'tablets' ? 'selected' : '' }}>Tablets</option>
                                        <option value="smartwatches" {{ $filters['category'] == 'smartwatches' ? 'selected' : '' }}>Smartwatches</option>
                                        <option value="headphones" {{ $filters['category'] == 'headphones' ? 'selected' : '' }}>Fones de Ouvido</option>
                                        <option value="speakers" {{ $filters['category'] == 'speakers' ? 'selected' : '' }}>Caixas de Som</option>
                                        <option value="gaming_consoles" {{ $filters['category'] == 'gaming_consoles' ? 'selected' : '' }}>Consoles de Jogos</option>
                                        <option value="monitors" {{ $filters['category'] == 'monitors' ? 'selected' : '' }}>Monitores</option>
                                        <option value="keyboards" {{ $filters['category'] == 'keyboards' ? 'selected' : '' }}>Teclados</option>
                                        <option value="mice" {{ $filters['category'] == 'mice' ? 'selected' : '' }}>Mouses</option>
                                        <option value="printers" {{ $filters['category'] == 'printers' ? 'selected' : '' }}>Impressoras</option>
                                        <option value="routers" {{ $filters['category'] == 'routers' ? 'selected' : '' }}>Roteadores</option>
                                        <option value="cameras" {{ $filters['category'] == 'cameras' ? 'selected' : '' }}>Câmeras</option>
                                        <option value="drones" {{ $filters['category'] == 'drones' ? 'selected' : '' }}>Drones</option>
                                        <option value="accessories" {{ $filters['category'] == 'accessories' ? 'selected' : '' }}>Acessórios</option>
                                    </select>
                                </div>

                                <!-- Filtros de Data -->
                                <div class="col-md-3 mb-3">
                                    <label for="date_start" class="form-label">Data Início:</label>
                                    <input type="date" name="date_start" id="date_start" class="form-control"
                                        value="{{ $filters['date_start'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="date_end" class="form-label">Data Fim:</label>
                                    <input type="date" name="date_end" id="date_end" class="form-control"
                                        value="{{ $filters['date_end'] ?? '' }}">
                                </div>

                                <!-- Botão de Filtrar -->
                                <div class="col-md-3 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </div>

                            <!-- Seção de Exportação -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Exportar Relatório</h5>
                                    <div class="row">
                                        <!-- Exportar PDF -->
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.bom', ['export' => 'pdf']) }}"
                                                class="btn btn-success w-100">PDF (Bom)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.avariado', ['export' => 'pdf']) }}"
                                                class="btn btn-success w-100">PDF (Avariado)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.em-analise', ['export' => 'pdf']) }}"
                                                class="btn btn-success w-100">PDF (Em Análise)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.reparado', ['export' => 'pdf']) }}"
                                                class="btn btn-success w-100">PDF (Reparado)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.irreparavel', ['export' => 'pdf']) }}"
                                                class="btn btn-success w-100">PDF (Irreparável)</a>
                                        </div>

                                        <!-- Exportar Excel -->
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.bom', ['export' => 'excel']) }}"
                                                class="btn btn-info w-100">Excel (Bom)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.avariado', ['export' => 'excel']) }}"
                                                class="btn btn-info w-100">Excel (Avariado)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.em-analise', ['export' => 'excel']) }}"
                                                class="btn btn-info w-100">Excel (Em Análise)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.reparado', ['export' => 'excel']) }}"
                                                class="btn btn-info w-100">Excel (Reparado)</a>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <a href="{{ route('admin.reports.products.irreparavel', ['export' => 'excel']) }}"
                                                class="btn btn-info w-100">Excel (Irreparável)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Resultados -->
        <div class="row justify-content-center mt-4">
            <div class="col-12">
                <div class="shadow card">
                    <div class="card-body">
                        <table class="table datatables table-hover" id="myTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Quantidade Disponível</th>
                                    <th>Categoria</th>
                                    <th>Fornecedor</th>
                                    <th>Data de Criação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['produtos'] as $produto)
                                    <tr>
                                        <td>{{ $produto->id }}</td>
                                        <td>{{ $produto->nome }}</td>
                                        <td>{{ $produto->descricao }}</td>
                                        <td>
                                            <span
                                                class="badge
                                            {{ Str::slug($produto->status, '-') == 'bom'
                                                ? 'bg-success'
                                                : (Str::slug($produto->status, '-') == 'avariado'
                                                    ? 'bg-danger'
                                                    : (Str::slug($produto->status, '-') == 'em-análise'
                                                        ? 'bg-warning text-dark'
                                                        : (Str::slug($produto->status, '-') == 'reparado'
                                                            ? 'bg-info'
                                                            : (Str::slug($produto->status, '-') == 'irreparável'
                                                                ? 'bg-secondary'
                                                                : 'bg-light text-dark')))) }}
                                            p-2">
                                                {{ $produto->status ?? 'Sem Status' }}
                                            </span>
                                        </td>
                                        <td>{{ $produto->quantidade_disponivel }}</td>
                                        <td>{{ $produto->categoria }}</td>
                                        <td>{{ $produto->nome_fornecedor }}</td>
                                        <td>{{ date('d/m/Y', strtotime($produto->created_at)) }}</td>
                                    </tr>
                                @endforeach
                                @if ($data['produtos']->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center text-warning"><b>Nenhum produto encontrado!</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
