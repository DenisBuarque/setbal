@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.students.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    
                    <select name="local" class="form-control mr-1">
                        <option value="">Área</option>
                        <option value="setbal" @if ($search2 == 'setbal') selected @endif>Setbal</option>
                        <option value="ead" @if ($search2 == 'ead') selected @endif>EAD</option>
                    </select>
                    <input type="text" name="search" value="{{ $search }}" id="search" class="form-control mr-1" placeholder="Nome ou cidade" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $total_users }}</h3>
                    <p>Total de alunos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $student_setbal }}</h3>
                    <p>Alunos Setbal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $student_ead }}</h3>
                    <p>Alunos EAD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $student_active }}</h3>
                    <p>Alunos inativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Alunos cadastrados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Celular</th>
                        <th class="py-2">E-mail</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($student->image))
                                        <img src="{{asset('storage/' . $student->image) }}" alt="Photo" style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo" class="img-circle img-size-32 mr-2">
                                    @endif
                                    
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $student->name }}
                                        <br /><small>{{ $student->local }} - {{ $student->polo->title }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">{{ $student->phone }}</td>
                            <td class="py-2">{{ $student->email }}</td>
                            <td class="py-2 text-center">{{ $student->active }}</td>
                            <td class="py-2">{{\Carbon\Carbon::parse($student->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{\Carbon\Carbon::parse($student->updated_at)->format('d/m/Y H:m:s') }}</td>
                            <td>
                                <div class="btn-group">   
                                    <a href="{{ route('admin.students.show',['id' => $student->id]) }}"
                                        class="btn btn-default btn-sm" title="Informações sobre o estudante">
                                        <i class="fa fa-graduation-cap"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', ['id' => $student->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.students.destroy', ['id' => $student->id]) }}"
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

            @if ($students)
                <div class="mt-2 mx-2">
                    {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
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
