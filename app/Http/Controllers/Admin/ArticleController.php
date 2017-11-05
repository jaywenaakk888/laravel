<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\Tag;
use Redirect,Auth,Purifier;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::All();
        return view('admin.article.create')->with('tags',$tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request,[
			'title' => 'required|max:255|unique:article',
            'content' => 'required|max:60000',
            'tag' =>'required',
        ]);
        //使用Purifier防止Xss注入
        $original_content = Purifier::clean($request->input('content'));
        //修改content输入的换行符和空格符
        $pattern = array(
            '/ /',//半角下空格
            '/　/',//全角下空格
            '/\r\n/',//window 下换行符
            '/\n/',//Linux && Unix 下换行符
            );
        $replace = array('&nbsp;','&nbsp;','<br />','<br />');
        $str = preg_replace($pattern,$replace,$original_content);
        //修改预插入的图片信息
        $url = '<img class="img-responsive" src="'.env('APP_URL','http://localhost').'/uploads/';
        $content = str_replace('[!image]',$url,$str);

        $article = new Article;
        $article->title = $request->input('title');
        $article->content = $content;
        $article->original_content = $request->input('content');
        $article->user_id = Auth::user()->id;
        $article->tag_id = $request->input('tag');
        $article->state = 1;

        if($article->save()){
            $id = $article->id;
            return Redirect::to('article/'.$id);
        }else{
            return Redirect::back()->withInput()->withError('保存失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        $articles = Article::where('user_id','=',$user_id)->where('state','=','1')->orderBy('id','desc')->paginate(10);
        return view('admin.article.show')->with('articles',$articles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        $article_tag = Article::find($id)->tag;
        $tags = Tag::All();
        return view('admin.article.edit')->with('article',$article)->with('article_tag',$article_tag)->with('tags',$tags);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'title' => 'required|max:255|unique:article,id,'.$id,
            'content' => 'required|max:60000',
            'tag' =>'required',
        ]);
        //使用Purifier防止Xss注入
        $original_content = Purifier::clean($request->input('content'));
        //修改content输入的换行符和空格符
        $pattern = array(
            '/ /',//半角下空格
            '/　/',//全角下空格
            '/\r\n/',//window 下换行符
            '/\n/',//Linux && Unix 下换行符
            );
        $replace = array('&nbsp;','&nbsp;','<br />','<br />');
        $str = preg_replace($pattern,$replace,$original_content);
        //修改预插入的图片信息
        $req_url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $url = '<img class="img-responsive" src="'.$req_url.$_SERVER ['SERVER_NAME'].'/uploads/';
        $content = str_replace('[!image]',$url,$str);

        $article = Article::find($id);
        $article->title = $request->input('title');
        $article->content = $content;
        $article->original_content = $request->input('content');
        $article->user_id = Auth::user()->id;
        $article->tag_id = $request->input('tag');
        $article->state = 1;

        if($article->save()){
            return Redirect::to('article/'.$id);
        }else{
            return Redirect::back()->withInput()->withError('修改失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->state = 0;
        if($article->save()){
            return Redirect::to('admin/article/show');
        }
    }
}
