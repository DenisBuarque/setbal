@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.courses.index') }}">
                <div class="input-group input-group-md">
                    <select name="polo" class="form-control mr-1">
                        <option value="">Polo de ensino</option>
                        @foreach ($polos as $polo)
                            @if ($search_polo == $polo->id)
                                <option value="{{ $polo->id }}" selected>{{ $polo->title }}</option>
                            @else
                                <option value="{{ $polo->id }}">{{ $polo->title }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ $search }}" class="form-control mr-1" placeholder="Titulo, instituição ou ativo"/>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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

    <div class="row">
        <div class="col-lg-3 col-6">

            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $courses->where('type','setbal')->count() }}</h3>
                    <p>Cursos Setbal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $courses->where('type','ead')->count() }}</h3>
                    <p>Cursos EAD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $courses->where('status','sim')->count() }}</h3>
                    <p>Cursos ativo(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $courses->where('status','nao')->count() }}</h3>
                    <p>Cursos inativo(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de cursos</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Título</th>
                        <th class="py-2">Polo de ensino</th>
                        <th class="py-2 text-center">Instituição</th>
                        <th class="py-2">Duração</th>
                        <th class="py-2 text-center">Ativo</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2 text-center" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($course->photo))
                                        <img src="{{ asset('storage/' . $course->photo) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('/images/not-photo.jpg')}}" alt="Photo"
                                            class="img-circle img-size-32 mr-2">
                                    @endif
                                    {{ $course->title }}
                                </div>
                            </td>
                            <td class="py-2">{{ $course->polo->title }}</td>
                            <td class="py-2 text-center">{{ $course->type }}</td>
                            <td class="py-2">{{ $course->duration }}</td>
                            <td class="py-2 text-center">
                                @if ($course->status == 'sim')
                                    Sim
                                @else
                                    Não
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($course->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($course->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.courses.edit', ['id' => $course->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.destroy', ['id' => $course->id]) }}"
                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                        title="Excluir registro">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="8">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($courses)
                <div class="mt-2 mx-2">
                    {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
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
