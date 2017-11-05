@extends('layouts.app')

@section('content')
<meta name="_token" content="{{ csrf_token() }}"/>
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">编辑 article</div>

        <div class="panel-body">

          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br><br>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ URL('admin/article/'.$article->id) }}" method="POST">
            <input name="_method" type="hidden" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            Title:<input type="text" name="title" class="form-control" required="required" value="{{$article->title}}" >
            <br>
            Tag:<select  name="tag" class="form-control" required="required">
            @foreach ($tags AS $tag)
                @if($tag->id == $article_tag->id )
                <option value="{{$tag->id}}" selected>{{$tag->name}}</option>
                @else
                <option value="{{$tag->id}}">{{$tag->name}}</option>
                @endif
            @endforeach
            </select>
            <br>
            image:图片可以在文本框内插入多次，注意不要修改插入的图片信息。<input type="file" name="picture" id="picture">
            <br>
            content:<textarea name="content" id="content" rows="10" class="form-control" required="required">{{$article->original_content}}</textarea>
            <br>
            <button class="btn btn-lg btn-info">编辑 article</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $("#picture").change(function(){
    if(this.files.length>0){
        var picture = this.files[0];
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/upload/picture', true);
        var fd = new FormData;
        fd.append("picture", picture);
        fd.append("_token", $('meta[name="_token"]').attr('content'));
			xhr.onload = function(e) {
				var data = JSON.parse(xhr.responseText);
				if (data.error) {
					alert(data.error);
					return;
				}else{
          alert('成功在文本框内插入图片：'+data.filename+'，请换行后再进行输入并不要修改插入信息!');
          var str = $("#content").val()+'\n[!image]'+data.filename+'">\n';
          $("#content").val(str)
        }
			};
			xhr.send(fd);
    }

  })
})
</script>
@endsection
