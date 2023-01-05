@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.subjects.update', ['id' => $subject->id]) }}" onsubmit="return mySubmit()"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de disciplina:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Instituição:</small>
                                <select name="type" class="form-control @error('type') is-invalid @enderror">
                                    <option value=""></option>
                                    <option value="setbal" @if ($subject->type == 'setbal') selected @endif>Setbal</option>
                                    <option value="ead" @if ($subject->type == 'ead') selected @endif>EAD</option>
                                </select>
                                @error('type')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" class="form-control @error('title') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        @if ($course->id == $subject->course_id)
                                            <option value="{{ $course->id }}" selected>{{ $course->title }}</option>
                                        @else
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group m-0">
                                <small>Título: *</small>
                                <input type="text" name="title" value="{{ $subject->title ?? old('title') }}"
                                    maxlength="100" class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Ano:</small>
                                <select name="year" class="form-control">
                                    <option value="1" @if($subject->year == 1) selected @endif>1º ano</option>
                                    <option value="2" @if($subject->year == 2) selected @endif>2º ano</option>
                                    <option value="3" @if($subject->year == 3) selected @endif>3º ano</option>
                                    <option value="4" @if($subject->year == 4) selected @endif>4º ano</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Semestre:</small>
                                <select name="semester" class="form-control">
                                    <option value="1" @if($subject->semester == 1) selected @endif>1º semestre</option>
                                    <option value="2" @if($subject->semester == 2) selected @endif>2º semestre</option>
                                    <option value="3" @if($subject->semester == 3) selected @endif>3º semestre</option>
                                    <option value="4" @if($subject->semester == 4) selected @endif>4º semestre</option>
                                    <option value="5" @if($subject->semester == 5) selected @endif>5º semestre</option>
                                    <option value="6" @if($subject->semester == 6) selected @endif>6º semestre</option>
                                    <option value="7" @if($subject->semester == 7) selected @endif>7º semestre</option>
                                    <option value="8" @if($subject->semester == 8) selected @endif>8º semestre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Carga Horária:</small>
                                <input type="text" name="workload" value="{{ $subject->workload ?? old('workload') }}"
                                    maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Período:</small>
                                <input type="text" name="period" value="{{ $subject->period ?? old('period') }}"
                                    maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <small>Créditos:</small>
                                <input type="text" name="credits" value="{{ $subject->credits ?? old('credits') }}"
                                    maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <small>Teste avaliativo:</small>
                                <select name="quiz" class="form-control">
                                    <option value="bloqueado" @if ($subject->quiz == 'bloqueado') selected @endif>Bloqueado
                                    </option>
                                    <option value="liberado" @if ($subject->quiz == 'liberado') selected @endif>Liberado
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <small>Ativo:</small>
                                <select name="status" class="form-control">
                                    <option value="sim" @if ($subject->status == 'sim') selected @endif>Sim</option>
                                    <option value="nao" @if ($subject->status == 'nao') selected @endif>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <small>Descrição da disciplina: *</small>
                                @error('description')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                                <textarea name="description" class="form-control" style="width: 100%;" placeholder="Descreva aqui o que o aluno irá aprender nessa disciplina.">{{ $subject->description ?? old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.subjects.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
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

    <!-- tyneMCE -->
    <script src="https://cdn.tiny.cloud/1/cr3szni52gwqfslu3w63jcsfxdpbitqgg2x8tnnzdgktmhzq/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
            toolbar_mode: 'floating',
        });
    </script>
    <!-- butons -->
    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }
    </script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
        $(function() {
            console.log('ajax...');
        });
    </script>

@stop
