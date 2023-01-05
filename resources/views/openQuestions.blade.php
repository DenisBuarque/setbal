@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Questões abertas {{ $subject->course->title }} / <label>{{ $subject->title }}</label></h1>
        <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}"
            class="btn btn-md btn-info  d-flex align-items-center justify-content-center" title="Finalizar avaliação">
            <i class="fa fa-times mr-1"></i> Sair da avaliação
        </a>
    </div>
@stop

@section('content')

<div class="container-fluid">
    <form method="POST" action="{{ route('classroom.avaluation.open.response') }}" autocomplete="off" onsubmit="return mySubmit()" autocomplete="off">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="course_id" value="{{ $subject->course->id }}" />
        <input type="hidden" name="subject_id" value="{{ $subject->id }}" />
        <div class="card card-info" style="max-width: 1024px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Questões de conhecimento:</h3>
            </div>
            <div class="card-body">

                @foreach ($openQuestions->random(2) as $key => $question)
                    <input type="hidden" name="question[]" value="{{ $question->id }}" />
                    <span>{{ $question->question }}</span><br/>
                    <textarea name="comments[]" style="width: 100%"></textarea>
                    <hr />
                @endforeach

            </div>
            <div class="card-footer">
                <a href="{{ route('classroom', ['slug' => $subject->course->slug]) }}" type="submit" class="btn btn-default">Cancelar</a>
                <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
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
    <br>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script></script>
@stop
