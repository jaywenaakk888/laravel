<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use DB;

class ArticleController extends Controller
{
    /**
     * 通过搜索显示文章列表
     */
    public function indexBysearch(Request $request){
        $search_title = $request->input('search_title','');
        $articles = DB::table('article')->select('article.id','article.title','admins.name','article.updated_at')->leftJoin('admins','article.user_id','=','admins.id')->where('article.state','=','1')->where('article.title','like','%'.$search_title.'%')->orderBy('article.id','desc')->paginate(10);
        $articles = $articles->appends(['search_title'=>$search_title]);
        return view('article/index')->with('articles',$articles)->with('search_title',$search_title);
    }

    /**
     * 通过标签显示文章列表
     */
    public function indexByTag($id){
        $tag_id = ','.$id.',';
        $articles = DB::table('article')->select('article.id','article.title','admins.name','article.updated_at')->leftJoin('admins','article.user_id','=','admins.id')->where('article.state','=','1')->where('article.tag_id','like','%'.$tag_id.'%')->orderBy('article.id','desc')->paginate(10);
        return view('article/index')->with('articles',$articles);
    }

    /**
     * 显示文章
     */
    public function show($id){
        $article = Article::find($id);
        $user = Article::find($id)->user;
        return view('article/show')->with('article',$article)->with('user',$user);
    }
}
