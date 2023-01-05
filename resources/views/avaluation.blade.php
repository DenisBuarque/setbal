@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Teste Avaliativo {{ $subject->course->title }} / <label>{{ $subject->title }}</label></h1>
        <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}"
            class="btn btn-md btn-info  d-flex align-items-center justify-content-center" title="Lista disciplinas">
            <i class="fa fa-table mr-1"></i> Listar disciplinas
        </a>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Questões multiplas escolhas</span>
                    <span class="info-box-number">Acessos 0 de 3</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="far fa-comment"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Questão opnião aberta</span>
                    <span class="info-box-number">Acessos 0 de 3</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="far fa-file-pdf"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Enviao do arquivo PDF</span>
                    <span class="info-box-number">Não enviado</span>
                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Acesso</span>
                    <span class="info-box-number">Liberado</span>
                </div>

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="mb-4">
                <h4>Muita atenção:</h4>
                <ol>
                    <li>Para começar clique sobre o botão <strong>INICIAR AVALIAÇÃO</strong>.</li>
                    <li>Ao iniciar sua avaliação não <strong>MUDE, MINIMIZE OU FECHE</strong> a janela do seu browser.</li>
                    <li>Você tem o direito de acessar 3 vezes cada avaliação.</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <br />
                            <i class="fa fa-check fa-5x text-center"></i>
                            <span class="info-box-text text-center text-muted">Questões multiplas escolhas</span>
                            <br />
                            @if ($multipleResponses->count() == 0)
                                <a href="{{ route('classroom.avaluation.multiple', ['id' => $subject->id]) }}"
                                    class="info-box-number text-center text-white mb-0 btn btn-info">INICIAR
                                    AVALIAÇÃO</a>
                            @else
                                <a href="#"
                                    class="info-box-number text-center text-dark mb-0 btn btn-default">AVALIAÇÃO
                                    FINALIZADA</a>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <br />
                            <i class="fa fa-comment fa-5x text-center"></i>
                            <span class="info-box-text text-center text-muted">Questões de conhecimentos</span>
                            <br />

                            @if ($openResponses->count() == 0)
                                <a href="{{ route('classroom.avaluation.open', ['id' => $subject->id]) }}"
                                    class="info-box-number text-center text-white mb-0 btn btn-info">INICIAR
                                    AVALIAÇÃO</a>
                            @else
                                <a href="#"
                                    class="info-box-number text-center text-dark mb-0 btn btn-default">AVALIAÇÃO
                                    FINALIZADA</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <br />
                            <i class="fa fa-file-pdf fa-5x text-center"></i>
                            <span class="info-box-text text-center text-muted">Trabalho acadêmico</span>
                            <br />
                            @if ($jobs->count() == 0)
                                <a href="{{ route('classroom.avaluation.job', ['id' => $subject->id]) }}"
                                    class="info-box-number text-center text-white mb-0 btn btn-info">ENVIAR
                                    ARQUIVO</a>
                            @else
                                <a href="#"
                                    class="info-box-number text-center text-dark mb-0 btn btn-default">ARQUIVO ENVIADO</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="my-3">Histórico Avaliativo do aluno</h4>

            <div class="card card-info card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-multiple-tab" data-toggle="pill"
                                href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                aria-selected="true">Questões multiplas escolhas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-open-tab" data-toggle="pill"
                                href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                                aria-selected="false">Questões de conhecimento</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel"
                            aria-labelledby="custom-tabs-one-multiple-tab">

                            @if ($multipleResponses->count() == 0)
                                <span>Você não iniciou a avaliação de questões com multiplas escolhas.</span>
                            @else
                                @php $nota = 0 @endphp

                                @foreach ($multipleResponses as $response)
                                    @if ($response->multiple_question->gabarito == $response->option)
                                        @php
                                            $nota++;
                                        @endphp
                                    @endif
                                @endforeach

                                @if (number_format($nota, 1, '.', '') > 7.0)
                                    <div class="alert alert-success alert-dismissible">
                                        <h5><i class="fa fa-thumbs-up"></i> Sua nota foi:
                                            <label>{{ number_format($nota, 1, '.', '') }}</label>
                                        </h5>
                                        Parabéns! Você passou e tirou uma boa nota.
                                    </div>
                                @elseif (number_format($nota, 1, '.', '') > 9.0)
                                    <div class="alert alert-primary alert-dismissible">
                                        <h5><i class="fa fa-star"></i> Sua nota foi:
                                            <label>{{ number_format($nota, 1, '.', '') }}</label>
                                        </h5>
                                        Parabéns! Você passou e tirou uma excelente nota.
                                    </div>
                                @else
                                    <div class="alert alert-danger alert-dismissible">
                                        <h5><i class="fa fa-thumbs-down"></i> Sua nota foi:
                                            <label>{{ number_format($nota, 1, '.', '') }}</label>
                                        </h5>
                                        Você não consegui atingir a nota minima e foi reprovado.
                                    </div>
                                @endif

                                <table class="table table-striped">
                                    <tbody>

                                        @foreach ($multipleResponses as $response)
                                            <tr>
                                                <td>{{ $response->multiple_question->question }}</td>
                                                <td class="text-center">
                                                    @if ($response->multiple_question->gabarito == $response->option)
                                                        <i class="fa fa-check text-blue"></i>
                                                    @else
                                                        <i class="fa fa-times text-red"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            @endif

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-one-profile-tab">

                            @if ($openResponses->count() == 0)
                                <span>Você não iniciou a avaliação de questões de conhecimento.</span>
                            @else
                                @php $soma_nota = 0; @endphp
                                @foreach ($openResponses as $response)
                                    @php
                                        $soma_nota += $response->nota;
                                    @endphp
                                @endforeach

                                @if (number_format($soma_nota, 1, '.', '') == 0)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="fa fa-exclamation-triangle"></i> Aguarde...
                                            @if ($soma_nota != 0)
                                                <label>{{ number_format($soma_nota, 1, '.', '') }}</label>
                                            @endif
                                        </h5>
                                        Sua resposta está sendo avaliado pelo professor, aguarde sua nota.
                                    </div>
                                @elseif (number_format($soma_nota, 1, '.', '') >= 7.0)
                                    <div class="alert alert-success alert-dismissible">
                                        <h5><i class="fa fa-thumbs-up"></i> Sua nota foi:
                                            <label>{{ number_format($soma_nota, 1, '.', '') }}</label>
                                        </h5>
                                        Parabéns! Você passou e tirou uma boa nota.
                                    </div>
                                @elseif (number_format($soma_nota, 1, '.', '') >= 9.0)
                                    <div class="alert alert-primary alert-dismissible">
                                        <h5><i class="fa fa-star"></i> Sua nota foi:
                                            <label>{{ number_format($soma_nota, 1, '.', '') }}</label>
                                        </h5>
                                        Parabéns! Você passou e tirou uma excelente nota.
                                    </div>
                                @else
                                    <div class="alert alert-danger alert-dismissible">
                                        <h5><i class="fa fa-thumbs-down"></i> Sua nota foi:
                                            <label>{{ number_format($soma_nota, 1, '.', '') }}</label>
                                        </h5>
                                        Você não consegui atingir a nota minima e foi reprovado.
                                    </div>
                                @endif

                                @foreach ($openResponses as $response)
                                    <label>{{ $response->open_question->question }}</label><br />
                                    <p>{{ $response->comments }}</p>
                                    <label>Nota: {{ number_format($response->nota, 1, '.', '') }}</label>
                                    <hr />
                                @endforeach
                            @endif

                        </div>
                    </div>
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
    <script></script>
@stop
