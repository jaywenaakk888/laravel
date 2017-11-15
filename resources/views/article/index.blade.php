@extends('layouts.app')

@section('content')
<div class="container" id="content">
<ul>
    @foreach ($articles as $article)
    <li>
        <div class="title">
            <a href="{{ URL('article/show/'.$article->id) }}">
                <h4>{{ $article->title }}</h4>
            </a>
        </div>
        <div class="name">
            <p>作者：{{ $article->name }}</p>
            <p>修改时间：{{ $article->updated_at }}</p>
        </div>
    </li>
    <hr>
    @endforeach
</ul>
{!! $articles->render() !!}
</div>
@endsection