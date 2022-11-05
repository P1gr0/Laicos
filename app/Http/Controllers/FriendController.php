<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the friend requests received for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::whereIn('id', Friend::where([['friend_id', Auth::user()->id], ['status', 'pending']])->select('user_id'))->get();
    }

    public function getStatus($id)
    {
        if ($friend = Friend::where([['user_id', Auth::user()->id], ['friend_id', $id]])->first())
            return ($friend->status == 'pending') ? ['Request sent', 'Cancel'] : ['Friend', 'Remove Friend'];
        else if ($friend = Friend::where([['friend_id', Auth::user()->id], ['user_id', $id]])->first())
            return ($friend->status == 'pending') ? ['Request received', 'Remove'] : ['Friend', 'Remove Friend'];
        else
            return ['', 'Add Friend'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If the user you're sending the request to has sent you a request in the same time
        if (Friend::where([['user_id', $request->friend], ['friend_id', $request->user()->id]])->first())
            return 'Request received';

        //else 
        Friend::create([
            'user_id' => $request->user()->id,
            'friend_id' => $request->friend,
            'status' => 'pending'
        ]);

        return ['Request sent', 'Cancel'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        return User::find($user_id)->friends()->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function update($friend_id)
    {
        Friend::where([['user_id', $friend_id], ['friend_id', Auth::user()->id]])->update([
            'status' => 'accepted'
        ]);
        return ['Friend', 'Remove Friend'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Friend::where([['friend_id', Auth::user()->id], ['user_id', $id]])
            ->orWhere([['user_id', Auth::user()->id], ['friend_id', $id]])->delete();
        return['', 'Add Friend'];
    }
}
