<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use App\Model\Tag;

class ApiTestController extends Controller
{
    public function index(){
        return view('apitest/index');
    }

    public function send(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $jsonp = $request->input('callback');//get接收jsonp自动生成的函数名  
        $rs = Tag::where('id',$id)->where('name',$name)->get();
        // $rs = array(  
        //     'id' => $id,  
        //     'name' => $name,  
        // );  
        echo $jsonp.'('. json_encode($rs). ')'; //jsonp函数名包裹json数据  
    }
}