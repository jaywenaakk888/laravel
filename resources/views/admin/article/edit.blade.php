@extends('layouts.app')

@section('content')
@include('UEditor::head');
<meta name="_token" content="{{ csrf_token() }}"/>
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">编辑 article</div>

        <div class="panel-body">

          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong>注意!</strong>输入出错：<br><br>
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
            <!-- 加载编辑器的容器 -->
            <script id="container" name="content" type="text/plain">
            {!! $article->content !!}
            </script>
            <!-- 实例化编辑器 -->
            <script type="text/javascript">
              var ue = UE.getEditor('container',{
                toolbars: [
                  [   
                      'forecolor', //字体颜色  
                      'backcolor', //背景色  
                      'fontfamily', //字体  
                      'fontsize', //字号
                      'spechars', //特殊字符          
                      '|',        
                      'bold', //加粗  
                      'italic', //斜体  
                      'underline', //下划线  
                      'strikethrough', //删除线
                      'fontborder', //字符边框
                      'superscript', //上标
                      'subscript', //下标  
                      '|',  
                      'formatmatch', //格式刷  
                      'removeformat', //清除格式
                      'pasteplain', //纯文本粘贴模式
                      'cleardoc', //清空文档  
                      '|',  
                      'insertorderedlist', //有序列表  
                      'insertunorderedlist', //无序列表  
                      'paragraph', //段落格式  
                      'justifyleft', //居左对齐
                      'justifyright', //居右对齐
                      'justifycenter', //居中对齐
                      'justifyjustify', //两端对齐              
                      'horizontal', //分隔线
                      'pagebreak', //分页  
                      '|',  
                      'inserttable', //插入表格 
                      'insertrow', //前插入行
                      'insertcol', //前插入列
                      'mergeright', //右合并单元格
                      'mergedown', //下合并单元格
                      'deleterow', //删除行
                      'deletecol', //删除列
                      'splittorows', //拆分成行
                      'splittocols', //拆分成列
                      'splittocells', //完全拆分单元格
                      'deletecaption', //删除表格标题
                      'mergecells', //合并多个单元格
                      'edittable', //表格属性
                      'edittd', //单元格属性
                      'deletetable', //删除表格 
                      '|',  
                      'simpleupload', //单图上传
                      'imagenone', //默认
                      'imageleft', //左浮动
                      'imagecenter', //居中 
                      'imageright', //右浮动  
                      'attachment', //附件  
                      '|',  
                      'blockquote', //引用  
                      'insertcode', //代码语言  
                      '|',  
                      'source', //源代码  
                      'preview', //预览  
                      'fullscreen', //全屏  
                      'help', //帮助
                  ]
                ]
            });
                ue.ready(function() {
                ue.setHeight(600);
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.    
            });
            </script>
            <br>
            <br>
            <button class="btn btn-lg btn-info">编辑 article</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection
