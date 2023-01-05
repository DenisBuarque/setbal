@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.inscriptions.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="subject" class="form-control mr-1">
                        <option value="">Disciplinas</option>
                        @foreach ($subjects as $subject)
                            @if ($search == $subject->id)
                                <option value="{{ $subject->id }}" selected>{{ $subject->title }} ({{ $subject->type }})</option>
                            @else
                                <option value="{{ $subject->id }}">{{ $subject->title }}  ({{ $subject->type }})</option>
                            @endif
                        @endforeach
                    </select>
                    <select name="status" class="form-control mr-1">
                        <option value="">Status</option>
                        <option value="pendente" @if ($search2 == 'pendente') selected @endif>Pendente</option>
                        <option value="pago" @if ($search2 == 'pago') selected @endif>Pago</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.inscriptions.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $inscription_total }}</h3>
                    <p>Total de inscrições</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $inscription_payment }}</h3>
                    <p>Inscrições confirmadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $inscription_pendent }}</h3>
                    <p>Inscrições pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $subjects->count() }}</h3>
                    <p>Disciplinas disponiveis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de inscrições de alunos(as)</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2">Inscrição</th>
                        <th class="py-2">Encerramento</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inscriptions as $inscription)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($inscription->user->image))
                                        <img src="{{ asset('storage/' . $inscription->user->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                            class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $inscription->user->name }}
                                        <br /><small>{{ $inscription->user->phone . '  ' . $inscription->user->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $inscription->subject->title }}
                                    <br /><small>{{ $inscription->course->title }}</small>
                                </p>
                            </td>
                            <td class="py-2 text-center">
                                @if ($inscription->status == 'pendente')
                                    Pendente
                                @else
                                    Pago
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($inscription->closing_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($inscription->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($inscription->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.inscriptions.edit', ['id' => $inscription->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.inscriptions.destroy', ['id' => $inscription->id]) }}"
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

            @if ($inscriptions)
                <div class="mt-2 mx-2">
                    {{ $inscriptions->withQueryString()->links('pagination::bootstrap-5') }}
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
