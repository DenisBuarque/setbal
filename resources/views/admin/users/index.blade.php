@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{route('admin.users.index')}}">
                <div class="input-group input-group-md">
                    <input type="text" name="search" class="form-control" placeholder="Nome">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{route('admin.user.create')}}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Lista de usuários do sistema</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Nome</th>
                        <th class="py-2">E-mail</th>
                        <th class="py-2 text-center">Contato</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($user->image))
                                        <img src="{{asset('storage/' . $user->image) }}" alt="Photo" style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo" class="img-circle img-size-32 mr-2">
                                    @endif
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="py-2">{{$user->email}}</td>
                            <td class="py-2 text-center">{{$user->phone}}</td>
                            <td class="py-2">{{\Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{\Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:m:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.user.edit',['id' => $user->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.user.destroy',['id' => $user->id]) }}"
                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                        title="Excluir registro">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="6">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($users->appends(array('search' => 'name'))->links())
                <div class="px-2 pt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset("/css/admin_custom.css")}}">
@stop

@section('js')
<script>
    function confirmaExcluir() {
        var conf = confirm("Deseja mesmo excluir o registro? Os dados serão perdidos e não poderão ser recuperados novamente.");
        if (conf) {
            return true;
        } else {
            return false;
        }
    }

    setTimeout(() => {
        document.getElementById("message").style.display = "none";
    }, 6000);
</script>
@stop
