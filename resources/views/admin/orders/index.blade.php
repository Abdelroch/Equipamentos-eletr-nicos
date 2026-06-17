@extends('layouts.admin.body')

@section('content')
<div class="py-4 container-fluid">
    <h4 class="mb-3">Encomendas e Negociações</h4>

    <form method="GET" class="mb-3">
        <select name="status" class="form-select" style="width:280px;display:inline-block" onchange="this.form.submit()">
            <option value="">Todos os estados</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pendente</option>
            <option value="accepted" {{ request('status')=='accepted'?'selected':'' }}>Aceite — falta pagamento</option>
            <option value="awaiting_confirmation" {{ request('status')=='awaiting_confirmation'?'selected':'' }}>Aguarda confirmação</option>
            <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmada</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejeitada</option>
        </select>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="myTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Produto</th>
                <th>Original</th>
                <th>Proposto</th>
                <th>Total</th>
                <th>Entrega</th>
                <th>Estado</th>
                <th>Comprovativo</th>
                <th>Acções</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer_name }}<br><small class="text-muted">{{ $order->customer_email }}</small></td>
                <td>{{ $order->product_name }}</td>
                <td>KZ {{ number_format($order->product_original_price, 2, ',', '.') }}</td>
                <td>KZ {{ number_format($order->proposed_price, 2, ',', '.') }}</td>
                <td>KZ {{ number_format($order->total_price, 2, ',', '.') }}</td>
                <td>
                    {{ $order->delivery_address }}@if($order->delivery_bairro), {{ $order->delivery_bairro }}@endif
                    <br><small>{{ $order->delivery_location }}</small>
                </td>
                <td>
                    @php
                        $badges = [
                            'pending' => 'bg-warning', 'accepted' => 'bg-info',
                            'awaiting_confirmation' => 'bg-primary', 'confirmed' => 'bg-success',
                            'rejected' => 'bg-danger',
                        ];
                    @endphp
                    <span class="badge {{ $badges[$order->status] ?? 'bg-secondary' }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>
                <td>
                    @if($order->payment_proof)
                        <a href="{{ route('admin.orders.proof', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-file"></i> Ver
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($order->status === 'awaiting_confirmation')
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#aprovar{{ $order->id }}"><i class="fa fa-check"></i></button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejeitar{{ $order->id }}"><i class="fa fa-times"></i></button>
                    @endif
                </td>
            </tr>

            <div class="modal fade" id="aprovar{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-header"><h5 class="modal-title">Aprovar Encomenda #{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body"><textarea name="admin_notes" class="form-control" placeholder="Notas (opcional)"></textarea></div>
                        <div class="modal-footer"><button type="submit" class="btn btn-success">Confirmar Aprovação</button></div>
                    </form>
                </div></div>
            </div>

            <div class="modal fade" id="rejeitar{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-header"><h5 class="modal-title">Rejeitar Encomenda #{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body"><textarea name="admin_notes" class="form-control" placeholder="Motivo da rejeição*" required></textarea></div>
                        <div class="modal-footer"><button type="submit" class="btn btn-danger">Confirmar Rejeição</button></div>
                    </form>
                </div></div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
