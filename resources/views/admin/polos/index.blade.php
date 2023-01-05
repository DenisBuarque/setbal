@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            
        </div>
        <a href="{{ route('admin.polos.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-plus mr-1"></i> Adicionar novo
        </a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div id="message" class="alert alert-danger mb-2" role="alert">
            <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de polos de ensino</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Instituição</th>
                        <th class="py-2">Telefone</th>
                        <th class="py-2">e-Mail</th>
                        <th class="py-2">Cidade/UF</th>
                        <th class="py-2 text-center" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($polos as $polo)
                        <tr>
                            <td class="py-2">{{ $polo->title }}</td>
                            <td class="py-2">{{ $polo->phone }}</td>
                            <td class="py-2">{{ $polo->email }}</td>
                            <td class="py-2">{{ $polo->city.'/'.$polo->state }}</td>
                            <td class="py-2">
                                <div class="btn-group">
                                    <a href="{{ route('admin.polos.edit',['id' => $polo->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.polos.destroy',['id' => $polo->id]) }}"
                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                        title="Excluir registro">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="2">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($polos->links())
                <div class="px-2 pt-3">
                    {{ $polos->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm(
                "Deseja mesmo excluir o registro? Os dados serão perdidos e não poderão ser recuperados novamente.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById("message").style.display = "none";
        }, 5000);
    </script>
@stop
