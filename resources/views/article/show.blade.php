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
        {!! $article->content !!}
    </div>
</div>
@endsection