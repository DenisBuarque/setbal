@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Trabalho acadêmico {{ $subject->course->title }} / <label>{{ $subject->title }}</label></h1>
        <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}"
            class="btn btn-md btn-info  d-flex align-items-center justify-content-center" title="Finalizar avaliação">
            <i class="fa fa-times mr-1"></i> Sair da avaliação
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
                <h4>Atenção:</h4>
                <ol>
                    <li>Envie arquivo somente no formato: .pdf, .doc, .docx</li>
                    <li>Tamanho maxímo do arquivo: 5MB</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('classroom.avaluation.store.job') }}" enctype="multipart/form-data" onsubmit="return mySubmit()">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                <input type="hidden" name="course_id" value="{{ $subject->course->id }}" />
                <input type="hidden" name="subject_id" value="{{ $subject->id }}" />

                <input type="file" name="arquivo" required class="border bg-white p-1" style="width: 100%"/>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}" type="submit"
                        class="btn btn-default">Cancelar</a>
                    <div>
                        <button id="button" type="submit" class="btn btn-md btn-info"
                            style="display: block;">
                            <i class="fas fa-save mr-1"></i>
                            Enviar arquivo
                        </button>
                        <button type="button" id="spinner" class="btn btn-md btn-info text-center"
                            style="display: none;">
                            <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Enviando...
                        </button>
                    </div>
                </div>
            </form>

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
