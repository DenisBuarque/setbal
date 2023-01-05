@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.forums.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="subject" class="form-control mr-1">
                        <option value="">Disciplinas</option>
                        @foreach ($subjects as $subject)
                            @if ($search2 == $subject->id)
                                <option value="{{ $subject->id }}" selected>{{ $subject->title }} ({{ $subject->type }})</option>
                            @else
                                <option value="{{ $subject->id }}">{{ $subject->title }} ({{ $subject->type }})</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" name="title" value="{{ $search }}" id="title" class="form-control mr-1" placeholder="Título" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.forums.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Lista de forum de alunos</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>Título do Forum</th>
                        <th style="width: 30%"> Membros </th>
                        <th>Participantes</th>
                        <th style="width: 20%"></th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($forums as $forum)
                        <tr>
                            <td>
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $forum->title }} <br />
                                    <small>{{ $forum->course->title }}: {{ $forum->subject->title }}</small>
                                </p>
                            </td>
                            <td>
                                <ul class="list-inline">
                                    @foreach ($inscriptions as $inscription) 
                                        @if ($inscription->course_id == $forum->course_id)    
                                            <li class="list-inline-item">
                                                @if (isset($inscription->user->image))
                                                    <img src="{{asset('storage/' . $inscription->user->image)}}" title="{{ $inscription->user->name }}" alt="Avatar" class="table-avatar" style="width: 35px; height: 35px; margin-bottom: 2px;" />
                                                @else
                                                    <img src="/images/not-photo.jpg" title="{{ $inscription->user->name }}" alt="Avatar" class="table-avatar" style="width: 35px; height: 35px; margin-bottom: 2px;"/>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class="project_progress">
                                @php
                                    $total = $inscriptions->where('course_id', $forum->course_id)->count();
                                @endphp
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $total }}"
                                        aria-valuemin="0" aria-valuemax="100" style="width: {{ $total }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $total }}% alunos
                                </small>
                            </td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.forums.show',['id' => $forum->id])}}">
                                    <i class="fas fa-folder"></i>
                                </a>
                                <a class="btn btn-info btn-sm" href="{{route('admin.forums.edit',['id' => $forum->id])}}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="{{route('admin.forums.destroy',['id' => $forum->id])}}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($forums)
                <div class="mt-2 mx-2">
                    {{ $forums->withQueryString()->links('pagination::bootstrap-5') }}
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
