@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="d-flex justify-content-between">
    <h4>Forum discursões: <a href="{{ route('classroom',['slug' => $subject->course->slug])}}">{{ $subject->course->title }} </a> / <label>{{ $subject->title }}</label></h4>
    <a href="{{ route('classroom',['slug' => $subject->course->slug])}}" class="btn btn-md btn-info  d-flex align-items-center justify-content-center" title="Lista disciplinas">
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
                        <br/>
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

                                    <form method="POST" action="{{ route('classroom.forum.opinion') }}">
                                        @csrf
                                        <div class="input-group">
                                            <input type="hidden" name="subject_id" value="{{ $subject->id }}" />
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

                                                    @if (isset($opinion->user->image))
                                                        <img src="{{ asset('storage/' . $opinion->user->image) }}"
                                                            alt="{{ $opinion->user->name }}" class="img-circle img-sm">
                                                    @else
                                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="{{ $opinion->user->name }}"
                                                            class="img-circle img-sm">
                                                    @endif
                                                    
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
                <div class="d-flex align-items-center mb-2 p-2">
                    @if (isset($inscription->student->photo))
                        <img src="{{ asset('storage/' . $inscription->student->photo) }}"
                            alt="{{ $inscription->student->name }}" class="img-circle img-size-32 mr-2">
                    @else
                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="{{ $inscription->student->name }}"
                            class="img-circle img-size-32 mr-2">
                    @endif

                    <p style="line-height: 1; margin-bottom: 0">
                        {{ $inscription->student->name }}
                        <br /><small>Aluno(a)</small>
                    </p>
                </div>
            @empty
                <div class="d-flex align-items-center">
                    <span>Lista vazia</span>
                </div>
            @endforelse

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
