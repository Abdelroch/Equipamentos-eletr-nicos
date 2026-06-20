@extends('layouts.visitor.main')

@section('title', 'Minha conta')

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
                    <h3 class="breadcrumb-header">Minha conta</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="#">Home</a></li>
                        <li class="">Configurações</li>
                        <li class="active">Meu Perfil</li>
                    </ul>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container w-100">
            <!-- row -->
            <div class="row" style="padding: 1rem;">

                <style>
                    .profile-picture {
                        width: 100%;
                        height: 80px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .avatar-initial {
                        width: 80px;
                        height: 80px;
                        background-color: #fa0e36f3;
                        color: white;
                        font-size: 18px;
                        font-weight: bold;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        text-transform: uppercase;
                        border-radius: 100%;
                    }

                    .card-header {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                </style>

                <div class="my-3 my-md-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card card-profile">
                                    <div class="card-header">
                                        <div class="avatar-initial rounded-pill">
                                            {{ strtoupper(substr($userAuthed->name, 0, 4)) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                    <div class="card-body text-cente">

                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Nome completo</label>
                                                    <input type="text" class="form-control" placeholder="Username"
                                                        value="{{ $userAuthed->name }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">NIF</label>
                                                    <input type="text" class="form-control" placeholder="nif"
                                                        value="{{ $userAuthed->nif }}" disabled>
                                                </div>
                                            </div>


                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Data de nascimento</label>
                                                    <input type="date" class="form-control" placeholder="birth_date"
                                                        value="{{ $userAuthed->birth_date }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Gênero</label>
                                                    <select name="gender" id="" disabled class="form-control">
                                                        <option value="N/D" selected disabled>N/D</option>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <form class="card" action="{{ route('customer.update_account') }}"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <h3 class="card-title">Meus dados</h3>
                                        <hr>
                                        <div class="row">

                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Endereço email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="{{ $userAuthed->email }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Contacto</label>
                                                    <input type="text" class="form-control" name="phone_number"
                                                        placeholder="+244 xxx xxx xxx"
                                                        value="{{ $userAuthed->phone_number }}">
                                                </div>
                                            </div>

                                            {{--    <div class="col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Foto de perfil</label>
                                                        <input type="file" class="form-control" name="profile_picture">
                                                    </div>
                                                </div> --}}

                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Senha</label>
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="Senha antiga">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Nova senha</label>
                                                    <input type="password" class="form-control" name="new_password"
                                                        placeholder="Crie uma senha nova">
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Confirme a nova senha</label>
                                                    <input type="password" class="form-control"
                                                        name="new_password_confirmation" placeholder="Confirmar senha">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right card-footer">
                                            <button type="submit" class="btn btn-primary">Actualizar dados</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
