@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.teachers.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="course" class="form-control mr-1">
                        <option value="">Curso</option>
                        @foreach ($courses as $course)
                            @if ($search1 == $course->id)
                                <option value="{{ $course->id }}" selected>{{ $course->title }} ({{ $course->type }})</option>
                            @else
                                <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->type }})</option>
                            @endif
                        @endforeach
                    </select>

                    <input type="text" name="search" value="{{ $search2 ?? ''}}" id="search" class="form-control mr-1" placeholder="Nome, instituição ou status" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $teachers->where('local','setbal')->count() }}</h3>
                    <p>Professores Setbal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $teachers->where('local','ead')->count() }}</h3>
                    <p>Professores EAD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $teachers->where('active','ativo')->count() }}</h3>
                    <p>Professores ativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $teachers->where('active','inativo')->count() }}</h3>
                    <p>Professores inativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de professores cadastrados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Nome</th>
                        <th class="py-2">Telefone</th>
                        <th class="py-2">e-Mail</th>
                        <th class="py-2 text-center">Instituição</th>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2">Status</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($teacher->image))
                                        <img src="{{asset('storage/' . $teacher->image) }}" alt="Photo" style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo" class="img-circle img-size-32 mr-2">
                                    @endif
                                    {{ $teacher->name }}
                                </div>
                            </td>
                            <td class="py-2">{{ $teacher->phone }}</td>
                            <td class="py-2">{{ $teacher->email }}</td>
                            <td class="py-2 text-center">{{ $teacher->local }}</td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $teacher->subject->title }} <br/>
                                    <small>{{ $teacher->course->title }}</small>
                                </p>
                            </td>
                            <td class="py-2">{{ $teacher->active }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.teachers.edit', ['id' => $teacher->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.teachers.destroy', ['id' => $teacher->id]) }}"
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

            @if ($teachers)
                <div class="mt-2 mx-2">
                    {{ $teachers->withQueryString()->links('pagination::bootstrap-5') }}
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
