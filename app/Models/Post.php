<?php

namespace App\Models;

use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Likeable;

    protected $fillable = ['title', 'content', 'user_id', 'image'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }
}
