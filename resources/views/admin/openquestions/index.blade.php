@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.openquestions.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="subject_id" class="form-control">
                        <option value="">Disciplinas</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.openquestions.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Lista de questões abertas</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2" style="width: 50%">Questão</th>
                        <th class="py-2 text-center">Pontuação</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($openquestions as $question)
                        <tr>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $question->course->title }}: <strong>{{ $question->subject->title }}</strong>
                                    <br />
                                    <small>{{ $question->question }}</small>
                                </p>
                            </td>
                            <td class="py-2 text-center">{{ $question->punctuation }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($question->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($question->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.openquestions.edit', ['id' => $question->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.openquestions.destroy', ['id' => $question->id]) }}"
                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                        title="Excluir registro">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="5">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($openquestions)
                <div class="mt-2 mx-2">
                    {{ $openquestions->links('pagination::bootstrap-5') }}
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
