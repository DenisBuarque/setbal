@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.murals.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="course" class="form-control mr-1">
                        <option value="">Disciplina</option>
                        @foreach ($subjects as $subject)
                            @if ($search == $subject->id)
                                <option value="{{ $subject->id }}" selected>{{ $subject->title }} {{ $subject->type }}</option>
                            @else
                                <option value="{{ $subject->id }}">{{ $subject->title }} {{ $subject->type }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" name="title" value="{{ $search2 }}" id="title"
                        class="form-control mr-1" placeholder="Título" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.murals.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Lista de mural de recados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Expira em</th>
                        <th class="py-2">Título</th>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($murals as $mural)
                        <tr>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->date)->format('d/m/Y') }}</td>
                            <td class="py-2">{{ $mural->title }}</td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $mural->subject->title }}
                                    <br /><small>{{ $mural->course->title }}</small>
                                </p>
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->updated_at)->format('d/m/Y H:m:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.murals.edit', ['id' => $mural->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.murals.destroy', ['id' => $mural->id]) }}"
                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                        title="Excluir registro">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="7">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($murals)
                <div class="mt-2 mx-2">
                    {{ $murals->withQueryString()->links('pagination::bootstrap-5') }}
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
