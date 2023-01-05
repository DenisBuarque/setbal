@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.teachers.update',['id' => $teacher->id]) }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de cadastro de professor:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <small>Foto do professor:</small>
                            <div class="form-group">
                                <input type="file" name="image" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" id="course" class="form-control">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        @if ($teacher->course_id == $course->id)
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
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Disciplina: *</small>
                                <select name="subject_id" id="subject" class="form-control">


                                </select>
                                @error('subject_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-0">
                                <small>Nome do professor: *</small>
                                <input type="text" name="name" value="{{ $teacher->name ?? old('name') }}" maxlength="100"
                                    class="form-control @error('title') is-invalid @enderror">
                                @error('name')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Nº telefone: *</small>
                                <input type="tel" name="phone" value="{{ $teacher->phone ?? old('phone') }}"
                                    onkeyup="handlePhone(event)" placeholder="(00)90000-0000" maxlength="15"
                                    class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group m-0">
                                <small>E-mail: *</small>
                                <input type="email" name="email" value="{{ $teacher->email ?? old('email') }}" maxlength="100"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Senha:</small>
                                <input type="password" name="password" value="{{ old('password') }}" maxlength="50"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <small>Permissão de acesso ao sistema: *</small>
                                <select name="active" class="form-control @error('status') is-invalid @enderror">
                                    <option value="inativo" @if ($teacher->status == 'inativo') selected @endif>Inativo</option>
                                    <option value="ativo" @if ($teacher->status == 'ativo') selected @endif>Ativo</option>
                                </select>
                                @error('status')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <small>Instituição: *</small>
                                <select name="local" class="form-control @error('status') is-invalid @enderror">
                                    <option value="setbal" @if ($teacher->local == 'setbal') selected @endif>Setbal</option>
                                    <option value="ead" @if ($teacher->local == 'ead') selected @endif>EAD</option>
                                </select>
                                @error('status')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.teachers.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            toolbar_mode: 'floating',
        });
    </script>

    <script>
        $('#course').change(function() {

            $("#subject option").remove();
            
            var course_id = $(this).val();

            if (course_id !== "") 
            {
                $.ajax({
                    type: 'GET',
                    url: "/admin/teachers/list/" + course_id,
                    dataType: 'html',
                    success: function(response) {
                        $("#subject").append(response);
                    }
                })
            }
        });

        $('#subject').ready(function() {       
            $.ajax({
                type: 'GET',
                url: "/admin/teachers/list/{{ $teacher->course_id }}",
                dataType: 'html',
                success: function(response) {
                    $("#subject").append(response);
                }
            })
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
        const handlePhone = (event) => {
            let input = event.target
            input.value = phoneMask(input.value)
        }

        const phoneMask = (value) => {
            if (!value) return ""
            value = value.replace(/\D/g, '')
            value = value.replace(/(\d{2})(\d)/, "($1) $2")
            value = value.replace(/(\d)(\d{4})$/, "$1-$2")
            return value
        }

        //criação de mascara
        function mascara(t, mask) {
            var i = t.value.length;
            var saida = mask.substring(1, 0);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                t.value += texto.substring(0, 1);
            }
        }
    </script>

@stop
