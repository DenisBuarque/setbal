@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Informações sobre o estudante:</h3>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-list mr-1"></i> LIstar registros
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
                    <h3>0</h3>
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
                    <h3>0</h3>
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
                    <h3>0</h3>
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
                    <h3>0</h3>
                    <p>Alunos inativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-9">

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
                                <th class="py-2">Ensino</th>
                                <th class="py-2">Polo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2"></td>
                                <td class="py-2"></td>
                                <td class="py-2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">


        
        


            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if (isset($student->photo))
                            <img class="profile-user-img img-fluid img-circle" src="{{asset('storage/' . $student->photo) }}"
                                            alt="User profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="https://dummyimage.com/28x28/b6b7ba/fff"
                                            alt="User profile picture">
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $student->name }}</h3>
                    <p class="text-muted text-center">{{ $student->local }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Telefone</b> <a class="float-right">{{ $student->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Matricula</b> <a class="float-right">{{ $student->registration }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Cidade/UF</b> <a class="float-right">{{ $student->city . '/' . $student->state }}</a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.students.edit',['id' => $student->id ])}}" class="btn btn-primary btn-block"><b>Detalhes</b></a>
                </div>

            </div>

        </div>
    </div>



@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop

@section('js')
    <script></script>
@stop
