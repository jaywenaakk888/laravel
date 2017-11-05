<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;

class ArticleController extends Controller
{
    /**
     * 显示文章
     */
    public function show($id){
        $article = Article::find($id);
        $user = Article::find($id)->user;
        return view('article/show')->with('article',$article)->with('user',$user);
    }
}
