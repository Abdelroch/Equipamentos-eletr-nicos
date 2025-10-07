@extends('layouts.visitor.main')

@section('title', 'Lista de solicitações de negociações')

@section('content')

     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

        <!-- BREADCRUMB -->
        <div id="breadcrumb" class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="breadcrumb-header">Minhas Solicitações</h3>
                        <ul class="breadcrumb-tree">
                            <li><a href="#">Home</a></li>
                            <li class="active">WishList</li>
                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->

        <!-- SECTION -->
        <div class="section" >
            <!-- container -->
            <div class="container w-100" style="box-shadow: 8px 8px 8px 8px rgb(228, 228, 228);">
                <!-- row -->
                <div class="row" style="padding: 1rem;">

                    <table class="table bordered table-striped table-hover" id="myTable">
                        <thead>
                            <tr>
                                <th>Index</th>
                                <th>Producto</th>
                                <th>Quantidade</th>
                                <th>Preço original</th>
                                <th>Preço proposto</th>
                                <th>Custo de entrega</th>
                                <th>Preço total</th>
                                <th>Local de entrega</th>
                                <th>Método de pagamento</th>
                                <th>Estado do pedido</th>
                                <th>Solicitado em</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_requests as $order_request)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $order_request->product_name }}</td>
                                    <td>{{ $order_request->quantity }}</td>
                                    <td>{{ $order_request->original_price }} AOA</td>
                                    <td>{{ $order_request->proposed_price == 0.00 ? 'N/D' : $order_request->proposed_price . " AOA" }}</td>
                                    <td>{{ $order_request->delivery_cost == 0.00 ? 'N/D' : $order_request->delivery_cost . " AOA" }}</td>
                                    <td>{{ $order_request->total_price }} AOA</td>
                                    <td>{{ $order_request->delivery_location }}</td>
                                    <td>Transferência bancária(IBAN)</td>
                                    <td>
                                        @if ($order_request->status === "pending")
                                                <span style="background: #E4A11B; color: #FBFBFB; padding: 0.2rem;">pendente</span>
                                            @elseif ($order_request->status === "rejected")
                                                <span style="background: #DC4C64; color: #FBFBFB; padding: 0.2rem;">rejeitada</span>
                                            @elseif ($order_request->status === "accepted")
                                                <span style="background: #14A44D; color: #FBFBFB; padding: 0.2rem;">aceitada</span>
                                        @endif
                                    </td>
                                    <td>{{ date('d/m/Y, H:i', strtotime($order_request->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /SECTION -->

          <!-- jQuery + DataTables JS -->
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
          <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

          <script>
              $(document).ready(function() {
                  $('#myTable').DataTable();
              });
          </script>

@endsection
