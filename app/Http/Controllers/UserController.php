<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function search($name){
        $users = User::where('name', 'like', "%$name%")->get();
        foreach($users as $user)
            $user->image = $user->image ? $user->image : 'default.png';
        return view('users.search')->with('users', $users);
    }

    public function getCounts(User $user){
        return [$user->posts->count(), $user->comments->count()];
    }

    public function setImage(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:png,jpg,jpeg|max:10000'
        ]);

        $file_name = NULL;
        if ($request->image) {
            $file_name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/profile'), $file_name);
        }

        if($request->user()->image)
            File::delete(public_path('images/profile/' . $request->user()->image));

        $request->user()->update([
            "image" => $file_name
        ]);
    }

    public function deleteImage(Request $request, User $user)
    {
        if($request->user()->image){
            File::delete(public_path('images/profile/' . $request->user()->image));
            $user->update([
                "image" => null
            ]);
        }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        if($user == Auth::user()) return view('home')->with('user', $user);
        return view('users.show', ['user' => $user]);
    }

    public function getPosts(User $user){
        return $user->posts->sortByDesc('created_at')->values();
/*         $result = $user->posts()->with('user')->orderBy('created_at', 'desc')->paginate(10); */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
