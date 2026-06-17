@extends('layouts.visitor.main')

@section('title', 'Minha Conta')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="p-3 mb-3 card">
                <h5>Dados Pessoais</h5>
                <p class="mb-1"><strong>Nome:</strong> {{ $userAuthed->user_name }}</p>
                <p class="mb-0"><strong>Email:</strong> {{ $userAuthed->email }}</p>
            </div>

            <div class="p-3 card">
                <h5>Endereço de Entrega e Pagamento</h5>

                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <form method="POST" action="{{ route('customer.update_profile') }}">
                    @csrf
                    <div class="mb-2">
                        <label>Telefone</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ $userAuthed->phone_number }}">
                    </div>
                    <div class="mb-2">
                        <label>Endereço</label>
                        <input type="text" name="address" class="form-control" value="{{ $userAuthed->address }}" placeholder="Rua, número">
                    </div>
                    <div class="mb-2">
                        <label>Bairro</label>
                        <input type="text" name="bairro" class="form-control" value="{{ $userAuthed->bairro }}">
                    </div>
                    <div class="mb-2">
                        <label>Província</label>
                        <select name="province" class="form-control">
                            @foreach(['Luanda','Benguela','Huambo','Huíla','Cabinda','Bié','Bengo','Cuanza Norte','Cuanza Sul','Cunene','Lunda Norte','Lunda Sul','Malanje','Moxico','Namibe','Uíge','Zaire','Cuando Cubango'] as $prov)
                                <option value="{{ $prov }}" {{ $userAuthed->province == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Ponto de Referência</label>
                        <input type="text" name="reference_point" class="form-control" value="{{ $userAuthed->reference_point }}">
                    </div>
                    <div class="mb-3">
                        <label>Método de Pagamento Preferido</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Selecione</option>
                            @foreach(['BAI','BFA','Multicaixa Express','TPA','Numerário'] as $pm)
                                <option value="{{ $pm }}" {{ $userAuthed->payment_method == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Alterações</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <h5>Minhas Encomendas</h5>
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            @forelse($orders as $order)
            @php
                $labels = [
                    'pending' => 'Aguarda Resposta', 'accepted' => 'Aceite — falta pagamento',
                    'awaiting_confirmation' => 'Comprovativo Enviado', 'confirmed' => 'Confirmada',
                    'rejected' => 'Rejeitada',
                ];
                $badges = [
                    'pending' => 'bg-warning', 'accepted' => 'bg-info',
                    'awaiting_confirmation' => 'bg-primary', 'confirmed' => 'bg-success',
                    'rejected' => 'bg-danger',
                ];
            @endphp
            <div class="p-3 mb-3 card">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $order->product_name }}</strong><br>
                        <small>Pedido #{{ $order->id }} — {{ $order->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge {{ $badges[$order->status] ?? 'bg-secondary' }} align-self-start">
                        {{ $labels[$order->status] ?? $order->status }}
                    </span>
                </div>

                <p class="mt-2 mb-1">Total a pagar: <strong>KZ {{ number_format($order->total_price, 2, ',', '.') }}</strong></p>

                @if($order->status === 'rejected' && $order->admin_notes)
                    <div class="mt-2 mb-2 alert alert-danger">Motivo: {{ $order->admin_notes }}</div>
                @endif

                @if(in_array($order->status, ['pending', 'accepted']))
                    <form method="POST" action="{{ route('customer.submit_payment_proof', $order->id) }}" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        <label class="form-label">Submeter comprovativo de pagamento (jpg, png ou pdf, até 5MB)</label>
                        <div class="gap-2 d-flex">
                            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            <button type="submit" class="btn btn-success">Enviar</button>
                        </div>
                    </form>
                @elseif($order->status === 'awaiting_confirmation')
                    <p class="mb-0 text-muted"><i class="fa fa-clock-o"></i> Enviado em {{ \Carbon\Carbon::parse($order->proof_submitted_at)->format('d/m/Y H:i') }}. Aguarda confirmação.</p>
                @endif
            </div>
            @empty
                <p class="text-muted">Ainda não tens encomendas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
