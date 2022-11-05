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

    public function fetchMessages($receiver_id)
    {
      return Message::where([['user_id', Auth::user()->id], ['receiver_id', $receiver_id]])
            ->orWhere([['user_id', $receiver_id], ['receiver_id', Auth::user()->id]])->get();
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'message' => $request->input('message'),
            'user_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id
        ]);

        broadcast(new MessageSent(Auth::user(), $message))->toOthers();

        return $message;
    }
}
