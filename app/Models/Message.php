<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'user_id', 'receiver_id'];

    public function user(){
        return $this->belongsToMany('App\Models\User', 'messages', ['receiver_id', 'user_id']);
    }
}
