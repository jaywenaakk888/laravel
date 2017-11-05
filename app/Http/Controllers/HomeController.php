<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_title = $request->input('search_title','');
        $articles = DB::table('article')->select('article.id','article.title','users.name','article.updated_at')->leftJoin('users','article.user_id','=','users.id')->where('article.state','=','1')->where('article.title','like','%'.$search_title.'%')->orderBy('article.id','desc')->paginate(10);
        return view('home')->with('articles',$articles);
    }
}
