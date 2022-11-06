<?php

namespace App\Models;

use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, Likeable;
    protected $hidden = ['user_id'];
    protected $fillable = ['content', 'image', 'user_id', 'post_id'];

    public function post(){
        return $this->belongsTo('App\Models\Post');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function isAuthor(){
        return $this->user == Auth::user();
    }
}