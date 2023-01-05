@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.tickets.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="user" class="form-control mr-1">
                        <option value="">Alunos</option>
                        @foreach ($users as $user)
                            @if ($search == $user->id)
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <select name="status" class="form-control">
                        <option value=""></option>
                        <option value="open" @if ($search2 == 'open') selected @endif>Abertos</option>
                        <option value="pending" @if ($search2 == 'pending') selected @endif>Pendentes</option>
                        <option value="close" @if ($search2 == 'close') selected @endif>Resolvidos</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.tickets.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ count($ticket_total) }}</h3>
                    <p>Total de tickets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ count($ticket_open) }}</h3>
                    <p>Tickets Abertos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullhorn"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ count($ticket_close) }}</h3>
                    <p>Tickets Resolvidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ count($ticket_pending) }}</h3>
                    <p>Tickets pendentes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de tickets de alunos(as)</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Assunto</th>
                        <th class="py-2">Setor</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($ticket->user->image))
                                        <img src="{{ asset('storage/' . $ticket->user->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                                            class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $ticket->user->name }}
                                        <br /><small>{{ $ticket->user->phone . '  ' . $ticket->user->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">{{ $ticket->subject }}</td>
                            <td class="py-2 text-center">
                                @if ($ticket->sector == 'support')
                                    Suporte
                                @elseif($ticket->sector == 'academic')
                                    Acadêmico
                                @elseif($ticket->sector == 'financial')
                                    Financeiro
                                @else
                                    Ouvidoria
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                @if ($ticket->status == 'open')
                                    <span>
                                        <i class="fa fa-bullhorn text-warning" aria-hidden="true" title="Ticket aberto"></i>
                                    </span>
                                @elseif($ticket->status == 'pending')
                                    <span>
                                        <i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" title="Ticket pendente"></i>
                                    </span>
                                @else
                                    <span>
                                        <i class="fa fa-thumbs-up text-success" aria-hidden="true" title="Ticket resolvido"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.tickets.edit', ['id' => $ticket->id]) }}"
                                        class="btn btn-default btn-sm" title="Editar registro">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.tickets.destroy', ['id' => $ticket->id]) }}"
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

            @if ($tickets)
                <div class="mt-2 mx-2">
                    {{ $tickets->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}" />
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
