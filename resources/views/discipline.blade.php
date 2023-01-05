@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h4>Sala curso / <a href="{{ route('classroom',['slug' => $discipline->course->slug])}}">{{ $discipline->course->title }} </a> / <label>{{ $discipline->title }}</label></h4>
        <a href="{{ route('classroom',['slug' => $discipline->course->slug])}}" class="btn btn-md btn-info d-flex align-items-center justify-content-center" title="Lista disciplinas">
            <i class="fa fa-table mr-1"></i> Listar disciplinas
        </a>
    </div>
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
                @forelse ($videos as $video)
                    <div class="col-md-4">
                        
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ $video->link }}" allowfullscreen="false"></iframe>
                        </div>
                        {{ $video->description }}
                    </div>
                @empty
                    <div class="col-md-12 text-center">
                        <p>Nenhum vídeo adicionado.</p>
                    </div>
                @endforelse
            </div>
            <br/>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Conteúdo do modulo de ensino:</h3>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body table-responsive">
                    <div id="contentModule">
                        <label>Clique sobre o título do modulo de ensino para carregar seu conteúdo.</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Download de arquivos</h3>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="py-2">Título do arquivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @forelse ($files as $file)
                                <td class="py-2">
                                    <p style="line-height: 1; margin-bottom: 0">
                                        <a href="#">{{ $file->title }}</a><br />
                                        <small>{{ $file->description }}</small>
                                    </p>
                                </td>
                                @empty
                                <td class="py-2">
                                    <p style="line-height: 1; margin-bottom: 0">
                                        Nenhum arquivo para download disponível.
                                    </p>
                                </td>
                                @endforelse
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modulos de ensino</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        @forelse ($modules as $module)
                            <li class="nav-item">
                                <a href="#" class="nav-link" onClick="showModule({{ $module->id }})">
                                    <i class="far fa-circle"></i>
                                    {{ $module->title }}
                                </a>
                            </li>
                        @empty
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle"></i> Nenhum modulo adicionado a essa disciplina.
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </div>

            </div>

        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function showModule(id) {

            if (id == "") {
                document.getElementById("contentModule").innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("contentModule").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "/classroom/discipline/module/" + id, true);
            xmlhttp.send();
        }
    </script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
@stop
