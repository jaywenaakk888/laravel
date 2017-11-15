@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    @foreach($tags AS $tag)
    <div class="col-md-4">
      <p>
        <a href="{{url('article/index/tag/'.$tag->id)}}" role="button"  style="height:100px; text-align:center; line-height:100px;color: rgb(0, 0, 0); background-color: rgb(153, 204, 204);font-size:30px;"  class="btn btn-block">
        {{$tag->name}}
        </a>
      </p>
    </div>
    @endforeach
  </div>

</div>

@endsection
