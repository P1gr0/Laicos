<?php

namespace App\Http\Controllers;
use App\Events\MessageSent;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'message' => $request->input('message'),
            'user_id' => $request->user()->id
        ]);

        broadcast(new MessageSent(Auth::user(), $message))->toOthers();
        
        return ['status' => 'Message Sent!'];
       
    }
}
