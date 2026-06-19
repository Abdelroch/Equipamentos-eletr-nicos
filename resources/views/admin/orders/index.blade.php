@extends('layouts.admin.body')

@section('title', 'Encomendas & Negociações')

@section('conteudo')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-shopping-bag"></i> Encomendas & Negociações</h4>

                    <div class="btn-group">
                        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
                           class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">Todas</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                           class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pendentes</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'awaiting_confirmation']) }}"
                           class="btn btn-sm {{ request('status') === 'awaiting_confirmation' ? 'btn-primary' : 'btn-outline-primary' }}">Com Comprovativo</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}"
                           class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-success' : 'btn-outline-success' }}">Confirmadas</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}"
                           class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejeitadas</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="m-3 alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="m-3 alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="p-0 card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Produto</th>
                                    <th>Preço Original</th>
                                    <th>Preço Proposto</th>
                                    <th>Entrega</th>
                                    <th>Total</th>
                                    <th>Endereço</th>
                                    <th>Comprovativo</th>
                                    <th>Estado</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong><br>
                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                    </td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ number_format($order->original_price, 2, ',', '.') }} KZ</td>
                                    <td>
                                        @if($order->proposed_price == $order->original_price)
                                            <span class="text-muted">Preço fixo</span>
                                        @else
                                            <strong class="text-warning">{{ number_format($order->proposed_price, 2, ',', '.') }} KZ</strong>
                                        @endif
                                    </td>
                                    <td>{{ number_format($order->delivery_cost ?? 0, 2, ',', '.') }} KZ</td>
                                    <td><strong>{{ number_format($order->total_price, 2, ',', '.') }} KZ</strong></td>
                                    <td style="min-width:160px; font-size:0.82rem;">
                                        @if($order->delivery_address)
                                            {{ $order->delivery_address }}, {{ $order->delivery_bairro ?? '' }}
                                        @else
                                            <span class="text-muted">Não informado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment_proof)
                                            <a href="{{ route('admin.orders.proof', $order->id) }}" target="_blank"
                                               class="btn btn-xs btn-info btn-sm">
                                                <i class="fa fa-file"></i> Ver
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'pending'              => ['warning', 'Pendente'],
                                                'awaiting_confirmation'=> ['primary', 'Aguarda Confirmação'],
                                                'confirmed'            => ['success', 'Confirmada'],
                                                'rejected'             => ['danger', 'Rejeitada'],
                                                'cancelled'            => ['secondary', 'Cancelada'],
                                                'expired'              => ['dark', 'Expirada'],
                                            ];
                                            $badge = $statusBadge[$order->status] ?? ['secondary', ucfirst($order->status)];
                                        @endphp
                                        <span class="badge badge-{{ $badge[0] }}">{{ $badge[1] }}</span>
                                    </td>
                                    <td><small>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</small></td>

                                    <td style="min-width: 240px;">
                                        @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('admin.orders.approve', $order->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-handshake-o"></i> Aceitar Proposta
                                                </button>
                                            </form>

                                        @elseif($order->status === 'awaiting_confirmation')
                                            <div class="gap-2 d-flex flex-column">
                                                <!-- Confirmar -->
                                                <form method="POST" action="{{ route('admin.orders.confirm', $order->id) }}">
                                                    @csrf
                                                    <input type="text" name="admin_notes" class="mb-1 form-control form-control-sm"
                                                           placeholder="Nota (opcional)">
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fa fa-check"></i> Confirmar Encomenda
                                                    </button>
                                                </form>

                                                <!-- Rejeitar -->
                                                <form method="POST" action="{{ route('admin.orders.reject', $order->id) }}">
                                                    @csrf
                                                    <input type="text" name="admin_notes" class="mb-1 form-control form-control-sm"
                                                           placeholder="Motivo da rejeição" required>
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                                        <i class="fa fa-times"></i> Rejeitar
                                                    </button>
                                                </form>
                                            </div>

                                        @elseif(in_array($order->status, ['confirmed', 'rejected', 'cancelled', 'expired']))
                                            <span class="text-muted small">Sem ações disponíveis</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="py-4 text-center text-muted">
                                        Nenhuma encomenda encontrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
