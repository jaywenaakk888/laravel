<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * 指定名
     */
    protected $table = 'article';

    /**
     * 与user表关联
     */
    public function user(){
        return $this->belongsTo('App\User');
    }

    /**
     * 与tags表关联
     */
    public function tag(){
        return $this->belongsTo('App\Model\Tag');
    }
}
