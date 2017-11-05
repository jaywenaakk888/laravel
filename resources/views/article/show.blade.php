@extends('layouts.app')

@section('content')
<div class="container" id="content">
    <h1 style="text-align: center; margin-top: 50px;">{{ $article->title }}</h1>
    <hr>
    <div id="user" style="text-align: right;">
        作者：{{ $user->name }}
    </div>
    <div id="date" style="text-align: right;">
        修改时间：{{ $article->updated_at }}
    </div>
    <div id="content" style="padding: 50px;">
    <img src="/http.laravel.dev/uploads/2017-11-03-12-42-51-59fc644bd79f3.jpg" class="img-responsive" >
    <p>
      {!! $article->content !!}
    </p>
  </div>
</div>
@endsection