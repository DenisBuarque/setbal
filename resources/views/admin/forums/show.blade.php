@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h4 style="line-height: 1; margin-bottom: 0">{{ $forum->title }}</h4>
            <span>{{ $forum->course->title }}: {{ $forum->subject->title }}</span>
        </div>
        <a href="{{ route('admin.forums.index') }}"
            class="btn btn-md btn-info d-flex justify-content-center align-items-center" title="Adicionar novo registro">
            <i class="fa fa-plus mr-1"></i> Listar Forums
        </a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div id="message" class="alert alert-danger mb-2" role="alert">
            <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-9">

            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Forum</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Comentar no Forum</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        {!! $forum->description !!}
                        <strong>Comentários do forum:</strong>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">

                            @forelse ($comments as $comment)
                                <div class="post">
                                    <div class="user-block">
                                        @if (isset($comment->user->image))
                                            <img class="img-circle img-bordered-sm"
                                                src="{{ asset('storage/' . $comment->user->image) }}"
                                                alt="{{ $comment->user->name }}">
                                        @else
                                            <img class="img-circle img-bordered-sm"
                                                src="https://dummyimage.com/28x28/b6b7ba/fff" alt="User Image">
                                        @endif

                                        <span class="username">
                                            <a href="#">{{ $comment->user->name }}</a>
                                        </span>
                                        <span class="description">Publicado -
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:m:s') }}</span>
                                    </div>

                                    <p class="mb-0">{{ $comment->comment }}</p>

                                    <p>
                                        <span class="float-right">
                                            <i class="far fa-comments mr-1"></i> Comentário(s)
                                            {{ $opinions->where('comment_id', $comment->id)->count() }}
                                        </span>
                                    </p>

                                    <form method="POST" action="{{ route('admin.forums.opinions') }}">
                                        @csrf
                                        <div class="input-group">
                                            <input type="hidden" name="forum_id" value="{{ $forum->id }}" />
                                            <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                                            <input type="text" name="opinion" id="opinion" required
                                                placeholder="Deixe seu comentário." class="form-control">
                                            <span class="input-group-append">
                                                <button type="submit" id="send"
                                                    class="btn btn-primary">Enviar</button>
                                            </span>
                                        </div>
                                    </form>

                                    <div class="card-footer card-comments">
                                        @foreach ($opinions as $opinion)
                                            @if ($opinion->comment_id == $comment->id)
                                                <div class="card-comment">
                                                    <img class="img-circle img-sm"
                                                        src="https://dummyimage.com/28x28/b6b7ba/fff" alt="User Image">
                                                    <div class="comment-text">
                                                        <span class="username">
                                                            {{ $opinion->user->name }}
                                                            <span
                                                                class="text-muted float-right">{{ \Carbon\Carbon::parse($opinion->created_at)->format('d/m/Y H:m:s') }}</span>
                                                        </span>
                                                        {{ $opinion->opinion }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            @empty
                                <div class="text-center">Nenhum comentário adicionado.</div>
                            @endforelse

                        </div>

                        <div class="tab-pane" id="settings">
                            <form method="POST" action="{{ route('admin.forums.comments') }}" class="form-horizontal">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                                        <input type="hidden" name="forum_id" value="{{ $forum->id }}" />
                                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" id="inputExperience"
                                            placeholder="Deixe aqui seu comentário no forum"></textarea>
                                        @error('comment')
                                            <small class="text-red line-height">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-danger">Enviar comentário</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">

            <h4><i class="fa fa-users"></i> Aluno(as):</h4>
            @forelse ($inscriptions as $inscription)
                <div class="d-flex align-items-center mb-1 p-2">
                    @if (isset($inscription->user->image))
                        <img src="{{ asset('storage/' . $inscription->user->image) }}"
                            alt="{{ $inscription->user->name }}" class="img-circle img-size-32 mr-2">
                    @else
                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="{{ $inscription->user->name }}"
                            class="img-circle img-size-32 mr-2">
                    @endif

                    <p style="line-height: 1; margin-bottom: 0">
                        {{ $inscription->user->name }}
                        <br /><small>Aluno(a)</small>
                    </p>
                </div>
            @empty
                <div class="d-flex align-items-center">
                    <span>Lista vazia</span>
                </div>
            @endforelse

        </div>

    @stop

    @section('css')
        <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
    @stop

    @section('js')
        <script>
            function confirmaExcluir() {
                var conf = confirm(
                    "Deseja mesmo excluir o registro? Os dados serão perdidos e não poderão ser recuperados novamente.");
                if (conf) {
                    return true;
                } else {
                    return false;
                }
            }

            setTimeout(() => {
                document.getElementById("message").style.display = "none";
            }, 5000);
        </script>
    @stop
