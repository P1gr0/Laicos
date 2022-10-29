<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friends = [];
        $requests = Friend::where([['friend_id', Auth::user()->id], ['status', 'pending']])->get();
        foreach ($requests as $request) {
            $friend = User::find($request->user_id);
            $friends[] = [
                'id' => $friend->id, 
                'image' => $friend->image ? $friend->image : 'default.png', 
                'name' => $friend->name];
        }

        return $friends;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    public function getStatus($id)
    {
        if ($friend = Friend::where([['user_id', Auth::user()->id], ['friend_id', $id]])->first())
            return ($friend->status == 'pending') ? 'Request sent' : 'Friend';

        if ($friend = Friend::where([['friend_id', Auth::user()->id], ['user_id', $id]])->first())
            return ($friend->status == 'pending') ? 'Request received' : 'Friend';

        return '';
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

        return 'Request sent';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $friends = [];
        $friendships = Friend::where([['user_id', $user_id], ['status', 'accepted']])
            ->orWhere([['friend_id', $user_id], ['status', 'accepted']])
            ->get();

        foreach ($friendships as $friendship) {
            if ($friendship->user_id != $user_id) $friend = User::find($friendship->user_id);
            else $friend = User::find($friendship->friend_id);
            $friends[] = ['id' => $friend->id, 'image' => $friend->image, 'name' => $friend->name];
        }

        return $friends;
    }

    public function countRequests(){
        return Friend::where([['friend_id', Auth::user()->id], ['status', 'pending']])
        ->count();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function edit($friend_id)
    {
        //
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
    }
}
