<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use Illuminate\Support\Facades\Redis;
use App\Model\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $redis = Redis::connection('home');
        if(empty($redis->get('tag'))){
            $this->setTags();
        }
        $tags = json_decode($redis->get('tag'));
        return view('home')->with('tags',$tags);
    }

    /**
     * 将tag写入redis
     */
    protected function setTags(){
        $tags = Tag::select('id','name')->get();
        $redis = Redis::connection('home');
        $tags_json = json_encode($tags);
        $redis->set('tag',$tags_json);
        return $tags_json;
    }
}
