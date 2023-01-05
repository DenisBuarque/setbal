@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Questões miltiplas {{ $subject->course->title }} / <label>{{ $subject->title }}</label></h1>
        <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}"
            class="btn btn-md btn-info  d-flex align-items-center justify-content-center" title="Finalizar avaliação">
            <i class="fa fa-times mr-1"></i> Sair da avaliação
        </a>
    </div>
@stop

@section('content')

    <div class="container-fluid">
        <form method="POST" action="{{ route('classroom.avaluation.multiple.response') }}" autocomplete="off" autocomplete="off">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
            <input type="hidden" name="course_id" value="{{ $subject->course->id }}" />
            <input type="hidden" name="subject_id" value="{{ $subject->id }}" />
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Questões de multiplas escolhas:</h3>
                </div>
                <div class="card-body">
                    @foreach ($multipleQuestions->random(10) as $key => $question)
                        <input type="hidden" name="question{{ $key }}" value="{{ $question->id }}" />
                        <label>{{ $question->question }}</label><br />
                        <span><input type="radio" name="option{{ $key }}" value="1" />
                            {{ $question->response_one }}</span><br />
                        <span><input type="radio" name="option{{ $key }}" value="2" />
                            {{ $question->response_two }}</span><br />
                        <span><input type="radio" name="option{{ $key }}" value="3" />
                            {{ $question->response_tree }}</span><br />
                        <span><input type="radio" name="option{{ $key }}" value="4" />
                            {{ $question->response_four }}</span>
                        <hr />
                    @endforeach

                </div>
                <div class="card-footer">
                    <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}" type="submit"
                        class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" onclick="checar()" class="btn btn-md btn-info float-right"
                        style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Enviar respostas
                    </button>
                    <button type="button" id="spinner" class="btn btn-md btn-info float-right text-center"
                        style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviando...
                    </button>
                </div>
            </div>
        </form>
        <br />
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        /*$(document).ready(function() {
                        window.addEventListener('blur', function () {
                            alert("Valiação Encerreda, você mudou de página.");
                            location.href = "/classroom/avaluation/{{ $subject->id }}";
                        });
                    });*/

        /*function radio_preenchido(nome) {
            var opcoes = document.getElementsByName(nome);
            for (var i = 0; i < 10; i++) {
                if (!opcoes[i].checked) {
                    return true;
                }
            }
            return false;
        };

        function checar() {
            if (radio_preenchido('option0')) {
                alert('Responda a pergunta 1');
                return false;
            }

            return true;
        };*/

    </script>
@stop
