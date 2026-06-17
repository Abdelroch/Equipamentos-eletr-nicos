@extends('layouts.visitor.main')

@section('title', 'Lista de solicitações de negociações')

@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <style>
        .status-badge { display:inline-block; padding:.35rem .7rem; border-radius:4px;
                         font-size:.78rem; font-weight:600; color:#fff; }
        .status-pending               { background:#E4A11B; }
        .status-rejected               { background:#DC4C64; }
        .status-accepted               { background:#14A44D; }
        .status-awaiting_confirmation  { background:#2E86DE; }
        .status-confirmed              { background:#0F9D58; }
        .rejected-reason { font-size:.78rem; color:#DC4C64; display:block; margin-top:4px; }
    </style>

    <div id="breadcrumb" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">Minhas Solicitações</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li class="active">Minhas Solicitações</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="container"><div class="mt-3 alert alert-success">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="container"><div class="mt-3 alert alert-danger">{{ session('error') }}</div></div>
    @endif

    <div class="section">
        <div class="container w-100" style="box-shadow: 8px 8px 8px 8px rgb(228, 228, 228);">
            <div class="row" style="padding: 1rem;">

                <table class="table bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Preço Total</th>
                            <th>Entrega</th>
                            <th>Pagamento</th>
                            <th>Estado</th>
                            <th>Solicitado em</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_requests as $order_request)
                            <tr>
                                <td>
                                    {{ $order_request->product_name }}
                                    <br>
                                    <small class="text-muted">
                                        Original: {{ number_format($order_request->original_price, 2, ',', '.') }} AOA
                                        @if($order_request->proposed_price != $order_request->original_price)
                                            · Proposto: {{ number_format($order_request->proposed_price, 2, ',', '.') }} AOA
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $order_request->quantity }}</td>
                                <td>
                                    <strong>{{ number_format($order_request->total_price, 2, ',', '.') }} AOA</strong>
                                    <br>
                                    <small class="text-muted">+ {{ number_format($order_request->delivery_cost, 2, ',', '.') }} AOA entrega</small>
                                </td>
                                <td>{{ $order_request->delivery_location }}</td>
                                <td>{{ $order_request->payment_method ?? 'Não definido' }}</td>
                                <td>
                                    @php
                                        $labels = [
                                            'pending'               => 'pendente',
                                            'rejected'              => 'rejeitada',
                                            'accepted'              => 'aceite — falta comprovativo',
                                            'awaiting_confirmation' => 'comprovativo enviado',
                                            'confirmed'             => 'confirmada',
                                        ];
                                    @endphp
                                    <span class="status-badge status-{{ $order_request->status }}">
                                        {{ $labels[$order_request->status] ?? $order_request->status }}
                                    </span>
                                    @if($order_request->status === 'rejected' && $order_request->admin_notes)
                                        <span class="rejected-reason">Motivo: {{ $order_request->admin_notes }}</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y, H:i', strtotime($order_request->created_at)) }}</td>
                                <td style="min-width: 220px;">
                                    @if ($order_request->status === 'accepted')
                                        <form method="POST" action="{{ route('customer.submit_payment_proof', $order_request->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required style="font-size: 0.8rem;">
                                            <button type="submit" class="mt-1 btn btn-sm btn-success">Enviar comprovativo</button>
                                        </form>
                                    @elseif ($order_request->status === 'pending')
                                        <small class="text-muted">Aguarda aprovação do preço</small>
                                    @elseif ($order_request->status === 'awaiting_confirmation')
                                        <small class="text-muted">Aguarda confirmação do admin</small>
                                    @elseif ($order_request->status === 'confirmed')
                                        <small class="text-success"><i class="fa fa-check-circle"></i> Concluído</small>
                                    @elseif ($order_request->status === 'rejected')
                                        <small class="text-muted">Sem acção disponível</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

@endsection
