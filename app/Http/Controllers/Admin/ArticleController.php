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
        $content = Purifier::clean($request->input('content'));

        $article = new Article;
        $article->title = $request->input('title');
        $article->content = $content;
        $article->original_content = $request->input('content');
        $article->user_id = $request->session()->get('admin')['id'];
        if(is_array($request->input('tag'))){
            $tag_id = ','.implode(',',$request->input('tag')).',';
        }
        $article->tag_id = $tag_id;
        $article->state = 1;

        if($article->save()){
            $id = $article->id;
            return Redirect::to('article/show/'.$id);
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
    public function show(Request $request)
    {
        $user_id = $request->session()->get('admin')['id'];
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
        $article_tag = explode(',',$article['tag_id']);
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
        $content = Purifier::clean($request->input('content'));

        $article = Article::find($id);
        $article->title = $request->input('title');
        $article->content = $content;
        $article->original_content = $request->input('content');        
        $article->user_id = $request->session()->get('admin')['id'];
        if(is_array($request->input('tag'))){
            $tag_id = ','.implode(',',$request->input('tag')).',';
        }
        $article->tag_id = $tag_id;
        $article->state = 1;

        if($article->save()){
            return Redirect::to('article/show/'.$id);
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
        $article->title = $article['title'].str_random(5);
        $article->state = 0;
        if($article->save()){
            return Redirect::to('admin/article/show');
        }
    }
}
