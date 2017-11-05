@extends('layouts.app')

@section('content')
<div class="container" id="content">
<ul>
    @foreach ($articles as $article)
    <li>
        <div class="title">
            <h4>{{ $article->title }}</h4>
        </div>
        <div>
            <a href="{{ URL('admin/article/'.$article->id.'/edit') }}" class="btn btn-success">编辑</a>
            <form action="{{ URL('admin/article/'.$article->id) }}" method="POST" style="display: inline;">
                <input name="_method" type="hidden" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-danger">作废</button>
            </form>
        </div>
    </li>
    <hr>
    @endforeach
</ul>
{!! $articles->render() !!}
</div>
@endsection
