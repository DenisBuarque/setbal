@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.subjects.index') }}">
                <div class="input-group input-group-md">
                    <select name="course" class="form-control mr-1">
                        <option value="">Curso</option>
                        @foreach ($courses as $course)
                            @if ($search2 == $course->id)
                                <option value="{{ $course->id }}" selected>{{ $course->title }}</option>
                            @else
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ $search }}" id="search" class="form-control mr-1" placeholder="Titulo, instit, ativo, avaliação" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $disciplines->count() }}</h3>
                    <p>Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $active->count() }}</h3>
                    <p>Disciplina(s) Liberada(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $quiz->count() }}</h3>
                    <p>Avaliações liberadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $inative->count() }}</h3>
                    <p>Disciplina(s) Indisponível</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de disciplinas</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2 text-center">Ano</th>
                        <th class="py-2 text-center">Semestre</th>
                        <th class="py-2">Curso</th>
                        <th class="py-2 text-center">Instituição</th>
                        <th class="py-2 text-center">Avaliação</th>
                        <th class="py-2 text-center">Ativo</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td class="py-2">{{ $subject->title }}</td>
                            <td class="py-2 text-center">{{ $subject->year }}º ano</td>
                            <td class="py-2 text-center">{{ $subject->semester }}º Semestre</td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $subject->course->title }}
                                    <br /><small>{{$subject->course->polo->title}}</small>
                                </p>
                            </td>
                            <td class="py-2 text-center">{{ $subject->type }}</td>
                            <td class="py-2 text-center">
                                @if ($subject->quiz == 'liberado')
                                    Liberado
                                @else
                                    Bloqueado
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                @if ($subject->status == 'sim')
                                    Sim
                                @else
                                    Não
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.modules.index', ['id' => $subject->id]) }}" class="btn btn-default btn-sm" title="Modulos da disciplina">
                                        <i class="fa fa-sitemap"></i>
                                        <span style="position: absolute; top: -3px; left: -5px; width: 12px; height: 14px; border-radius: 3px; background-color: #FF0000; color: #FFFFFF; padding: 0; font-size: 9px;">{{count($subject->modules)}}</span>
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', ['id' => $subject->id]) }}" class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.subjects.destroy', ['id' => $subject->id]) }}" onclick="return confirmaExcluir()" class="btn btn-default btn-sm" title="Excluir registro">
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

            @if ($subjects)
                <div class="mt-2 mx-2">
                    {{ $subjects->withQueryString()->links('pagination::bootstrap-5') }}
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
