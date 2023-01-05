@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.modules.index',['id' => $subject->id ]) }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.modules.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="hidden" name="category" value="file" />
            <input type="hidden" name="subject_id" value="{{ $subject->id }}" />
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de cadastro de arquivo da disciplina: <strong>{{ $subject->title }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <small>Adicione somente arquivos com a extenção: .doc, .docx, .pdf</small>
                            <div class="form-group">
                                <input type="file" name="file" required />
                                @error('file')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-0">
                                <small>Título: *</small>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror" maxlength="200" required />
                                @error('title')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Descrição: *</small>
                                <textarea name="description" class="form-control  @error('description') is-invalid @enderror" required style="width: 100%;" placeholder="Digite uma brve descrição sobre o arquivo.">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.modules.index',['id' => $subject->id]) }}" type="submit" class="btn btn-default">Cancelar</a>
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
