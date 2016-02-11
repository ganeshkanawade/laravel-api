<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $connection = 'subdomain';
    
    protected $fillable = ['title','post','user_id'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
