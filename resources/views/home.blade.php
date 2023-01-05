@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')


    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Messages</span>
                    <span class="info-box-number">1,410</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bookmarks</span>
                    <span class="info-box-number">410</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Uploads</span>
                    <span class="info-box-number">13,648</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">93,139</span>
                </div>

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="row">
                @foreach ($courses as $course)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $course->title }}
                                </h3>
                            </div>

                            <div class="card-body p-0">
                                <div
                                    style="width: 100%; height: 200px; background-image: url('{{ asset('storage/' . $course->photo) }}'); background-position: center; background-repeat: no-repeat; background-size: cover;">
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="d-block">{!! Str::substr($course->description, 0, 150) !!} ...</small>

                                <ul class="nav flex-column">
                                    <li class="nav-item">Disciplinas <span
                                            class="float-right">{{ count($course->subjects) }}</span></li>
                                    <li class="nav-item">Alunos <span
                                            class="float-right">{{ count($course->inscriptions) }}</span></li>
                                    <li class="nav-item">Duração <span class="float-right">{{ $course->duration }}</span>
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between">
                                    @if ($course->status == 'sim')
                                        <a href="{{ route('classroom',['slug' => $course->slug])}}" class="btn btn-success btn-sm mt-3">Acessar</a>
                                        <a href="#" data-toggle="modal" data-target="#modal-lg{{ $course->id }}"
                                            class="btn btn-default btn-sm mt-3">Saber mais</a>
                                    @else
                                        <a href="#" class="btn btn-danger btn-sm mt-3">Indisponível</a>
                                        <a href="#" data-toggle="modal" data-target="#modal-lg{{ $course->id }}"
                                            class="btn btn-default btn-sm mt-3">Saber mais</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach ($courses as $course)
                <div class="modal fade" id="modal-lg{{ $course->id }}" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Curso: {{ $course->title }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {!! $course->description !!}

                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <td class="p-1">Alunos Matriculados</td>
                                            <td class="p-1">{{ count($course->inscriptions) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1">Tempo de duração do estudo</td>
                                            <td class="p-1">{{ $course->duration }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br/>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="p-1">Disciplina</th>
                                            <th class="p-1 text-center">Semestre</th>
                                            <th class="p-1 text-center">Créditos</th>
                                            <th class="p-1 text-center">Carga Horária</th>
                                            <th class="p-1 text-center">Modulos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($course->subjects->sortBy('semester') as $subject)
                                            <tr>
                                                <td class="p-1">{{ $subject->title }}</td>
                                                <td class="p-1 text-center">{{ $subject->semester }}º</td>
                                                <td class="p-1 text-center">{{ $subject->credits }}</td>
                                                <td class="p-1 text-center">{{ $subject->workload }} hr</td>
                                                <td class="p-1 text-center">{{ count($subject->modules) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fecha</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="col-md-3">

            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="timeline">
                                <div class="time-label">
                                    <span class="bg-green">Mural Acadêmico</span>
                                </div>
                                @forelse ($murals as $mural)
                                    <div>
                                        <i class="fas fa-comments bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($mural->date)->format('d/m/Y') }}</span>
                                            <h3 class="timeline-header"><a href="#">Informativo</a></h3>
                                            <div class="timeline-body">
                                                <label>{{ $mural->title }}</label>
                                                <small>{!! $mural->description !!}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                @endforelse


                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
