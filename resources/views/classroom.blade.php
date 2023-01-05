@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Sala curso: <label>{{ $course->title }}</label></h1>
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

            <div class="callout callout-danger">
                <h5>Caro aluno(a)!</h5>
                <p>Notamos que você foi reprovado em uma disciplina, para solicita sua recuperação <a href="#">clique
                        aqui</a>.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Disciplinas</h3>
                    <div class="card-tools">
                        <i class="fa fa-sitemap"></i> Estudar
                        <i class="fas fa-bullhorn ml-2"></i> Forum
                        <i class="fas fa-hourglass-half ml-2"></i> Avaliação
                        <i class="fa fa-ban ml-2"></i> Indisponível
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th class="text-center">Semestre</th>
                                <th title="Créditos do estudo." class="text-center">Créditos</th>
                                <th title="Carga horárioa de estudo." class="text-center">CH</th>
                                <th title="Nota das questões multiplas.">1ª NT</th>
                                <th title="Nota das questões abertas.">2ª NT</th>
                                <th title="Nota do envio do arquivo de trabalho.">3º NT</th>
                                <th title="Média avaliativa das notas.">Média</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects->sortBy('semester') as $subject)
                                <tr>
                                    <td>
                                        @if ($subject->status == 'sim')
                                            <i class="fa fa-unlock mr-2"></i>
                                        @else
                                            <i class="fa fa-lock mr-2"></i>
                                        @endif

                                        {{ $subject->title }}
                                    </td>
                                    <td class="text-center">{{ $subject->semester }}º</td>
                                    <td class="text-center">{{ $subject->credits }}</td>
                                    <td class="text-center">{{ $subject->workload }} hs</td>
                                    <td class="text-center">
                                        @php
                                            $nota_1 = App\Http\Controllers\HomeController::getNotaMultiple($subject->course->id, $subject->id, Auth::user()->id);
                                            if ($nota_1 == 0) {
                                                echo '<s>0.0</s>';
                                            } else {
                                                echo number_format($nota_1, 1, '.', '');
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $nota_2 = App\Http\Controllers\HomeController::getNotaOpen($subject->course->id, $subject->id, Auth::user()->id);
                                            if ($nota_2 == 0) {
                                                echo '<s>0.0</s>';
                                            } else {
                                                echo number_format($nota_2, 1, '.', '');
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $nota_3 = App\Http\Controllers\HomeController::getNotaJob($subject->course->id, $subject->id, Auth::user()->id);
                                            if ($nota_3 == 0) {
                                                echo '<s>0.0</s>';
                                            } else {
                                                echo number_format($nota_3, 1, '.', '');
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        @php
                                            if ($nota_1 != 0 && $nota_2 != 0 && $nota_3 != 0) {
                                                $soma_notas = $nota_1 + $nota_2 + $nota_3;
                                                $media = $soma_notas / 3;
                                                if ($media == 0) {
                                                    echo '<s>0.0</s>';
                                                } elseif ($media > 0 && $media <= 6.9) {
                                                    echo '<strong class="text-red">' . number_format($media, 1, '.', '') . '<strong>';
                                                } else {
                                                    echo '<strong class="text-blue">' . number_format($media, 1, '.', '') . '<strong>';
                                                }
                                            } else {
                                                echo '<s>0.0</s>';
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if ($subject->status == 'sim')
                                                <a href="{{ route('classroom.discipline', ['id' => $subject->id]) }}"
                                                    class="btn btn-default btn-sm" title="Modulos de estudo da disciplina.">
                                                    <i class="fa fa-sitemap"></i>
                                                    <span
                                                        style="position: absolute; top: -3px; left: -5px; width: 12px; height: 14px; border-radius: 3px; background-color: #0bb833; color: #FFFFFF; padding: 0; font-size: 9px;">{{ count($subject->modules) }}</span>
                                                </a>
                                                <a href="{{ route('classroom.forum.discipline', ['id' => $subject->id]) }}"
                                                    class="btn btn-default btn-sm" title="Forum de discursões">
                                                    <i class="fas fa-bullhorn"></i>
                                                </a>
                                            @else
                                                <a href="" class="btn btn-default btn-sm"
                                                    title="Disciplina indisponível!">
                                                    <i class="fa fa-ban"></i>
                                                </a>
                                                <a href="" class="btn btn-default btn-sm"
                                                    title="Forum indisponível!">
                                                    <i class="fa fa-ban"></i>
                                                </a>
                                            @endif

                                            @if ($subject->quiz == 'liberado')
                                                <a href="{{ route('classroom.avaluation', ['id' => $subject->id]) }}"
                                                    class="btn btn-default btn-sm"
                                                    title="Teste avaliativo de aprendisagem.">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </a>
                                            @else
                                                <a href="" class="btn btn-default btn-sm"
                                                    title="Avaliação indisponível!">
                                                    <i class="fa fa-ban"></i>
                                                </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-2 text-center" colspan="4">
                                        <span>Nenhum registro cadastrado.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

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
